<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Admin Routes
Route::middleware(['auth:sanctum', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Doctor Management
    Route::get('/doctors', [AdminController::class, 'doctors'])->name('doctors');
    Route::get('/doctors/create', [AdminController::class, 'createDoctor'])->name('doctors.create');
    Route::post('/doctors', [AdminController::class, 'storeDoctor'])->name('doctors.store');
    Route::get('/doctors/{doctor}/edit', [AdminController::class, 'editDoctor'])->name('doctors.edit');
    Route::put('/doctors/{doctor}', [AdminController::class, 'updateDoctor'])->name('doctors.update');
    Route::delete('/doctors/{doctor}', [AdminController::class, 'destroyDoctor'])->name('doctors.destroy');
    
    // Patient Management
    Route::get('/patients', [AdminController::class, 'patients'])->name('patients');
    Route::get('/patients/{patient}', [AdminController::class, 'viewPatient'])->name('patients.view');
    
    // Appointment Management
    Route::get('/appointments', [AdminController::class, 'appointments'])->name('appointments');
});

// Doctor Routes
Route::middleware(['auth:sanctum', 'verified', 'role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', [DoctorController::class, 'dashboard'])->name('dashboard');
    
    // Profile Management
    Route::get('/profile', [DoctorController::class, 'profile'])->name('profile');
    Route::put('/profile', [DoctorController::class, 'updateProfile'])->name('profile.update');
    
    // Availability Management
    Route::get('/availability', [DoctorController::class, 'availability'])->name('availability');
    Route::get('/availability/create', [DoctorController::class, 'createAvailability'])->name('availability.create');
    Route::post('/availability', [DoctorController::class, 'storeAvailability'])->name('availability.store');
    Route::delete('/availability/{availability}', [DoctorController::class, 'destroyAvailability'])->name('availability.destroy');
    
    // Appointment Management
    Route::get('/appointments', [DoctorController::class, 'appointments'])->name('appointments');
    Route::put('/appointments/{appointment}/status', [DoctorController::class, 'updateAppointmentStatus'])->name('appointments.update-status');
});

// Patient Routes
Route::middleware(['auth:sanctum', 'verified', 'role:patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');
    
    // Profile Management
    Route::get('/profile', [PatientController::class, 'profile'])->name('profile');
    Route::put('/profile', [PatientController::class, 'updateProfile'])->name('profile.update');
    
    // Doctor Browsing
    Route::get('/doctors', [PatientController::class, 'doctors'])->name('doctors');
    Route::get('/doctors/{doctorProfile}', [PatientController::class, 'doctorDetails'])->name('doctor-details');
    
    // Appointment Booking
    Route::get('/book-appointment/{availability}', [PatientController::class, 'bookAppointment'])->name('book-appointment');
    Route::post('/book-appointment/{availability}', [PatientController::class, 'storeAppointment'])->name('book-appointment.store');
    
    // Appointment Management
    Route::get('/appointments', [PatientController::class, 'appointments'])->name('appointments');
    Route::put('/appointments/{appointment}/cancel', [PatientController::class, 'cancelAppointment'])->name('appointments.cancel');
});