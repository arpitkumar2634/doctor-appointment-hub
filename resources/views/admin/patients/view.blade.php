@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mb-4">
            <h2>Patient Details</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.patients') }}">Patients</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $patient->name }}</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="{{ $patient->profile_photo_url }}" alt="{{ $patient->name }}" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    <h4>{{ $patient->name }}</h4>
                    <p class="text-muted">Patient</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5>Contact Information</h5>
                </div>
                <div class="card-body">
                    <p><i class="fas fa-envelope me-2 text-primary"></i> {{ $patient->email }}</p>
                    <p><i class="fas fa-phone me-2 text-primary"></i> {{ $patientProfile->phone_number ?? 'Not specified' }}</p>
                    <p><i class="fas fa-map-marker-alt me-2 text-primary"></i> {{ $patientProfile->address ?? 'Not specified' }}</p>
                    <p><i class="fas fa-calendar me-2 text-primary"></i> Joined {{ $patient->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Personal Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Date of Birth:</strong> {{ $patientProfile->date_of_birth ? \Carbon\Carbon::parse($patientProfile->date_of_birth)->format('M d, Y') : 'Not specified' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Gender:</strong> {{ $patientProfile->gender ? ucfirst($patientProfile->gender) : 'Not specified' }}</p>
                        </div>
                    </div>
                    
                    @if($patientProfile->medical_history)
                        <div class="mt-3">
                            <h6>Medical History</h6>
                            <p>{{ $patientProfile->medical_history }}</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Appointment History</h5>
                </div>
                <div class="card-body">
                    @if($appointments->count() > 0)
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
                                    @foreach($appointments as $appointment)
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
                    @else
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-calendar-times fa-3x text-muted"></i>
                            </div>
                            <p>No appointment history found for this patient.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
