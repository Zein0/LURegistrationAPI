<?php

namespace App\Imports;

use App\Models\Course;
use App\Models\User;
use App\Models\UserCourse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentCoursesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // 0 => 'student_id'
        // 1 => 'first_name'
        // 2 => 'last_name'
        // 3 => 'email'
        // 4 => 'course'
        // 5 => 'grade'
        // 6 => 'pass'
        $student = User::where('uni_id', $row['student_id'])->first();
        if (!$student) {
            $student = User::create([
                'uni_id' => $row['student_id'],
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'email' => $row['email'],
                'password' => bcrypt($row['student_id'] % 1000)
            ]);
        }
        $course = Course::where('code', $row['course'])->first();
        if (!$course) {
            return;
        }
        UserCourse::updateOrCreate(['user_id' => $student->id, 'course_id' => $course->id], ['grade' => $row['grade'], 'passed' => $row['pass']]);
    }
    public function startRow(): int
    {
        return 2;
    }
}
