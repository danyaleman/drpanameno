<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingSections extends Model
{
    use HasFactory;

    protected $table = "landing_sections";
    protected $fillable = [
        "title",
        "is_enable",
    ];
}
