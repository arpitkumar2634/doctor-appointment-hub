<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'doctor_profile_id',
        'patient_profile_id',
        'availability_id',
        'appointment_date',
        'appointment_time',
        'status',
        'notes'
    ];
    
    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime',
    ];
    
    /**
     * Get the doctor profile associated with this appointment.
     */
    public function doctorProfile()
    {
        return $this->belongsTo(DoctorProfile::class);
    }
    
    /**
     * Get the patient profile associated with this appointment.
     */
    public function patientProfile()
    {
        return $this->belongsTo(PatientProfile::class);
    }
    
    /**
     * Get the availability slot associated with this appointment.
     */
    public function availabilitySlot()
    {
        return $this->belongsTo(DoctorAvailability::class, 'availability_id');
    }
}
