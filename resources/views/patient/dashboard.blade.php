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
                            <h5 class="card-title">My Appointments</h5>
                            <h2 class="mb-0">{{ $appointmentsCount }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-calendar-check fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('patient.appointments') }}" class="text-white text-decoration-none">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Available Doctors</h5>
                            <h2 class="mb-0">{{ $doctorsCount }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-user-md fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('patient.doctors') }}" class="text-white text-decoration-none">Find Doctors</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Upcoming Appointments</h5>
                            <h2 class="mb-0">{{ $upcomingAppointmentsCount }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-calendar-day fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="{{ route('patient.appointments') }}" class="text-white text-decoration-none">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Upcoming Appointments</h5>
                </div>
                <div class="card-body">
                    @if($upcomingAppointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Doctor</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingAppointments as $appointment)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $appointment->doctorProfile->user->profile_photo_url }}" class="avatar me-2">
                                                    <div>
                                                        <div>Dr. {{ $appointment->doctorProfile->user->name }}</div>
                                                        <small class="text-muted">{{ $appointment->doctorProfile->specialization }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                                            <td>
                                                @if($appointment->status == 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($appointment->status == 'confirmed')
                                                    <span class="badge bg-success">Confirmed</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end mt-3">
                            <a href="{{ route('patient.appointments') }}" class="btn btn-sm btn-primary">View All Appointments</a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-calendar-times fa-3x text-muted"></i>
                            </div>
                            <p>You don't have any upcoming appointments.</p>
                            <a href="{{ route('patient.doctors') }}" class="btn btn-primary">Find a Doctor</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('patient.doctors') }}" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i> Find a Doctor
                        </a>
                        <a href="{{ route('patient.profile') }}" class="btn btn-outline-primary">
                            <i class="fas fa-user me-2"></i> Update Profile
                        </a>
                    </div>
                    
                    <hr>
                    
                    <h6>Recommended Doctors</h6>
                    @if($recommendedDoctors->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recommendedDoctors as $doctor)
                                <a href="{{ route('patient.doctor-details', $doctor->doctorProfile->id) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $doctor->profile_photo_url }}" class="avatar me-3">
                                        <div>
                                            <div class="fw-bold">Dr. {{ $doctor->name }}</div>
                                            <small class="text-muted">{{ $doctor->doctorProfile->specialization }}</small>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No recommended doctors available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
