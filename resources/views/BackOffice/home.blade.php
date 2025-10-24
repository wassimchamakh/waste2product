@extends('BackOffice.layouts.app')

<<<<<<< HEAD
@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto p-6">
    <div class="text-center mb-6">
        <h1 class="text-3xl font-bold mb-2">Dashboard</h1>
        <p class="text-gray-600">Welcome to Waste2Product Admin Panel!</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Card 1: Total Users -->
        <div class="bg-white shadow-md rounded-lg p-4">
            <h2 class="text-xl font-semibold text-gray-700">Total Users</h2>
            <p class="text-3xl font-bold text-green-500">1,245</p>
            <p class="text-gray-500">+15% from last month</p>
        </div>
        <!-- Card 2: Total Projects -->
        <div class="bg-white shadow-md rounded-lg p-4">
            <h2 class="text-xl font-semibold text-gray-700">Total Projects</h2>
            <p class="text-3xl font-bold text-green-500">342</p>
            <p class="text-gray-500">+5% from last month</p>
        </div>
        <!-- Card 3: Total Events -->
        <div class="bg-white shadow-md rounded-lg p-4">
            <h2 class="text-xl font-semibold text-gray-700">Total Events</h2>
            <p class="text-3xl font-bold text-green-500">87</p>
            <p class="text-gray-500">+10% from last month</p>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-4 mb-6">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Recent Activity</h2>
        <ul class="space-y-2">
            <li class="flex justify-between">
                <span>User John Doe registered</span>
                <span class="text-gray-500">2 mins ago</span>
            </li>
            <li class="flex justify-between">
                <span>Project "Eco-Friendly Packaging" created</span>
                <span class="text-gray-500">5 mins ago</span>
            </li>
            <li class="flex justify-between">
                <span>Event "Waste Management Workshop" scheduled</span>
                <span class="text-gray-500">10 mins ago</span>
            </li>
        </ul>
    </div>

    <div class="bg-white shadow-md rounded-lg p-4">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Monthly Statistics</h2>
        <div class="flex justify-between">
            <div class="w-1/2 pr-2">
                <canvas id="userChart"></canvas>
            </div>
            <div class="w-1/2 pl-2">
                <canvas id="projectChart"></canvas>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx1 = document.getElementById('userChart').getContext('2d');
    const userChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [{
                label: 'Users',
                data: [300, 400, 350, 500, 600, 800, 1000],
                borderColor: 'rgba(46, 125, 71, 1)',
                backgroundColor: 'rgba(46, 125, 71, 0.2)',
                fill: true,
=======
@section('title', 'Dashboard - Admin Panel')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Tableau de Bord</h1>
        <p class="text-gray-600 mt-2">Bienvenue sur le panneau d'administration Waste2Product</p>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users Card -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Utilisateurs</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_users']) }}</h3>
                    <div class="flex items-center mt-2">
                        @if($changes['users'] >= 0)
                            <span class="text-green-600 text-sm font-medium flex items-center">
                                <i class="fas fa-arrow-up mr-1"></i>
                                +{{ $changes['users'] }}%
                            </span>
                        @else
                            <span class="text-red-600 text-sm font-medium flex items-center">
                                <i class="fas fa-arrow-down mr-1"></i>
                                {{ $changes['users'] }}%
                            </span>
                        @endif
                        <span class="text-gray-500 text-sm ml-2">vs mois dernier</span>
                    </div>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Projects Card -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Projets</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_projects']) }}</h3>
                    <div class="flex items-center mt-2">
                        @if($changes['projects'] >= 0)
                            <span class="text-green-600 text-sm font-medium flex items-center">
                                <i class="fas fa-arrow-up mr-1"></i>
                                +{{ $changes['projects'] }}%
                            </span>
                        @else
                            <span class="text-red-600 text-sm font-medium flex items-center">
                                <i class="fas fa-arrow-down mr-1"></i>
                                {{ $changes['projects'] }}%
                            </span>
                        @endif
                        <span class="text-gray-500 text-sm ml-2">vs mois dernier</span>
                    </div>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-project-diagram text-2xl text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Events Card -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Événements</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_events']) }}</h3>
                    <div class="flex items-center mt-2">
                        @if($changes['events'] >= 0)
                            <span class="text-green-600 text-sm font-medium flex items-center">
                                <i class="fas fa-arrow-up mr-1"></i>
                                +{{ $changes['events'] }}%
                            </span>
                        @else
                            <span class="text-red-600 text-sm font-medium flex items-center">
                                <i class="fas fa-arrow-down mr-1"></i>
                                {{ $changes['events'] }}%
                            </span>
                        @endif
                        <span class="text-gray-500 text-sm ml-2">vs mois dernier</span>
                    </div>
                </div>
                <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Dechets Card -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Déchets</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_dechets']) }}</h3>
                    <div class="flex items-center mt-2">
                        @if($changes['dechets'] >= 0)
                            <span class="text-green-600 text-sm font-medium flex items-center">
                                <i class="fas fa-arrow-up mr-1"></i>
                                +{{ $changes['dechets'] }}%
                            </span>
                        @else
                            <span class="text-red-600 text-sm font-medium flex items-center">
                                <i class="fas fa-arrow-down mr-1"></i>
                                {{ $changes['dechets'] }}%
                            </span>
                        @endif
                        <span class="text-gray-500 text-sm ml-2">vs mois dernier</span>
                    </div>
                </div>
                <div class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-recycle text-2xl text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Users Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Utilisateurs (6 derniers mois)</h3>
                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
            </div>
            <div class="relative w-full" style="height: 280px; max-height: 280px;">
                <canvas id="usersChart"></canvas>
            </div>
        </div>

        <!-- Projects Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Projets (6 derniers mois)</h3>
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
            </div>
            <div class="relative w-full" style="height: 280px; max-height: 280px;">
                <canvas id="projectsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity & User Stats Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Recent Activity -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Activités Récentes</h3>
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @forelse($recentActivities as $activity)
                    <div class="flex items-start space-x-4 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                            @if($activity['color'] === 'blue') bg-blue-100
                            @elseif($activity['color'] === 'green') bg-green-100
                            @elseif($activity['color'] === 'purple') bg-purple-100
                            @else bg-gray-100
                            @endif">
                            <i class="fas {{ $activity['icon'] }}
                                @if($activity['color'] === 'blue') text-blue-600
                                @elseif($activity['color'] === 'green') text-green-600
                                @elseif($activity['color'] === 'purple') text-purple-600
                                @else text-gray-600
                                @endif"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $activity['message'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $activity['time']->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-2"></i>
                        <p>Aucune activité récente</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- User Stats -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistiques Utilisateurs</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-user-shield text-blue-600"></i>
                        <span class="text-sm font-medium text-gray-700">Administrateurs</span>
                    </div>
                    <span class="text-lg font-bold text-blue-600">{{ $userStats['admins'] }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-user text-green-600"></i>
                        <span class="text-sm font-medium text-gray-700">Utilisateurs</span>
                    </div>
                    <span class="text-lg font-bold text-green-600">{{ $userStats['regular_users'] }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-check-circle text-purple-600"></i>
                        <span class="text-sm font-medium text-gray-700">Vérifiés</span>
                    </div>
                    <span class="text-lg font-bold text-purple-600">{{ $userStats['verified'] }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-clock text-gray-600"></i>
                        <span class="text-sm font-medium text-gray-700">Non vérifiés</span>
                    </div>
                    <span class="text-lg font-bold text-gray-600">{{ $userStats['unverified'] }}</span>
                </div>
            </div>

            <!-- Top Contributors -->
            <div class="mt-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Top Contributeurs</h4>
                <div class="space-y-2">
                    @forelse($topContributors as $contributor)
                        <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
                            <div class="flex items-center space-x-2 min-w-0">
                                <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-white text-xs font-bold">{{ strtoupper(substr($contributor->name, 0, 1)) }}</span>
                                </div>
                                <span class="text-sm text-gray-700 truncate">{{ $contributor->name }}</span>
                            </div>
                            <span class="text-xs font-semibold text-green-600 ml-2">{{ $contributor->projects_count + $contributor->dechets_count }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500 text-center py-2">Aucun contributeur</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions Rapides</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="/admin/users/create" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-all">
                <i class="fas fa-user-plus text-2xl text-blue-600 mb-2"></i>
                <span class="text-sm font-medium text-gray-700">Ajouter Utilisateur</span>
            </a>
            <a href="/admin/projects" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-green-500 hover:bg-green-50 transition-all">
                <i class="fas fa-project-diagram text-2xl text-green-600 mb-2"></i>
                <span class="text-sm font-medium text-gray-700">Gérer Projets</span>
            </a>
            <a href="/admin/events" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition-all">
                <i class="fas fa-calendar-plus text-2xl text-purple-600 mb-2"></i>
                <span class="text-sm font-medium text-gray-700">Créer Événement</span>
            </a>
            <a href="/admin/tutorials" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-500 hover:bg-orange-50 transition-all">
                <i class="fas fa-book text-2xl text-orange-600 mb-2"></i>
                <span class="text-sm font-medium text-gray-700">Gérer Tutoriels</span>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Users Chart
    const usersCtx = document.getElementById('usersChart').getContext('2d');
    new Chart(usersCtx, {
        type: 'line',
        data: {
            labels: @json($monthlyData['months']),
            datasets: [{
                label: 'Nouveaux Utilisateurs',
                data: @json($monthlyData['users']),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
>>>>>>> tutoral-branch
            }]
        },
        options: {
            responsive: true,
<<<<<<< HEAD
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
=======
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
>>>>>>> tutoral-branch
                }
            }
        }
    });

<<<<<<< HEAD
    const ctx2 = document.getElementById('projectChart').getContext('2d');
    const projectChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [{
                label: 'Projects',
                data: [50, 70, 60, 80, 90, 110, 120],
                backgroundColor: 'rgba(6, 214, 160, 0.7)',
                borderColor: 'rgba(6, 214, 160, 1)',
                borderWidth: 1
=======
    // Projects Chart
    const projectsCtx = document.getElementById('projectsChart').getContext('2d');
    new Chart(projectsCtx, {
        type: 'bar',
        data: {
            labels: @json($monthlyData['months']),
            datasets: [{
                label: 'Nouveaux Projets',
                data: @json($monthlyData['projects']),
                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                borderColor: 'rgb(34, 197, 94)',
                borderWidth: 2,
                borderRadius: 8,
                hoverBackgroundColor: 'rgb(34, 197, 94)'
>>>>>>> tutoral-branch
            }]
        },
        options: {
            responsive: true,
<<<<<<< HEAD
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
=======
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
>>>>>>> tutoral-branch
                }
            }
        }
    });
</script>
<<<<<<< HEAD
@endsection
=======
@endpush
>>>>>>> tutoral-branch
@endsection