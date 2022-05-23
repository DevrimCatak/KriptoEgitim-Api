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
        $lessons = DB::select('select title, detail, banner, banner_type, status,
            count(Completed_lessons.id) as completed from Lessons
            left join Completed_lessons on Completed_lessons.lesson_id = Lessons.id
            and Completed_lessons.user_id = ? WHERE lessons.course_id = ? group by lessons.id' , [$user->id, $course_id]);

        if($lessons != null){
            return response()->json(['status' => true, 'message' => "Dersler başarıyla listelendi",'data'=>$lessons]);
        }else{
            return response()->json(['status' => false, 'message' => "Hata! Daha sonra tekrar deneyiniz."]);
        }
    }

    public function lesson($lesson_id){
        $user = auth('sanctum')->user();
        $lesson = DB::select('select Lessons.title, Lessons.detail, Lessons.banner, Lessons.banner_type, Lessons.status,
            Courses.title as courses_itle, count(Completed_lessons.id) as completed from Lessons
            left join Courses on Courses.id = Lessons.course_id
            left join Completed_lessons on Completed_lessons.lesson_id = Lessons.id
            and Completed_lessons.user_id = ? WHERE lessons.id = ? group by Lessons.id, Courses.id' , [$user->id, $lesson_id]);

        if($lesson != null){
            return response()->json(['status' => true, 'message' => "Ders başarıyla listelendi",'data'=>$lesson]);
        }else{
            return response()->json(['status' => false, 'message' => "Hata! Daha sonra tekrar deneyiniz."]);
        }
    }

    public function completedLesson($lesson_id){
        $user = auth('sanctum')->user();
        $lesson = CompletedLesson::where('lesson_id', $lesson_id)->first();

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
                return response()->json(['status' => false, 'message' => "Hata! Bu dersi daha önce tamamlamışsınız."]);
            }
        } else {
            return response()->json(['status' => false, 'message' => "Hata! Ders bulunamadı, lütfen daha sonra tekrar deneyiniz."]);
        }

    }

    public function questions($lesson_id){
        $questions = Question::where('lesson_id', $lesson_id)->with('answers')->get();

        if($questions->isNotEmpty()){
            return response()->json(['status' => true, 'message' => "Sorular başarıyla listelendi",'Questions'=> $questions]);
        }else{
            return response()->json(['status' => false, 'message' => "Hata! Daha sonra tekrar deneyiniz."]);
        }
    }

}
