<?php

namespace App\Http\Controllers;

use App\Imports\StudentCoursesImport;
use App\Models\UserCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class UserCourseController extends Controller
{

    public function post_results(Request $request)
    {
        $file = $request->file('excel_file');
        $fileName = 'student_courses_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('imported-excel', $fileName, 'public');
        Excel::import(new StudentCoursesImport, public_path('storage/imported-excel/' . $fileName));
        Storage::disk('public')->delete('imported-excel/' . $fileName);
        return response(['success' => true]);
    }
}
