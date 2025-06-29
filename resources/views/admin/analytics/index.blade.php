@extends('layouts.app')

@section('title', 'Analytics Dashboard - Admin Panel')

@push('styles')
<style>
    .stat-card {
        transition: transform 0.2s;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    }
    .chart-container {
        position: relative;
        height: 350px;
        margin: 20px 0;
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .progress-ring {
        width: 120px;
        height: 120px;
    }
    .progress-ring-circle {
        stroke: #007bff;
        stroke-width: 8;
        fill: transparent;
        stroke-dasharray: 283;
        stroke-dashoffset: 283;
        transition: stroke-dashoffset 0.35s;
        transform: rotate(-90deg);
        transform-origin: 50% 50%;
    }
    .metric-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .chart-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #333;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-0">
            <i class="fas fa-chart-line me-2"></i>Analytics Dashboard
        </h2>
        <p class="text-muted">Comprehensive overview of your event management system</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['total_events'] }}</h4>
                        <p class="mb-0">Total Events</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['total_bookings'] }}</h4>
                        <p class="mb-0">Total Bookings</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-ticket-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['total_users'] }}</h4>
                        <p class="mb-0">Registered Users</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">PKR {{ number_format($stats['total_revenue'], 2) }}</h4>
                        <p class="mb-0">Total Revenue</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row mb-4">
    <!-- Monthly Bookings Trend -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Monthly Bookings Trend</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="monthlyBookingsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Event Status Distribution -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Event Status Distribution</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="eventStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue and Booking Status Charts -->
<div class="row mb-4">
    <!-- Monthly Revenue -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Monthly Revenue</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Booking Status Distribution -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-doughnut me-2"></i>Booking Status Distribution</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="bookingStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Events and Recent Activity -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-star me-2"></i>Top Events by Bookings</h5>
            </div>
            <div class="card-body">
                @if($topEvents->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Bookings</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topEvents as $event)
                                    <tr>
                                        <td>{{ Str::limit($event->title, 25) }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $event->bookings_count }}</span>
                                        </td>
                                        <td>PKR {{ number_format($event->bookings_count * $event->price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">No events found</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Activity</h5>
            </div>
            <div class="card-body">
                @if($recentBookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Event</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBookings->take(5) as $booking)
                                    <tr>
                                        <td>{{ Str::limit($booking->user->name ?? 'N/A', 15) }}</td>
                                        <td>{{ Str::limit($booking->event->title ?? 'Event Deleted', 20) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'secondary' : 'danger') }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $booking->created_at->format('M d') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">No recent activity</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Performance Metrics -->
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-percentage me-2"></i>Booking Conversion Rate</h5>
            </div>
            <div class="card-body text-center">
                @php
                    $totalCapacity = $topEvents->sum('capacity');
                    $totalBookings = $stats['total_bookings'];
                    $conversionRate = $totalCapacity > 0 ? ($totalBookings / $totalCapacity) * 100 : 0;
                @endphp
                <div class="progress-ring">
                    <svg class="progress-ring" width="120" height="120">
                        <circle class="progress-ring-circle" stroke-width="8" fill="transparent" r="52" cx="60" cy="60"/>
                    </svg>
                    <div class="position-absolute top-50 start-50 translate-middle">
                        <h3 class="mb-0">{{ number_format($conversionRate, 1) }}%</h3>
                    </div>
                </div>
                <p class="mt-3 mb-0">Conversion Rate</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-trending-up me-2"></i>Average Revenue per Event</h5>
            </div>
            <div class="card-body text-center">
                @php
                    $avgRevenue = $stats['total_events'] > 0 ? $stats['total_revenue'] / $stats['total_events'] : 0;
                @endphp
                <h3 class="text-primary mb-2">PKR {{ number_format($avgRevenue, 2) }}</h3>
                <p class="mb-0">Average Revenue</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user-check me-2"></i>Active Users</h5>
            </div>
            <div class="card-body text-center">
                @php
                    $activeUsers = $stats['total_users'];
                    $totalPotential = $stats['total_users'] + 50; // Assuming potential growth
                    $userActivity = $totalPotential > 0 ? ($activeUsers / $totalPotential) * 100 : 0;
                @endphp
                <h3 class="text-success mb-2">{{ $activeUsers }}</h3>
                <p class="mb-0">Active Users</p>
                <small class="text-muted">{{ number_format($userActivity, 1) }}% of potential</small>
            </div>
        </div>
    </div>
</div>

<!-- Seat Analytics -->
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-3">
            <i class="fas fa-chair me-2"></i>Seat Analytics
        </h4>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Seats</h6>
                        <h3 class="mb-0">{{ number_format($totalSeats) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chair fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Available Seats</h6>
                        <h3 class="mb-0">{{ number_format($availableSeats) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Booked Seats</h6>
                        <h3 class="mb-0">{{ number_format($bookedSeats) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-ticket-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Seat Utilization</h6>
                        <h3 class="mb-0">{{ $seatUtilization }}%</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-percentage fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Seat Section Breakdown
                </h5>
            </div>
            <div class="card-body">
                <canvas id="seatSectionChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Seat Status Distribution
                </h5>
            </div>
            <div class="card-body">
                <canvas id="seatStatusChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Bookings Chart
const monthlyBookingsCtx = document.getElementById('monthlyBookingsChart').getContext('2d');
new Chart(monthlyBookingsCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_keys($monthlyBookings)) !!},
        datasets: [{
            label: 'Bookings',
            data: {!! json_encode(array_values($monthlyBookings)) !!},
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0,0,0,0.1)'
                }
            },
            x: {
                grid: {
                    color: 'rgba(0,0,0,0.1)'
                }
            }
        }
    }
});

// Event Status Chart
const eventStatusCtx = document.getElementById('eventStatusChart').getContext('2d');
new Chart(eventStatusCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode(array_keys($eventStatus)) !!},
        datasets: [{
            data: {!! json_encode(array_values($eventStatus)) !!},
            backgroundColor: ['#28a745', '#6c757d', '#dc3545', '#ffc107'],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_keys($monthlyRevenue)) !!},
        datasets: [{
            label: 'Revenue (PKR)',
            data: {!! json_encode(array_values($monthlyRevenue)) !!},
            backgroundColor: 'rgba(255, 193, 7, 0.8)',
            borderColor: '#ffc107',
            borderWidth: 2,
            borderRadius: 5
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0,0,0,0.1)'
                }
            },
            x: {
                grid: {
                    color: 'rgba(0,0,0,0.1)'
                }
            }
        }
    }
});

// Booking Status Chart
const bookingStatusCtx = document.getElementById('bookingStatusChart').getContext('2d');
new Chart(bookingStatusCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode(array_keys($bookingStatus)) !!},
        datasets: [{
            data: {!! json_encode(array_values($bookingStatus)) !!},
            backgroundColor: ['#28a745', '#dc3545', '#ffc107', '#17a2b8'],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Seat Section Chart
const seatSectionCtx = document.getElementById('seatSectionChart').getContext('2d');
new Chart(seatSectionCtx, {
    type: 'pie',
    data: {
        labels: {!! json_encode($seatSections->pluck('section')) !!},
        datasets: [{
            data: {!! json_encode($seatSections->pluck('total')) !!},
            backgroundColor: ['#007bff', '#28a745', '#ffc107'],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Seat Status Chart
const seatStatusCtx = document.getElementById('seatStatusChart').getContext('2d');
new Chart(seatStatusCtx, {
    type: 'bar',
    data: {
        labels: ['Available', 'Booked', 'Reserved'],
        datasets: [{
            label: 'Seats',
            data: [{{ $availableSeats }}, {{ $bookedSeats }}, {{ $reservedSeats }}],
            backgroundColor: ['#28a745', '#007bff', '#ffc107'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Animate progress rings
document.addEventListener('DOMContentLoaded', function() {
    const circles = document.querySelectorAll('.progress-ring-circle');
    circles.forEach(circle => {
        const radius = circle.r.baseVal.value;
        const circumference = radius * 2 * Math.PI;
        circle.style.strokeDasharray = `${circumference} ${circumference}`;
        circle.style.strokeDashoffset = circumference;
        
        const setProgress = (percent) => {
            const offset = circumference - (percent / 100 * circumference);
            circle.style.strokeDashoffset = offset;
        };
        
        // Animate to 75% (example)
        setTimeout(() => setProgress(75), 500);
    });
});
</script>
@endpush 