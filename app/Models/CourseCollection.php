<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCollection extends Model
{
    use HasFactory;
    protected $fillable = [
        'credits',
    ];
    public function courses()
    {
        return $this->hasMany(Course::class, 'collection_id');
    }
}
