<?php

namespace App\Http\Controllers\Message;

use App\Model\FeedBack;
use App\Http\Controllers\Controller;
use App\Http\Requests\FeedBack\viewFeedBackCheck;
class viewFeedback extends Controller
{
    public function viewFeedback(viewFeedBackCheck $request){
        try{
            $id=$request->id;
            $data=FeedBack::select('content')
                ->where('id',$id)->get();
            return response()->success(200,"成功",$data);
        }catch (\Exception $e){
            return response()->fail(100, "失败");
        }
    }
}
