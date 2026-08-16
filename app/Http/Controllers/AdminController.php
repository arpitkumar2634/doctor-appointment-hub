<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DoctorProfile;
use App\Models\PatientProfile;
use App\Models\Appointment;
use App\Models\Role;

class AdminController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function dashboard()
    {
        $doctors = User::whereHas('role', function($query) {
            $query->where('slug', 'doctor');
        })->count();
        
        $patients = User::whereHas('role', function($query) {
            $query->where('slug', 'patient');
        })->count();
        
        $appointments = Appointment::count();
        
        return view('admin.dashboard', compact('doctors', 'patients', 'appointments'));
    }
    
    /**
     * Display all doctors
     */
    public function doctors()
    {
        $doctors = User::whereHas('role', function($query) {
            $query->where('slug', 'doctor');
        })->with('doctorProfile')->paginate(10);
        
        return view('admin.doctors.index', compact('doctors'));
    }
    
    /**
     * Show form to create a new doctor
     */
    public function createDoctor()
    {
        return view('admin.doctors.create');
    }
    
    /**
     * Store a new doctor
     */
    public function storeDoctor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'specialization' => 'required|string|max:255',
            'qualifications' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);
        
        // Get doctor role
        $doctorRole = Role::where('slug', 'doctor')->first();
        
        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $doctorRole->id,
        ]);
        
        // Create doctor profile
        DoctorProfile::create([
            'user_id' => $user->id,
            'specialization' => $request->specialization,
            'qualifications' => $request->qualifications,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
        ]);
        
        return redirect()->route('admin.doctors')->with('success', 'Doctor created successfully');
    }
    
    /**
     * Display all patients
     */
    public function patients()
    {
        $patients = User::whereHas('role', function($query) {
            $query->where('slug', 'patient');
        })->with('patientProfile')->paginate(10);
        
        return view('admin.patients.index', compact('patients'));
    }
    
    /**
     * Display all appointments
     */
    public function appointments()
    {
        $appointments = Appointment::with(['doctorProfile.user', 'patientProfile.user'])->paginate(10);
        
        return view('admin.appointments.index', compact('appointments'));
    }
}
