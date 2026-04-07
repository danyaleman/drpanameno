<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teleconsultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'daily_room_url',
        'daily_room_name',
        'status',
        'recording_url',
        'transcription_text',
        'ai_summary',
        'started_at',
        'ended_at',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
