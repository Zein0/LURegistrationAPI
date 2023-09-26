<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'credits',
        'year',
        'collection_id',
        'parent_id',
    ];

    public function parent()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
    public function grandchildren()
    {
        return $this->children()->with('grandchildren');
    }
    public function students()
    {
        return $this->hasMany(UserCourse::class, 'course_id');
    }
    public function collection()
    {
        return $this->belongsTo(CourseCollection::class, 'collection_id');
    }
    public function requests()
    {
        return $this->hasMany(UserRegistration::class, 'course_id');
    }
}
