<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSettings extends Model
{
    use HasFactory;

    protected $table = "app_setting";

    protected $fillable = [
        "title",
        "logo_lg",
        "logo_sm",
        "logo_dark_sm",
        "logo_dark_lg",
        "favicon",
        "footer_left",
        "footer_right",
    ];
}
