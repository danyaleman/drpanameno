<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departments extends Model
{
    use HasFactory;

    protected $table = "departments";

    protected $fillable = [
        'id',
        "name",
        "description",
        "is_deleted"
    ];

    public function doctor() {
        return $this->hasMany(Doctor::class, 'department_id', 'id');
    }
}
