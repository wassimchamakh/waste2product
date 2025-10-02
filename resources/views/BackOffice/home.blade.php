@extends('BackOffice.layouts.app')

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
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                }
            }
        }
    });

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
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                }
            }
        }
    });
</script>
@endsection
@endsection