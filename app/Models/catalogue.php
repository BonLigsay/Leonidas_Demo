<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class catalogue extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description', // Add other attributes as needed
        'status',
        'priority',
        // Add any other attributes that should be mass assignable
    ];
}
