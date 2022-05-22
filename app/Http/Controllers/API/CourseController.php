<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{

    public function courses(){
        $user = auth('sanctum')->user();

        /*$courses = Course::leftjoin('Lessons', 'Lessons.course_id', '=', 'Courses.id')
            ->leftJoin('Completed_lessons', function ($leftJoin) use ($user) {
                $leftJoin->on('Completed_lessons.lesson_id', '=', 'Lessons.id')
                ->on('Completed_lessons.user_id', '=', $user->id);
            })
            ->selectRaw("Courses.title, Courses.detail, Courses.banner, Courses.banner_type, Courses.status,
            count(lessons.id) as lessons_count, count(Completed_lessons.id) as completed_lessons_count" )
            ->groupBy("Courses.id")
            ->get();*/
        $courses = DB::select('select Courses.title, Courses.detail, Courses.banner, Courses.banner_type, Courses.status,
            count(lessons.id) as lessons_count, count(Completed_lessons.id) as completed_lessons_count from `courses`
            left join Lessons on Lessons.course_id = Courses.id
            left join Completed_lessons on Completed_lessons.lesson_id = Lessons.id
            and Completed_lessons.user_id = ? group by Courses.id' , [$user->id]);
        if($courses != null){
            return response()->json(['status' => true, 'message' => "Kurslar başarıyla listelendi",'data'=>$courses]);
        }else{
            return response()->json(['status' => false, 'message' => "Hata! Daha sonra tekrar deneyiniz."]);
        }
    }

}
