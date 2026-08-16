<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorAvailability extends Model
{
    use HasFactory;
    
    protected $table = 'doctor_availability';
    
    protected $fillable = [
        'doctor_profile_id',
        'date',
        'start_time',
        'end_time',
        'is_available'
    ];
    
    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_available' => 'boolean'
    ];
    
    /**
     * Get the doctor profile that owns this availability slot.
     */
    public function doctorProfile()
    {
        return $this->belongsTo(DoctorProfile::class);
    }
    
    /**
     * Get the appointments for this availability slot.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'availability_id');
    }
}
