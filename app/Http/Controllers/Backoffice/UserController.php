<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of users with statistics and filters
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            if ($request->role === 'admin') {
                $query->where('is_admin', true);
            } elseif ($request->role === 'user') {
                $query->where('is_admin', false);
            }
        }

        // Filter by email verification
        if ($request->filled('verified')) {
            if ($request->verified === 'yes') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->verified === 'no') {
                $query->whereNull('email_verified_at');
            }
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate(15);

        // Get statistics
        $stats = [
            'total' => User::count(),
            'admins' => User::where('is_admin', true)->count(),
            'regular' => User::where('is_admin', false)->count(),
            'verified' => User::whereNotNull('email_verified_at')->count(),
            'unverified' => User::whereNull('email_verified_at')->count(),
            'this_month' => User::whereMonth('created_at', date('m'))
                              ->whereYear('created_at', date('Y'))
                              ->count(),
        ];

        return view('BackOffice.users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('BackOffice.users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'is_admin' => 'boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('avatars', $filename, 'public');
            $validated['avatar'] = $path;
        }

        // Set email as verified for admin-created users
        $validated['email_verified_at'] = now();

        $user = User::create($validated);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'Utilisateur créé avec succès!');
    }

    /**
     * Display the specified user
     */
    public function show($id)
    {
        $user = User::with([
            'dechets',
            'projects',
            'createdTutorials',
            'tutorialComments',
            'tutorialProgress'
        ])->findOrFail($id);

        // Get user statistics
        $stats = [
            'dechets_count' => $user->dechets()->count(),
            'projects_count' => $user->projects()->count(),
            'tutorials_created' => $user->createdTutorials()->count(),
            'tutorials_completed' => $user->tutorialProgress()->where('is_completed', true)->count(),
            'tutorials_in_progress' => $user->tutorialProgress()->where('is_completed', false)->count(),
            'comments_count' => $user->tutorialComments()->count(),
            'total_co2_saved' => $user->total_co2_saved ?? 0,
        ];

        // Recent activity
        $recentDechets = $user->dechets()->orderBy('created_at', 'desc')->limit(5)->get();
        $recentProjects = $user->projects()->orderBy('created_at', 'desc')->limit(5)->get();
        $recentComments = $user->tutorialComments()->with('tutorial')->orderBy('created_at', 'desc')->limit(5)->get();

        return view('BackOffice.users.show', compact('user', 'stats', 'recentDechets', 'recentProjects', 'recentComments'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('BackOffice.users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'is_admin' => 'boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Only update password if provided
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar && \Storage::disk('public')->exists($user->avatar)) {
                \Storage::disk('public')->delete($user->avatar);
            }
            
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('avatars', $filename, 'public');
            $validated['avatar'] = $path;
        }

        $user->update($validated);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'Utilisateur mis à jour avec succès!');
    }

    /**
     * Toggle admin status
     */
    public function toggleAdmin($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent removing admin from yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas modifier votre propre rôle!');
        }

        $user->update(['is_admin' => !$user->is_admin]);

        $role = $user->is_admin ? 'administrateur' : 'utilisateur';
        return back()->with('success', "Rôle changé en {$role} avec succès!");
    }

    /**
     * Toggle email verification
     */
    public function toggleVerification($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->email_verified_at) {
            $user->update(['email_verified_at' => null]);
            return back()->with('success', 'Email marqué comme non vérifié!');
        } else {
            $user->update(['email_verified_at' => now()]);
            return back()->with('success', 'Email vérifié avec succès!');
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte!');
        }

        // Delete avatar
        if ($user->avatar && \Storage::disk('public')->exists($user->avatar)) {
            \Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès!');
    }

    /**
     * Bulk actions on users
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:verify,unverify,make_admin,remove_admin,delete',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $action = $request->action;
        $ids = $request->user_ids;

        // Prevent bulk actions on yourself
        if (in_array(auth()->id(), $ids)) {
            return back()->with('error', 'Vous ne pouvez pas effectuer d\'actions groupées sur votre propre compte!');
        }

        switch ($action) {
            case 'verify':
                User::whereIn('id', $ids)->update(['email_verified_at' => now()]);
                return back()->with('success', count($ids) . ' utilisateurs vérifiés!');

            case 'unverify':
                User::whereIn('id', $ids)->update(['email_verified_at' => null]);
                return back()->with('success', count($ids) . ' utilisateurs marqués comme non vérifiés!');

            case 'make_admin':
                User::whereIn('id', $ids)->update(['is_admin' => true]);
                return back()->with('success', count($ids) . ' utilisateurs promus administrateurs!');

            case 'remove_admin':
                User::whereIn('id', $ids)->update(['is_admin' => false]);
                return back()->with('success', count($ids) . ' administrateurs révoqués!');

            case 'delete':
                $users = User::whereIn('id', $ids)->get();
                foreach ($users as $user) {
                    if ($user->avatar && \Storage::disk('public')->exists($user->avatar)) {
                        \Storage::disk('public')->delete($user->avatar);
                    }
                }
                User::whereIn('id', $ids)->delete();
                return back()->with('success', count($ids) . ' utilisateurs supprimés!');
        }

        return back()->with('error', 'Action invalide!');
    }
}
