@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mb-4">
            <h2>Dashboard</h2>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Doctors</h5>
                            <h2 class="mb-0">{{ $doctorsCount }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-user-md fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.doctors') }}" class="text-white text-decoration-none">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Patients</h5>
                            <h2 class="mb-0">{{ $patientsCount }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-users fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.patients') }}" class="text-white text-decoration-none">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Appointments</h5>
                            <h2 class="mb-0">{{ $appointmentsCount }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-calendar-check fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.appointments') }}" class="text-white text-decoration-none">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Appointments</h5>
                </div>
                <div class="card-body">
                    @if($recentAppointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Doctor</th>
                                        <th>Patient</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentAppointments as $appointment)
                                        <tr>
                                            <td>Dr. {{ $appointment->doctorProfile->user->name }}</td>
                                            <td>{{ $appointment->patientProfile->user->name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                                            <td>
                                                @if($appointment->status == 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($appointment->status == 'confirmed')
                                                    <span class="badge bg-success">Confirmed</span>
                                                @elseif($appointment->status == 'completed')
                                                    <span class="badge bg-info">Completed</span>
                                                @elseif($appointment->status == 'cancelled')
                                                    <span class="badge bg-danger">Cancelled</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end mt-3">
                            <a href="{{ route('admin.appointments') }}" class="btn btn-sm btn-primary">View All Appointments</a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-calendar-times fa-3x text-muted"></i>
                            </div>
                            <p>No recent appointments found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Appointment Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6>Pending Appointments</h6>
                        <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $pendingPercentage }}%" aria-valuenow="{{ $pendingPercentage }}" aria-valuemin="0" aria-valuemax="100">{{ $pendingCount }}</div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6>Confirmed Appointments</h6>
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $confirmedPercentage }}%" aria-valuenow="{{ $confirmedPercentage }}" aria-valuemin="0" aria-valuemax="100">{{ $confirmedCount }}</div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6>Completed Appointments</h6>
                        <div class="progress">
                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $completedPercentage }}%" aria-valuenow="{{ $completedPercentage }}" aria-valuemin="0" aria-valuemax="100">{{ $completedCount }}</div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6>Cancelled Appointments</h6>
                        <div class="progress">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $cancelledPercentage }}%" aria-valuenow="{{ $cancelledPercentage }}" aria-valuemin="0" aria-valuemax="100">{{ $cancelledCount }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
