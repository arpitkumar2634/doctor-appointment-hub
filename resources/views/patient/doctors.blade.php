@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 d-flex justify-content-between align-items-center mb-4">
            <h2>Find a Doctor</h2>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="doctor-search" placeholder="Search by name or specialty">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-filter"></i></span>
                                <select class="form-select" id="specialty-filter">
                                    <option value="all">All Specialties</option>
                                    @foreach($specialties as $specialty)
                                        <option value="{{ $specialty }}">{{ $specialty }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        @forelse($doctors as $doctor)
            <div class="col-md-4 mb-4">
                <div class="card doctor-card h-100">
                    <img src="{{ $doctor->user->profile_photo_url }}" class="card-img-top doctor-img" alt="Dr. {{ $doctor->user->name }}">
                    <div class="card-body">
                        <h5 class="card-title doctor-name">Dr. {{ $doctor->user->name }}</h5>
                        <p class="card-text doctor-specialty">{{ $doctor->specialization }}</p>
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-2">
                                <i class="fas fa-star text-warning"></i>
                                <span>{{ $doctor->rating ?? '0.0' }}</span>
                            </div>
                            <div class="text-muted">({{ $doctor->reviews_count ?? '0' }} reviews)</div>
                        </div>
                        <p class="card-text">{{ Str::limit($doctor->bio, 100) }}</p>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="{{ route('patient.doctor-details', $doctor->id) }}" class="btn btn-primary w-100">View Profile</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-md-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-user-md fa-3x mb-3"></i>
                    <h4>No doctors found</h4>
                    <p>We couldn't find any doctors matching your criteria. Please try different search terms or check back later.</p>
                </div>
            </div>
        @endforelse
    </div>
    
    <div class="row">
        <div class="col-md-12 d-flex justify-content-center">
            {{ $doctors->links() }}
        </div>
    </div>
</div>
@endsection
