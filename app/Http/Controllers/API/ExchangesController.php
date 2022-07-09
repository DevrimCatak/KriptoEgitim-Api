<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CompletedLesson;
use App\Models\Exchanges;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExchangesController extends Controller
{
    public function exchanges(){
        $exchanges = Exchanges::where('status', 1)->get();
        if($exchanges != null){
            return response()->json(['status' => true, 'message' => "Borsa önerileri başarıyla listelendi",'data'=>$exchanges]);
        }else{
            return response()->json(['status' => false, 'message' => "Hata! Daha sonra tekrar deneyiniz."]);
        }
    }
}
