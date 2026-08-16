@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 d-flex justify-content-between align-items-center mb-4">
            <h2>My Appointments</h2>
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
                            <button class="nav-link" id="past-tab" data-bs-toggle="tab" data-bs-target="#past" type="button" role="tab" aria-controls="past" aria-selected="false">Past</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled" type="button" role="tab" aria-controls="cancelled" aria-selected="false">Cancelled</button>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="appointmentTabsContent">
                        <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
                            @if($upcomingAppointments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Doctor</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Status</th>
                                                <th>Actions</th>
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
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal{{ $appointment->id }}">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        
                                                        <form method="POST" action="{{ route('patient.appointments.cancel', $appointment->id) }}" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="btn btn-sm btn-danger cancel-appointment">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                        
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
                                                                        
                                                                        <h6>Appointment Information</h6>
                                                                        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</p>
                                                                        <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                                                                        <p><strong>Status:</strong> {{ ucfirst($appointment->status) }}</p>
                                                                        
                                                                        @if($appointment->reason)
                                                                            <p><strong>Reason for Visit:</strong> {{ $appointment->reason }}</p>
                                                                        @endif
                                                                        
                                                                        @if($appointment->symptoms)
                                                                            <p><strong>Symptoms:</strong> {{ $appointment->symptoms }}</p>
                                                                        @endif
                                                                        
                                                                        @if($appointment->notes)
                                                                            <p><strong>Additional Notes:</strong> {{ $appointment->notes }}</p>
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
                                        <i class="fas fa-calendar-times fa-3x text-muted"></i>
                                    </div>
                                    <p>You don't have any upcoming appointments.</p>
                                    <a href="{{ route('patient.doctors') }}" class="btn btn-primary">Find a Doctor</a>
                                </div>
                            @endif
                        </div>
                        
                        <div class="tab-pane fade" id="past" role="tabpanel" aria-labelledby="past-tab">
                            @if($pastAppointments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Doctor</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pastAppointments as $appointment)
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
                                                        
                                                        @if(!$appointment->has_review && $appointment->status == 'completed')
                                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $appointment->id }}">
                                                                <i class="fas fa-star"></i>
                                                            </button>
                                                            
                                                            <!-- Review Modal -->
                                                            <div class="modal fade" id="reviewModal{{ $appointment->id }}" tabindex="-1" aria-labelledby="reviewModalLabel{{ $appointment->id }}" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="reviewModalLabel{{ $appointment->id }}">Review Dr. {{ $appointment->doctorProfile->user->name }}</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <form method="POST" action="{{ route('patient.reviews.store') }}">
                                                                            @csrf
                                                                            <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                                                                            <input type="hidden" name="doctor_id" value="{{ $appointment->doctorProfile->id }}">
                                                                            
                                                                            <div class="modal-body">
                                                                                <div class="mb-3">
                                                                                    <label class="form-label">Rating</label>
                                                                                    <div class="rating">
                                                                                        <div class="form-check form-check-inline">
                                                                                            <input class="form-check-input" type="radio" name="rating" id="rating1{{ $appointment->id }}" value="1" required>
                                                                                            <label class="form-check-label" for="rating1{{ $appointment->id }}">1</label>
                                                                                        </div>
                                                                                        <div class="form-check form-check-inline">
                                                                                            <input class="form-check-input" type="radio" name="rating" id="rating2{{ $appointment->id }}" value="2">
                                                                                            <label class="form-check-label" for="rating2{{ $appointment->id }}">2</label>
                                                                                        </div>
                                                                                        <div class="form-check form-check-inline">
                                                                                            <input class="form-check-input" type="radio" name="rating" id="rating3{{ $appointment->id }}" value="3">
                                                                                            <label class="form-check-label" for="rating3{{ $appointment->id }}">3</label>
                                                                                        </div>
                                                                                        <div class="form-check form-check-inline">
                                                                                            <input class="form-check-input" type="radio" name="rating" id="rating4{{ $appointment->id }}" value="4">
                                                                                            <label class="form-check-label" for="rating4{{ $appointment->id }}">4</label>
                                                                                        </div>
                                                                                        <div class="form-check form-check-inline">
                                                                                            <input class="form-check-input" type="radio" name="rating" id="rating5{{ $appointment->id }}" value="5">
                                                                                            <label class="form-check-label" for="rating5{{ $appointment->id }}">5</label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <div class="mb-3">
                                                                                    <label for="comment{{ $appointment->id }}" class="form-label">Comment</label>
                                                                                    <textarea class="form-control" id="comment{{ $appointment->id }}" name="comment" rows="3" required></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                                <button type="submit" class="btn btn-primary">Submit Review</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        
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
                                                                        
                                                                        <h6>Appointment Information</h6>
                                                                        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</p>
                                                                        <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                                                                        <p><strong>Status:</strong> {{ ucfirst($appointment->status) }}</p>
                                                                        
                                                                        @if($appointment->reason)
                                                                            <p><strong>Reason for Visit:</strong> {{ $appointment->reason }}</p>
                                                                        @endif
                                                                        
                                                                        @if($appointment->symptoms)
                                                                            <p><strong>Symptoms:</strong> {{ $appointment->symptoms }}</p>
                                                                        @endif
                                                                        
                                                                        @if($appointment->notes)
                                                                            <p><strong>Additional Notes:</strong> {{ $appointment->notes }}</p>
                                                                        @endif
                                                                        
                                                                        @if($appointment->doctor_notes)
                                                                            <hr>
                                                                            <h6>Doctor's Notes</h6>
                                                                            <p>{{ $appointment->doctor_notes }}</p>
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
                                    <p>You don't have any past appointments.</p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                            @if($cancelledAppointments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Doctor</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($cancelledAppointments as $appointment)
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
                                                                        
                                                                        <h6>Appointment Information</h6>
                                                                        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</p>
                                                                        <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                                                                        <p><strong>Status:</strong> {{ ucfirst($appointment->status) }}</p>
                                                                        <p><strong>Cancelled On:</strong> {{ $appointment->updated_at->format('M d, Y h:i A') }}</p>
                                                                        
                                                                        @if($appointment->reason)
                                                                            <p><strong>Reason for Visit:</strong> {{ $appointment->reason }}</p>
                                                                        @endif
                                                                        
                                                                        @if($appointment->cancellation_reason)
                                                                            <p><strong>Cancellation Reason:</strong> {{ $appointment->cancellation_reason }}</p>
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
                                    <p>You don't have any cancelled appointments.</p>
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
