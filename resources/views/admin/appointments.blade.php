@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 d-flex justify-content-between align-items-center mb-4">
            <h2>All Appointments</h2>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs mb-4" id="appointmentTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab" aria-controls="upcoming" aria-selected="true">Upcoming</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="false">Pending</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="past-tab" data-bs-toggle="tab" data-bs-target="#past" type="button" role="tab" aria-controls="past" aria-selected="false">Past</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled" type="button" role="tab" aria-controls="cancelled" aria-selected="false">Cancelled</button>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="appointmentTabsContent">
                        <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
                            @if($appointments->where('appointment_date', '>=', today())->whereIn('status', ['confirmed'])->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Doctor</th>
                                                <th>Patient</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($appointments->where('appointment_date', '>=', today())->whereIn('status', ['confirmed']) as $appointment)
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
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $appointment->patientProfile->user->profile_photo_url }}" class="avatar me-2">
                                                            <div>{{ $appointment->patientProfile->user->name }}</div>
                                                        </div>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                                                    <td>
                                                        <span class="badge bg-success">Confirmed</span>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal{{ $appointment->id }}">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        
                                                        <!-- View Modal -->
                                                        <div class="modal fade" id="viewModal{{ $appointment->id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $appointment->id }}" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="viewModalLabel{{ $appointment->id }}">Appointment Details</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <h6>Doctor Information</h6>
                                                                        <p><strong>Name:</strong> Dr. {{ $appointment->doctorProfile->user->name }}</p>
                                                                        <p><strong>Specialization:</strong> {{ $appointment->doctorProfile->specialization }}</p>
                                                                        <p><strong>Email:</strong> {{ $appointment->doctorProfile->user->email }}</p>
                                                                        
                                                                        <hr>
                                                                        
                                                                        <h6>Patient Information</h6>
                                                                        <p><strong>Name:</strong> {{ $appointment->patientProfile->user->name }}</p>
                                                                        <p><strong>Email:</strong> {{ $appointment->patientProfile->user->email }}</p>
                                                                        <p><strong>Phone:</strong> {{ $appointment->patientProfile->phone_number ?? 'Not provided' }}</p>
                                                                        
                                                                        <hr>
                                                                        
                                                                        <h6>Appointment Information</h6>
                                                                        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</p>
                                                                        <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                                                                        <p><strong>Status:</strong> {{ ucfirst($appointment->status) }}</p>
                                                                        
                                                                        @if($appointment->notes)
                                                                            <hr>
                                                                            <h6>Notes</h6>
                                                                            <p>{{ $appointment->notes }}</p>
                                                                        @endif
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <div class="mb-3">
                                        <i class="fas fa-calendar-check fa-3x text-muted"></i>
                                    </div>
                                    <p>No upcoming confirmed appointments found.</p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                            @if($appointments->where('status', 'pending')->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Doctor</th>
                                                <th>Patient</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($appointments->where('status', 'pending') as $appointment)
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
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $appointment->patientProfile->user->profile_photo_url }}" class="avatar me-2">
                                                            <div>{{ $appointment->patientProfile->user->name }}</div>
                                                        </div>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                                                    <td>
                                                        <span class="badge bg-warning">Pending</span>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal{{ $appointment->id }}">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        
                                                        <!-- View Modal -->
                                                        <div class="modal fade" id="viewModal{{ $appointment->id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $appointment->id }}" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="viewModalLabel{{ $appointment->id }}">Appointment Details</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <h6>Doctor Information</h6>
                                                                        <p><strong>Name:</strong> Dr. {{ $appointment->doctorProfile->user->name }}</p>
                                                                        <p><strong>Specialization:</strong> {{ $appointment->doctorProfile->specialization }}</p>
                                                                        <p><strong>Email:</strong> {{ $appointment->doctorProfile->user->email }}</p>
                                                                        
                                                                        <hr>
                                                                        
                                                                        <h6>Patient Information</h6>
                                                                        <p><strong>Name:</strong> {{ $appointment->patientProfile->user->name }}</p>
                                                                        <p><strong>Email:</strong> {{ $appointment->patientProfile->user->email }}</p>
                                                                        <p><strong>Phone:</strong> {{ $appointment->patientProfile->phone_number ?? 'Not provided' }}</p>
                                                                        
                                                                        <hr>
                                                                        
                                                                        <h6>Appointment Information</h6>
                                                                        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</p>
                                                                        <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                                                                        <p><strong>Status:</strong> {{ ucfirst($appointment->status) }}</p>
                                                                        
                                                                        @if($appointment->notes)
                                                                            <hr>
                                                                            <h6>Notes</h6>
                                                                            <p>{{ $appointment->notes }}</p>
                                                                        @endif
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <div class="mb-3">
                                        <i class="fas fa-hourglass-half fa-3x text-muted"></i>
                                    </div>
                                    <p>No pending appointments found.</p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="tab-pane fade" id="past" role="tabpanel" aria-labelledby="past-tab">
                            @if($appointments->where('appointment_date', '<', today())->orWhere('status', 'completed')->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Doctor</th>
                                                <th>Patient</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($appointments->where('appointment_date', '<', today())->orWhere('status', 'completed') as $appointment)
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
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $appointment->patientProfile->user->profile_photo_url }}" class="avatar me-2">
                                                            <div>{{ $appointment->patientProfile->user->name }}</div>
                                                        </div>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                                                    <td>
                                                        @if($appointment->status == 'completed')
                                                            <span class="badge bg-info">Completed</span>
                                                        @else
                                                            <span class="badge bg-secondary">Past</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal{{ $appointment->id }}">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        
                                                        <!-- View Modal -->
                                                        <div class="modal fade" id="viewModal{{ $appointment->id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $appointment->id }}" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="viewModalLabel{{ $appointment->id }}">Appointment Details</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <h6>Doctor Information</h6>
                                                                        <p><strong>Name:</strong> Dr. {{ $appointment->doctorProfile->user->name }}</p>
                                                                        <p><strong>Specialization:</strong> {{ $appointment->doctorProfile->specialization }}</p>
                                                                        <p><strong>Email:</strong> {{ $appointment->doctorProfile->user->email }}</p>
                                                                        
                                                                        <hr>
                                                                        
                                                                        <h6>Patient Information</h6>
                                                                        <p><strong>Name:</strong> {{ $appointment->patientProfile->user->name }}</p>
                                                                        <p><strong>Email:</strong> {{ $appointment->patientProfile->user->email }}</p>
                                                                        <p><strong>Phone:</strong> {{ $appointment->patientProfile->phone_number ?? 'Not provided' }}</p>
                                                                        
                                                                        <hr>
                                                                        
                                                                        <h6>Appointment Information</h6>
                                                                        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</p>
                                                                        <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                                                                        <p><strong>Status:</strong> {{ ucfirst($appointment->status) }}</p>
                                                                        
                                                                        @if($appointment->notes)
                                                                            <hr>
                                                                            <h6>Notes</h6>
                                                                            <p>{{ $appointment->notes }}</p>
                                                                        @endif
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <div class="mb-3">
                                        <i class="fas fa-history fa-3x text-muted"></i>
                                    </div>
                                    <p>No past appointments found.</p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                            @if($appointments->where('status', 'cancelled')->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Doctor</th>
                                                <th>Patient</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($appointments->where('status', 'cancelled') as $appointment)
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
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $appointment->patientProfile->user->profile_photo_url }}" class="avatar me-2">
                                                            <div>{{ $appointment->patientProfile->user->name }}</div>
                                                        </div>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                                                    <td>
                                                        <span class="badge bg-danger">Cancelled</span>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal{{ $appointment->id }}">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        
                                                        <!-- View Modal -->
                                                        <div class="modal fade" id="viewModal{{ $appointment->id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $appointment->id }}" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="viewModalLabel{{ $appointment->id }}">Appointment Details</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <h6>Doctor Information</h6>
                                                                        <p><strong>Name:</strong> Dr. {{ $appointment->doctorProfile->user->name }}</p>
                                                                        <p><strong>Specialization:</strong> {{ $appointment->doctorProfile->specialization }}</p>
                                                                        <p><strong>Email:</strong> {{ $appointment->doctorProfile->user->email }}</p>
                                                                        
                                                                        <hr>
                                                                        
                                                                        <h6>Patient Information</h6>
                                                                        <p><strong>Name:</strong> {{ $appointment->patientProfile->user->name }}</p>
                                                                        <p><strong>Email:</strong> {{ $appointment->patientProfile->user->email }}</p>
                                                                        <p><strong>Phone:</strong> {{ $appointment->patientProfile->phone_number ?? 'Not provided' }}</p>
                                                                        
                                                                        <hr>
                                                                        
                                                                        <h6>Appointment Information</h6>
                                                                        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</p>
                                                                        <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                                                                        <p><strong>Status:</strong> {{ ucfirst($appointment->status) }}</p>
                                                                        
                                                                        @if($appointment->notes)
                                                                            <hr>
                                                                            <h6>Notes</h6>
                                                                            <p>{{ $appointment->notes }}</p>
                                                                        @endif
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <div class="mb-3">
                                        <i class="fas fa-ban fa-3x text-muted"></i>
                                    </div>
                                    <p>No cancelled appointments found.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
