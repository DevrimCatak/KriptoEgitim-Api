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

        /*$courses = Course::leftjoin('lessons', 'lessons.course_id', '=', 'courses.id')
            ->leftJoin('completed_lessons', function ($leftJoin) use ($user) {
                $leftJoin->on('completed_lessons.lesson_id', '=', 'lessons.id')
                ->on('completed_lessons.user_id', '=', $user->id);
            })
            ->selectRaw("courses.title, courses.detail, courses.banner, courses.banner_type, courses.status,
            count(lessons.id) as lessons_count, count(completed_lessons.id) as completed_lessons_count" )
            ->groupBy("courses.id")
            ->get();*/
        $courses = DB::select('select courses.id, courses.title, courses.detail, courses.banner, courses.banner_type, courses.status,
            count(lessons.id) as lessons_count, count(completed_lessons.id) as completed_lessons_count from courses
            left join lessons on lessons.course_id = courses.id
            left join completed_lessons on completed_lessons.lesson_id = lessons.id
            and completed_lessons.user_id = ? group by courses.id, courses.title, courses.detail, courses.banner, courses.banner_type, courses.status, courses.id' , [$user->id]);
        if($courses != null){
            return response()->json(['status' => true, 'message' => "Kurslar başarıyla listelendi",'data'=>$courses]);
        }else{
            return response()->json(['status' => false, 'message' => "Hata! Daha sonra tekrar deneyiniz."]);
        }
    }

}
