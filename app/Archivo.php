<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    protected $table = 'archivos';

    protected $fillable = [
        'prescription_id',
        'url_file',
        'observaciones'
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }
}
