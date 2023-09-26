<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRegistration extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_id',
        'user_id',
    ];
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
