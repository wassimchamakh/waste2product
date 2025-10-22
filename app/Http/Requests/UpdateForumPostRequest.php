<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateForumPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $post = $this->route('post'); // Get post from route model binding
        
        // User must be authenticated AND (own the post OR be admin)
        return auth()->check() && 
               (auth()->id() === $post->user_id || auth()->user()->isAdmin());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:10', 'max:255'],
            'body' => ['required', 'string', 'min:20', 'max:10000'],
            'status' => ['sometimes', 'in:published,draft,archived'],
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire.',
            'title.min' => 'Le titre doit contenir au moins 10 caractères.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'body.required' => 'Le contenu est obligatoire.',
            'body.min' => 'Le contenu doit contenir au moins 20 caractères.',
            'body.max' => 'Le contenu ne peut pas dépasser 10000 caractères.',
        ];
    }
}
