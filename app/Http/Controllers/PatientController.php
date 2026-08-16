<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PatientProfile;
use App\Models\DoctorProfile;
use App\Models\DoctorAvailability;
use App\Models\Appointment;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    /**
     * Display patient dashboard
     */
    public function dashboard()
    {
        $patient = Auth::user();
        $patientProfile = $patient->patientProfile;
        
        $upcomingAppointments = Appointment::where('patient_profile_id', $patientProfile->id)
            ->whereDate('appointment_date', '>=', today())
            ->where('status', '!=', 'cancelled')
            ->with('doctorProfile.user')
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->take(5)
            ->get();
        
        $totalAppointments = Appointment::where('patient_profile_id', $patientProfile->id)->count();
        
        return view('patient.dashboard', compact('upcomingAppointments', 'totalAppointments'));
    }
    
    /**
     * Display patient profile
     */
    public function profile()
    {
        $patient = Auth::user();
        $patientProfile = $patient->patientProfile;
        
        return view('patient.profile', compact('patient', 'patientProfile'));
    }
    
    /**
     * Update patient profile
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'medical_history' => 'nullable|string',
        ]);
        
        $user = Auth::user();
        $user->name = $request->name;
        $user->save();
        
        $patientProfile = $user->patientProfile;
        $patientProfile->date_of_birth = $request->date_of_birth;
        $patientProfile->gender = $request->gender;
        $patientProfile->phone_number = $request->phone_number;
        $patientProfile->address = $request->address;
        $patientProfile->medical_history = $request->medical_history;
        $patientProfile->save();
        
        return redirect()->route('patient.profile')->with('success', 'Profile updated successfully');
    }
    
    /**
     * Display list of doctors
     */
    public function doctors()
    {
        $doctors = User::whereHas('role', function($query) {
            $query->where('slug', 'doctor');
        })->with('doctorProfile')->paginate(10);
        
        return view('patient.doctors', compact('doctors'));
    }
    
    /**
     * Display doctor details and availability
     */
    public function doctorDetails(DoctorProfile $doctorProfile)
    {
        $availabilitySlots = DoctorAvailability::where('doctor_profile_id', $doctorProfile->id)
            ->where('date', '>=', today())
            ->where('is_available', true)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
        
        return view('patient.doctor-details', compact('doctorProfile', 'availabilitySlots'));
    }
    
    /**
     * Show form to book an appointment
     */
    public function bookAppointment(DoctorAvailability $availability)
    {
        $doctorProfile = $availability->doctorProfile;
        
        return view('patient.book-appointment', compact('availability', 'doctorProfile'));
    }
    
    /**
     * Store new appointment
     */
    public function storeAppointment(Request $request, DoctorAvailability $availability)
    {
        $request->validate([
            'notes' => 'nullable|string',
        ]);
        
        $patient = Auth::user();
        $patientProfile = $patient->patientProfile;
        $doctorProfile = $availability->doctorProfile;
        
        // Check if slot is still available
        if (!$availability->is_available) {
            return redirect()->route('patient.doctor-details', $doctorProfile)->with('error', 'This slot is no longer available');
        }
        
        // Create appointment
        $appointment = Appointment::create([
            'doctor_profile_id' => $doctorProfile->id,
            'patient_profile_id' => $patientProfile->id,
            'availability_id' => $availability->id,
            'appointment_date' => $availability->date,
            'appointment_time' => $availability->start_time,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);
        
        // Mark slot as unavailable
        $availability->is_available = false;
        $availability->save();
        
        return redirect()->route('patient.appointments')->with('success', 'Appointment booked successfully');
    }
    
    /**
     * Display patient appointments
     */
    public function appointments()
    {
        $patient = Auth::user();
        $patientProfile = $patient->patientProfile;
        
        $appointments = Appointment::where('patient_profile_id', $patientProfile->id)
            ->with('doctorProfile.user')
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->paginate(10);
        
        return view('patient.appointments', compact('appointments'));
    }
    
    /**
     * Cancel appointment
     */
    public function cancelAppointment(Appointment $appointment)
    {
        $patient = Auth::user();
        $patientProfile = $patient->patientProfile;
        
        // Ensure the appointment belongs to this patient
        if ($appointment->patient_profile_id != $patientProfile->id) {
            return redirect()->route('patient.appointments')->with('error', 'Unauthorized action');
        }
        
        // Only allow cancellation if appointment is pending or confirmed
        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return redirect()->route('patient.appointments')->with('error', 'Cannot cancel this appointment');
        }
        
        $appointment->status = 'cancelled';
        $appointment->save();
        
        // Make the slot available again
        $availability = $appointment->availabilitySlot;
        $availability->is_available = true;
        $availability->save();
        
        return redirect()->route('patient.appointments')->with('success', 'Appointment cancelled successfully');
    }
}
