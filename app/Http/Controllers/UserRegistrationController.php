<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Models\UserCourse;
use App\Models\UserRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserRegistrationController extends Controller
{
    public function index()
    {
        $loggedInUser = Auth::guard('user')->user();
        $arr = [
            (object)['year' => 1, 'courses' => []],
            (object)['year' => 2, 'courses' => []],
            (object)['year' => 3, 'courses' => []]
        ];
        $user = $loggedInUser->id;
        $total = 0;
        $firstYearCredits = 0;
        $secondYearCredits = Course::where('year', 2)->whereHas('students', function ($q) use ($user) {
            return $q->where('user_id', $user)->where('passed', 1);
        })->sum('credits');
        $thirdYearCredits = Course::where('year', 3)->whereHas('students', function ($q) use ($user) {
            return $q->where('user_id', $user)->where('passed', 1);
        })->sum('credits');

        $firstYearCourses = UserCourse::where('user_id', $user)->with(['course' => function ($q) {
            return $q->where('year', 1);
        }])->whereHas('course', function ($q) {
            return $q->where('year', 1);
        })->get();
        $grades = 0;
        foreach ($firstYearCourses as $firstYearCourse) {
            $grades += ($firstYearCourse['grade'] * $firstYearCourse['course']['credits']);
            if ($firstYearCourse['passed']) {
                $firstYearCredits += $firstYearCourse['course']['credits'];
            }
        }
        $passedFirstYear = false;
        if (ceil($grades / 60) >= 50) {
            $passedFirstYear = true;
        }

        if ($passedFirstYear) {
            foreach ($firstYearCourses as $firstYearCourse) {
                if (!$firstYearCourse['passed']) {
                    $firstYearCourse->update(['passed' => 1]);
                }
            }
            $firstYearCredits = 60;
        }
        $eligibleFor72Credits = UserCourse::where('user_id', $user)->whereHas('course', function ($q) {
            return $q->where('year', 2);
        })->count();


        if (($firstYearCredits + $secondYearCredits >= 48) && $eligibleFor72Credits) {
            $total = 72;
        } else {
            if ($passedFirstYear || $firstYearCredits >= 42) {
                $total = 60;
            } else {
                $total = 60 - $firstYearCredits;
            }
        }
        $courses = Course::with(['students' => function ($q) use ($user) {
            return $q->where('user_id', $user);
        }, 'collection'])->get();
        $requested = array_column(UserRegistration::where('user_id', $user)->get()->toArray(), 'course_id');
        $obligatory2nd = (120 - $secondYearCredits - $firstYearCredits) <= $total;
        foreach ($courses as $course) {
            $course['collection_id'] = null;
            $course['total'] = 0;
            $course['disabled'] = false;
            $course['obligatory'] = false;
            $course['passed'] = false;
            $course['selected'] = false;
            if (sizeof($course['students'])) {
                $course['passed'] = $course['students'][0]['passed'];
            }
            if ($course['year'] == 1 && $passedFirstYear) {
                $course['passed'] = true;
            }
            if ($course['passed'] || ($course['year'] == 1 && $passedFirstYear) || ($course['year'] == 2 && $firstYearCredits < 42) || ($course['year'] == 3 && (($firstYearCredits + $secondYearCredits <= 48) || !$eligibleFor72Credits))) {
                $course['disabled'] = true;
            } else {
                if ($course['parent_id']) {
                    $parent = UserCourse::where('user_id', $user)->where('course_id', $course['parent_id'])->first();
                    if (!$parent || !$parent->passed) {
                        if ($passedFirstYear) {
                            $parentCourse = Course::whereId($course['parent_id'])->where('year', 1)->first();
                            if ($parentCourse) {
                                $course['disabled'] = false;;
                            } else {
                                $course['disabled'] = true;
                            }
                        } else {
                            $course['disabled'] = true;
                        }
                    }
                }
            }
            if (!$course['disabled'] && !$course['passed'] && (($course['year'] == 1 && $firstYearCredits < 60) || ($course['year'] == 2 && $eligibleFor72Credits && $obligatory2nd))) {
                if (!$course['collection']) {
                    $course['obligatory'] = true;
                    $total -= $course['credits'];
                }
            }
            if (in_array($course['id'], $requested)) {
                $course['selected'] = true;
                if (!$course['obligatory']) {
                    $total -= $course['credits'];
                }
            }
            if ($course['collection']) {
                $course['collection_id'] = $course['collection']->id;
                $course['total'] = $course['collection']->credits;
            }
            unset($course['students']);
            unset($course['collection']);
            $arr[$course['year'] - 1]->courses[] = $course;
        }
        $student = User::whereId($user)->first();
        return response(['success' => true, 'data' => $arr, 'total' => $total, 'user' => $student]);
    }

    public function request()
    {
        $loggedInUser = Auth::guard('user')->user();;
        $id = $loggedInUser->id;
        $courses = request('courses');
        UserRegistration::where('user_id', $id)->whereNotIn('course_id', $courses)->delete();
        foreach ($courses as $course) {
            UserRegistration::where('user_id', $id)->firstOrCreate(['course_id' => $course], ['user_id' => $id]);
        }
        return response(['success' => true,]);
    }
    public function requests()
    {
        $per_page = request('per_page', 25);
        $requests = User::whereHas('requests')->paginate($per_page);
        return response(['success' => true, 'data' => $requests]);
    }
    public function check($id)
    {
        $arr = [
            (object)['year' => 1, 'courses' => []],
            (object)['year' => 2, 'courses' => []],
            (object)['year' => 3, 'courses' => []]
        ];
        $user = $id;
        $total = 0;
        $firstYearCredits = 0;
        $secondYearCredits = Course::where('year', 2)->whereHas('students', function ($q) use ($user) {
            return $q->where('user_id', $user)->where('passed', 1);
        })->sum('credits');
        $thirdYearCredits = Course::where('year', 3)->whereHas('students', function ($q) use ($user) {
            return $q->where('user_id', $user)->where('passed', 1);
        })->sum('credits');

        $firstYearCourses = UserCourse::where('user_id', $user)->with(['course' => function ($q) {
            return $q->where('year', 1);
        }])->whereHas('course', function ($q) {
            return $q->where('year', 1);
        })->get();
        $grades = 0;
        foreach ($firstYearCourses as $firstYearCourse) {
            $grades += ($firstYearCourse['grade'] * $firstYearCourse['course']['credits']);
            if ($firstYearCourse['passed']) {
                $firstYearCredits += $firstYearCourse['course']['credits'];
            }
        }
        $passedFirstYear = false;
        if (ceil($grades / 60) >= 50) {
            $passedFirstYear = true;
        }

        if ($passedFirstYear) {
            foreach ($firstYearCourses as $firstYearCourse) {
                if (!$firstYearCourse['passed']) {
                    $firstYearCourse->update(['passed' => 1]);
                }
            }
            $firstYearCredits = 60;
        }
        $eligibleFor72Credits = UserCourse::where('user_id', $user)->whereHas('course', function ($q) {
            return $q->where('year', 2);
        })->count();


        if (($firstYearCredits + $secondYearCredits >= 48) && $eligibleFor72Credits) {
            $total = 72;
        } else {
            if ($passedFirstYear || $firstYearCredits >= 42) {
                $total = 60;
            } else {
                $total = 60 - $firstYearCredits;
            }
        }
        $courses = Course::with(['students' => function ($q) use ($user) {
            return $q->where('user_id', $user);
        }, 'collection'])->get();
        $requested = array_column(UserRegistration::where('user_id', $user)->get()->toArray(), 'course_id');
        $obligatory2nd = (120 - $secondYearCredits - $firstYearCredits) <= $total;
        foreach ($courses as $course) {
            $course['collection_id'] = null;
            $course['total'] = 0;
            $course['disabled'] = false;
            $course['obligatory'] = false;
            $course['passed'] = false;
            $course['selected'] = false;
            if (sizeof($course['students'])) {
                $course['passed'] = $course['students'][0]['passed'];
            }
            if ($course['year'] == 1 && $passedFirstYear) {
                $course['passed'] = true;
            }
            if ($course['passed'] || ($course['year'] == 1 && $passedFirstYear) || ($course['year'] == 2 && $firstYearCredits < 42) || ($course['year'] == 3 && (($firstYearCredits + $secondYearCredits <= 48) || !$eligibleFor72Credits))) {
                $course['disabled'] = true;
            } else {
                if ($course['parent_id']) {
                    $parent = UserCourse::where('user_id', $user)->where('course_id', $course['parent_id'])->first();
                    if (!$parent || !$parent->passed) {
                        if ($passedFirstYear) {
                            $parentCourse = Course::whereId($course['parent_id'])->where('year', 1)->first();
                            if ($parentCourse) {
                                $course['disabled'] = false;;
                            } else {
                                $course['disabled'] = true;
                            }
                        } else {
                            $course['disabled'] = true;
                        }
                    }
                }
            }
            if (!$course['disabled'] && !$course['passed'] && (($course['year'] == 1 && $firstYearCredits < 60) || ($course['year'] == 2 && $eligibleFor72Credits && $obligatory2nd))) {
                if (!$course['collection']) {
                    $course['obligatory'] = true;
                    $total -= $course['credits'];
                }
            }
            if (in_array($course['id'], $requested)) {
                $course['selected'] = true;
                if (!$course['obligatory']) {
                    $total -= $course['credits'];
                }
            }
            if ($course['collection']) {
                $course['collection_id'] = $course['collection']->id;
                $course['total'] = $course['collection']->credits;
            }
            unset($course['students']);
            unset($course['collection']);
            $arr[$course['year'] - 1]->courses[] = $course;
        }
        $student = User::whereId($user)->first();
        return response(['success' => true, 'data' => $arr, 'total' => $total, 'user' => $student]);
    }
    public function approve($id)
    {
        $courses = request('courses');
        UserRegistration::where('user_id', $id)->whereNotIn('course_id', $courses)->delete();
        foreach ($courses as $course) {
            UserRegistration::where('user_id', $id)->firstOrCreate(['course_id' => $course], ['user_id' => $id]);
        }
        return response(['success' => true,]);
    }
}
