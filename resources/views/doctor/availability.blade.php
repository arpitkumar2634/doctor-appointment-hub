@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 d-flex justify-content-between align-items-center mb-4">
            <h2>My Availability</h2>
            <a href="{{ route('doctor.availability.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i> Add New Slot
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @if($availabilitySlots->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($availabilitySlots as $slot)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($slot->date)->format('M d, Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}</td>
                                            <td>
                                                @if($slot->is_available)
                                                    <span class="badge bg-success">Available</span>
                                                @else
                                                    <span class="badge bg-danger">Booked</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('doctor.availability.edit', $slot->id) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $slot->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                                
                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteModal{{ $slot->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $slot->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel{{ $slot->id }}">Confirm Delete</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to delete this availability slot? This action cannot be undone.
                                                                @if(!$slot->is_available)
                                                                    <div class="alert alert-warning mt-3">
                                                                        <i class="fas fa-exclamation-triangle me-2"></i> This slot is currently booked. Deleting it will also cancel any associated appointments.
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <form action="{{ route('doctor.availability.destroy', $slot->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                                </form>
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
                            <p>You haven't set any availability slots yet.</p>
                            <a href="{{ route('doctor.availability.create') }}" class="btn btn-primary">Add Your First Slot</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
