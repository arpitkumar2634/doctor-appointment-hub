@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mb-4">
            <h2>Book Appointment</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('patient.doctors') }}">Doctors</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('patient.doctor-details', $doctor->id) }}">Dr. {{ $doctor->user->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Book Appointment</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <img src="{{ $doctor->user->profile_photo_url }}" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    <h4>Dr. {{ $doctor->user->name }}</h4>
                    <p class="text-muted">{{ $doctor->specialization }}</p>
                    <div class="d-flex justify-content-center mb-3">
                        <div class="me-2">
                            <i class="fas fa-star text-warning"></i>
                            <span>{{ $doctor->rating ?? '0.0' }}</span>
                        </div>
                        <div class="text-muted">({{ $doctor->reviews_count ?? '0' }} reviews)</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Appointment Details</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('patient.book-appointment.store') }}">
                        @csrf
                        <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                        
                        @if(isset($selectedSlot))
                            <input type="hidden" name="availability_id" value="{{ $selectedSlot->id }}">
                            
                            <div class="alert alert-info">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-info-circle fa-2x"></i>
                                    </div>
                                    <div>
                                        <h6>Selected Time Slot</h6>
                                        <p class="mb-0">
                                            <strong>Date:</strong> {{ \Carbon\Carbon::parse($selectedSlot->date)->format('l, F d, Y') }}<br>
                                            <strong>Time:</strong> {{ \Carbon\Carbon::parse($selectedSlot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($selectedSlot->end_time)->format('h:i A') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mb-3">
                                <label for="availability_id" class="form-label">Select Date and Time</label>
                                <select class="form-select @error('availability_id') is-invalid @enderror" id="availability_id" name="availability_id" required>
                                    <option value="">-- Select a time slot --</option>
                                    @foreach($availabilities->groupBy(function($item) { return \Carbon\Carbon::parse($item->date)->format('Y-m-d'); }) as $date => $slots)
                                        <optgroup label="{{ \Carbon\Carbon::parse($date)->format('l, F d, Y') }}">
                                            @foreach($slots as $slot)
                                                <option value="{{ $slot->id }}">
                                                    {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('availability_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason for Visit</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason" rows="3" required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="symptoms" class="form-label">Symptoms (if any)</label>
                            <textarea class="form-control @error('symptoms') is-invalid @enderror" id="symptoms" name="symptoms" rows="3">{{ old('symptoms') }}</textarea>
                            @error('symptoms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">terms and conditions</a>
                            </label>
                            @error('terms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('patient.doctor-details', $doctor->id) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Book Appointment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Appointment Booking Terms</h6>
                <p>By booking an appointment, you agree to the following terms:</p>
                <ul>
                    <li>You must arrive 15 minutes before your scheduled appointment time.</li>
                    <li>Cancellations must be made at least 24 hours in advance.</li>
                    <li>Late arrivals may result in rescheduling of your appointment.</li>
                    <li>You agree to provide accurate medical information.</li>
                    <li>Payment is due at the time of service unless otherwise arranged.</li>
                </ul>
                
                <h6>Privacy Policy</h6>
                <p>Your personal and medical information will be handled according to our privacy policy:</p>
                <ul>
                    <li>Your information will be kept confidential and secure.</li>
                    <li>We will only use your information for medical purposes.</li>
                    <li>Your data will not be shared with third parties without your consent.</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">I Understand</button>
            </div>
        </div>
    </div>
</div>
@endsection
