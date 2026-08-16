@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mb-4">
            <h2>Doctor Details</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('patient.doctors') }}">Doctors</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dr. {{ $doctor->user->name }}</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <img src="{{ $doctor->user->profile_photo_url }}" class="card-img-top" alt="Dr. {{ $doctor->user->name }}">
                <div class="card-body text-center">
                    <h4 class="card-title">Dr. {{ $doctor->user->name }}</h4>
                    <p class="card-text text-muted">{{ $doctor->specialization }}</p>
                    <div class="d-flex justify-content-center mb-3">
                        <div class="me-2">
                            <i class="fas fa-star text-warning"></i>
                            <span>{{ $doctor->rating ?? '0.0' }}</span>
                        </div>
                        <div class="text-muted">({{ $doctor->reviews_count ?? '0' }} reviews)</div>
                    </div>
                    <a href="{{ route('patient.book-appointment', $doctor->id) }}" class="btn btn-primary btn-lg w-100">Book Appointment</a>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Contact Information</h5>
                </div>
                <div class="card-body">
                    <p><i class="fas fa-envelope me-2 text-primary"></i> {{ $doctor->user->email }}</p>
                    @if($doctor->phone)
                        <p><i class="fas fa-phone me-2 text-primary"></i> {{ $doctor->phone }}</p>
                    @endif
                    @if($doctor->address)
                        <p><i class="fas fa-map-marker-alt me-2 text-primary"></i> {{ $doctor->address }}</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>About Doctor</h5>
                </div>
                <div class="card-body">
                    <p>{{ $doctor->bio }}</p>
                    
                    @if($doctor->education)
                        <h6 class="mt-4">Education</h6>
                        <p>{{ $doctor->education }}</p>
                    @endif
                    
                    @if($doctor->experience)
                        <h6 class="mt-4">Experience</h6>
                        <p>{{ $doctor->experience }}</p>
                    @endif
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Available Time Slots</h5>
                </div>
                <div class="card-body">
                    @if($availabilities->count() > 0)
                        <div class="row">
                            @foreach($availabilities->groupBy(function($item) { return \Carbon\Carbon::parse($item->date)->format('Y-m-d'); }) as $date => $slots)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">{{ \Carbon\Carbon::parse($date)->format('D, M d, Y') }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="list-group list-group-flush">
                                                @foreach($slots as $slot)
                                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                                        <span>{{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}</span>
                                                        <a href="{{ route('patient.book-appointment', ['doctor' => $doctor->id, 'slot' => $slot->id]) }}" class="btn btn-sm btn-outline-primary">Book</a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-calendar-times fa-3x text-muted"></i>
                            </div>
                            <p>No available time slots found for this doctor.</p>
                            <p class="text-muted">Please check back later or contact the clinic directly.</p>
                        </div>
                    @endif
                </div>
            </div>
            
            @if($reviews->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5>Patient Reviews</h5>
                    </div>
                    <div class="card-body">
                        @foreach($reviews as $review)
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <img src="{{ $review->patient->user->profile_photo_url }}" class="avatar me-2">
                                    <div>
                                        <h6 class="mb-0">{{ $review->patient->user->name }}</h6>
                                        <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                                <p>{{ $review->comment }}</p>
                                <hr>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
