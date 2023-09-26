<?php

use App\Models\Course;
use App\Models\CourseCollection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $collections = [
            3,
            3,
            5,
            9,
            4,
            6
        ];
        $courses = [
            ['code' => 'M1100', 'credits' => 6, 'pre' => null, 'year' => 1, 'collection' => null],
            ['code' => 'M1101', 'credits' => 6, 'pre' => null, 'year' => 1, 'collection' => null],
            ['code' => 'M1102', 'credits' => 6, 'pre' => null, 'year' => 1, 'collection' => null],
            ['code' => 'M1103', 'credits' => 3, 'pre' => null, 'year' => 1, 'collection' => null],
            ['code' => 'M1104', 'credits' => 6, 'pre' => null, 'year' => 1, 'collection' => null],
            ['code' => 'M1105', 'credits' => 6, 'pre' => null, 'year' => 1, 'collection' => null],
            ['code' => 'M1106', 'credits' => 3, 'pre' => null, 'year' => 1, 'collection' => null],
            ['code' => 'P1100', 'credits' => 6, 'pre' => null, 'year' => 1, 'collection' => null],
            ['code' => 'P1101', 'credits' => 6, 'pre' => null, 'year' => 1, 'collection' => null],
            ['code' => 'S1100', 'credits' => 3, 'pre' => null, 'year' => 1, 'collection' => null],
            ['code' => 'I1100', 'credits' => 3, 'pre' => null, 'year' => 1, 'collection' => null],
            ['code' => 'I1101', 'credits' => 6, 'pre' => null, 'year' => 1, 'collection' => null],


            ['code' => 'M2250', 'credits' => 3, 'pre' => 'M1102', 'year' => 2, 'collection' => null],
            ['code' => 'S2250', 'credits' => 4, 'pre' => null, 'year' => 2, 'collection' => null],
            ['code' => 'I2201', 'credits' => 4, 'pre' => null, 'year' => 2, 'collection' => null],
            ['code' => 'I2202', 'credits' => 4, 'pre' => null, 'year' => 2, 'collection' => null],
            ['code' => 'I2203', 'credits' => 4, 'pre' => null, 'year' => 2, 'collection' => null],
            ['code' => 'I2204', 'credits' => 5, 'pre' => 'I1101', 'year' => 2, 'collection' => null],
            ['code' => 'I2205', 'credits' => 3, 'pre' => null, 'year' => 2, 'collection' => null],

            ['code' => 'M2251', 'credits' => 3, 'pre' => null, 'year' => 2, 'collection' => 1],
            ['code' => 'I2231', 'credits' => 3, 'pre' => null, 'year' => 2, 'collection' => 1],
            ['code' => 'I2232', 'credits' => 3, 'pre' => null, 'year' => 2, 'collection' => 1],


            ['code' => 'I2206', 'credits' => 5, 'pre' => 'I1101', 'year' => 2, 'collection' => null],
            ['code' => 'I2207', 'credits' => 4, 'pre' => null, 'year' => 2, 'collection' => null],
            ['code' => 'I2208', 'credits' => 4, 'pre' => null, 'year' => 2, 'collection' => null],
            ['code' => 'I2209', 'credits' => 4, 'pre' => null, 'year' => 2, 'collection' => null],
            ['code' => 'I2210', 'credits' => 5, 'pre' => null, 'year' => 2, 'collection' => null],
            ['code' => 'I2211', 'credits' => 5, 'pre' => 'I1101', 'year' => 2, 'collection' => null],

            ['code' => 'I2233', 'credits' => 3, 'pre' => null, 'year' => 2, 'collection' => 2],
            ['code' => 'I2234', 'credits' => 3, 'pre' => null, 'year' => 2, 'collection' => 2],


            ['code' => 'DRH300', 'credits' => 3, 'pre' => null, 'year' => 3, 'collection' => null],
            ['code' => 'I3301', 'credits' => 4, 'pre' => null, 'year' => 3, 'collection' => null],
            ['code' => 'I3302', 'credits' => 4, 'pre' => null, 'year' => 3, 'collection' => null],
            ['code' => 'I3303', 'credits' => 4, 'pre' => 'I2203', 'year' => 3, 'collection' => null],
            ['code' => 'I3304', 'credits' => 4, 'pre' => null, 'year' => 3, 'collection' => null],
            ['code' => 'I3305', 'credits' => 3, 'pre' => null, 'year' => 3, 'collection' => null],
            ['code' => 'I3306', 'credits' => 3, 'pre' => 'I2210', 'year' => 3, 'collection' => null],

            ['code' => 'I3350', 'credits' => 5, 'pre' => null, 'year' => 3, 'collection' => 3],
            ['code' => 'I3351', 'credits' => 5, 'pre' => 'I2203', 'year' => 3, 'collection' => 3],

            ['code' => 'L3300', 'credits' => 3, 'pre' => null, 'year' => 3, 'collection' => null],
            ['code' => 'I3307', 'credits' => 4, 'pre' => null, 'year' => 3, 'collection' => null],
            ['code' => 'I3308', 'credits' => 4, 'pre' => null, 'year' => 3, 'collection' => null],

            ['code' => 'I3330', 'credits' => 3, 'pre' => null, 'year' => 3, 'collection' => 4],
            ['code' => 'I3331', 'credits' => 3, 'pre' => null, 'year' => 3, 'collection' => 4],
            ['code' => 'I3332', 'credits' => 3, 'pre' => null, 'year' => 3, 'collection' => 4],
            ['code' => 'I3333', 'credits' => 3, 'pre' => null, 'year' => 3, 'collection' => 4],

            ['code' => 'I3340', 'credits' => 4, 'pre' => null, 'year' => 3, 'collection' => 5],
            ['code' => 'I3341', 'credits' => 4, 'pre' => null, 'year' => 3, 'collection' => 5],

            ['code' => 'I3342', 'credits' => 3, 'pre' => null, 'year' => 3, 'collection' => 6],
            ['code' => 'I3343', 'credits' => 3, 'pre' => null, 'year' => 3, 'collection' => 6],
            ['code' => 'I3344', 'credits' => 6, 'pre' => null, 'year' => 3, 'collection' => 6],
        ];
        foreach ($collections as $collection) {
            CourseCollection::create(['credits' => $collection]);
        }
        foreach ($courses as $course) {
            $parent = null;
            if ($course['pre']) {
                $parent = Course::where('code', $course['pre'])->first()->id;
            }
            Course::create([
                'name' => $course['code'],
                'code' => $course['code'],
                'credits' => $course['credits'],
                'year' => $course['year'],
                'collection_id' => $course['collection'],
                'parent_id' =>  $parent,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
