<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'person';
    protected $fillable = [
        'cui',
        'name',
        'lastname',
        'gender_id',
        'phone',
        'dob',
        'address',
    ];

}
