<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoctorProfile;
use App\Models\DoctorAvailability;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    /**
     * Display doctor dashboard
     */
    public function dashboard()
    {
        $doctor = Auth::user();
        $doctorProfile = $doctor->doctorProfile;
        
        $totalAppointments = Appointment::where('doctor_profile_id', $doctorProfile->id)->count();
        $pendingAppointments = Appointment::where('doctor_profile_id', $doctorProfile->id)
            ->where('status', 'pending')
            ->count();
        $todayAppointments = Appointment::where('doctor_profile_id', $doctorProfile->id)
            ->whereDate('appointment_date', today())
            ->count();
        
        return view('doctor.dashboard', compact('totalAppointments', 'pendingAppointments', 'todayAppointments'));
    }
    
    /**
     * Display doctor profile
     */
    public function profile() 
    {
        $doctor = Auth::user();
        $doctorProfile = $doctor->doctorProfile;
        
        return view('doctor.profile', compact('doctor', 'doctorProfile'));
    }
    
    /**
     * Update doctor profile
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'qualifications' => 'nullable|string',
            'bio' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);
        
        $user = Auth::user();
        $user->name = $request->name;
        $user->save();
        
        $doctorProfile = $user->doctorProfile;
        $doctorProfile->specialization = $request->specialization;
        $doctorProfile->qualifications = $request->qualifications;
        $doctorProfile->bio = $request->bio;
        $doctorProfile->phone_number = $request->phone_number;
        $doctorProfile->address = $request->address;
        $doctorProfile->save();
        
        return redirect()->route('doctor.profile')->with('success', 'Profile updated successfully');
    }
    
    /**
     * Display doctor availability
     */
    public function availability()
    {
        $doctor = Auth::user();
        $doctorProfile = $doctor->doctorProfile;
        $availabilitySlots = DoctorAvailability::where('doctor_profile_id', $doctorProfile->id)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
        
        return view('doctor.availability', compact('availabilitySlots'));
    }
    
    /**
     * Show form to add new availability slot
     */
    public function createAvailability()
    {
        return view('doctor.availability.create');
    }
    
    /**
     * Store new availability slot
     */
    public function storeAvailability(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);
        
        $doctor = Auth::user();
        $doctorProfile = $doctor->doctorProfile;
        
        DoctorAvailability::create([
            'doctor_profile_id' => $doctorProfile->id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_available' => true,
        ]);
        
        return redirect()->route('doctor.availability')->with('success', 'Availability slot added successfully');
    }
    
    /**
     * Display doctor appointments
     */
    public function appointments()
    {
        $doctor = Auth::user();
        $doctorProfile = $doctor->doctorProfile;
        
        $appointments = Appointment::where('doctor_profile_id', $doctorProfile->id)
            ->with('patientProfile.user')
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->paginate(10);
        
        return view('doctor.appointments', compact('appointments'));
    }
    
    /**
     * Update appointment status
     */
    public function updateAppointmentStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:confirmed,cancelled,completed',
        ]);
        
        $doctor = Auth::user();
        $doctorProfile = $doctor->doctorProfile;
        
        // Ensure the appointment belongs to this doctor
        if ($appointment->doctor_profile_id != $doctorProfile->id) {
            return redirect()->route('doctor.appointments')->with('error', 'Unauthorized action');
        }
        
        $appointment->status = $request->status;
        $appointment->save();
        
        return redirect()->route('doctor.appointments')->with('success', 'Appointment status updated successfully');
    }
}
