<?php

namespace App\Model;

use App\Utils\Logs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use mysql_xdevapi\Exception;

class FeedBack extends Model
{
    //定义模型关联的数据表
    protected $table = 'feed_backs';
    //定义主键
    protected $primaryKey = 'id';
    //定义禁止操作时间
    protected $casts = [
        'content' => 'json', // 声明json类型
    ];
    public $timestamps = true;

    /**
     * @param $id
     * @return array|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public static function getInfo_echo($id)
    {
        try {
            $data = array();
            $i = 0;
            $status = self::verify();
            if ($status) {
                $info = self::where("project_id", $id)->where(function ($query) {
                    $query->where("to_user_id", Auth::id())->orWhere(function ($query) {
                        $query->where("to_user_id", 0);
                    });
                })
                    ->paginate(env('PAGE_NUM'));
                foreach ($info as $item) {
                    $user = User::find($item->from_uesr_id);
                    if ($user == null) $user["name"] = "用户已注销";
                    $json_decode = json_decode(json_encode($item->content));
                    $data['data'][$i++] = array(
                        "name" => $json_decode->title,
                        "type" => $json_decode->type,
                        "from" => $user["name"],
                        "to" => Auth::user()->name,
                    );
                }
                $info=json_decode(json_encode($info));
                $data['current_page']=$info->current_page;
                $data['first_page_url']=$info->first_page_url;
                $data['from']=$info->from;
                $data['last_page']=$info->last_page;
                $data['last_page_url']=$info->last_page_url;
                $data['next_page_url']=$info->next_page_url;
                $data['path']=$info->path;
                $data['per_page']=$info->per_page;
                $data['prev_page_url']=$info->prev_page_url;
                $data['to']=$info->to;
                $data['total']=$info->total;
                return $data;
            } else return null;
        } catch (\Exception $exception) {
            Logs::logError('获取反馈信息出错：', [$exception->getMessage()]);
            return null;
        }
    }

    public static function verify()
    {
        return Auth::id() != null ? Auth::id() : false;
    }
}
