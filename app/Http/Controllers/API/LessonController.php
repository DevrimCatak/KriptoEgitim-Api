<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\CompletedLesson;
use App\Models\Lesson;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LessonController extends Controller
{
    public function lessons($course_id){
        $user = auth('sanctum')->user();
        $lessons = DB::select('select lessons.id, title, explanation, detail, banner, banner_type, status,
            count(completed_lessons.id) as completed from lessons
            left join completed_lessons on completed_lessons.lesson_id = lessons.id
            and completed_lessons.user_id = ? WHERE lessons.course_id = ? group by lessons.id, title, explanation, detail, banner, banner_type, status' , [$user->id, $course_id]);

        if($lessons != null){
            return response()->json(['status' => true, 'message' => "Dersler başarıyla listelendi",'data'=>$lessons]);
        }else{
            return response()->json(['status' => false, 'message' => "Hata! Daha sonra tekrar deneyiniz."]);
        }
    }

    public function lesson($lesson_id){
        $user = auth('sanctum')->user();
        $lesson = DB::select('select lessons.title, lessons.detail, lessons.banner, lessons.banner_type, lessons.status,
            courses.title as courses_title, count(completed_lessons.id) as completed from lessons
            left join courses on courses.id = lessons.course_id
            left join completed_lessons on completed_lessons.lesson_id = lessons.id
            and completed_lessons.user_id = ? WHERE lessons.id = ? group by lessons.id, courses.id, lessons.title, lessons.detail, lessons.banner, lessons.banner_type,
            lessons.status, courses_title' , [$user->id, $lesson_id]);

        if($lesson != null){
            return response()->json(['status' => true, 'message' => "Ders başarıyla listelendi",'data'=>$lesson]);
        }else{
            return response()->json(['status' => false, 'message' => "Hata! Daha sonra tekrar deneyiniz."]);
        }
    }

    public function completedLesson($lesson_id){
        $user = auth('sanctum')->user();
        $lesson = Lesson::where('id', $lesson_id)->first();

        if ($lesson != null){

            $lesson = CompletedLesson::where('lesson_id', $lesson_id)
                ->where('user_id', $user->id)->first();

            if ($lesson == null){
                $new_lesson = CompletedLesson::create([
                        'user_id' => $user->id,
                        'lesson_id' => $lesson_id,
                    ]
                );

                if ($new_lesson){
                    return response()->json(['status' => true, 'message' => "Kursunuz başarıyla tamamlandı"]);
                } else {
                    return response()->json(['status' => false, 'message' => "Hata! Daha sonra tekrar deneyiniz."]);
                }
            } else {
                return response()->json(['true' => true, 'message' => "Kursunuz başarıyla tamamlandı"]);
            }
        } else {
            return response()->json(['status' => false, 'message' => "Hata! Ders bulunamadı, lütfen daha sonra tekrar deneyiniz."]);
        }

    }

    public function questions($lesson_id){
        $questions = Question::where('lesson_id', $lesson_id)->with('answers')->get();

        if($questions->isNotEmpty()){
            return response()->json(['status' => true, 'message' => "Sorular başarıyla listelendi",'data'=> $questions]);
        }else{
            return response()->json(['status' => false, 'message' => "Hata! Daha sonra tekrar deneyiniz."]);
        }
    }

}
