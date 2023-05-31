<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'date_of_birth',
        'gender',
        'city',
        'hobbies',
        'profile_image',
        'user_id',
    ];
}
