<?php

namespace App\Model;

use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Authenticatable;
use Validator;
use Illuminate\Validation\Rule;
use DB;


/**
 * @method static create(array $array)
 */
class User extends \Illuminate\Foundation\Auth\User implements JWTSubject, Authenticatable
{
    /**
     * 定义模型关联的数据表
     * @var string
     */
    protected $table = 'users';
    /**
     * 定义主键
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * 定义禁止操作时间
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var null
     */
    protected $rememberTokenName = NULL;

    /**
     * 设置批量赋值
     *
     * @var array
     */
    protected $guarded = [];


    /**
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return self::getKey();
    }

    /**
     * 根据用户id获取用户信息
     *
     * @param $UserId
     * @param array $array
     * @return mixed
     * @throws \Exception
     */
    public static function getUserInfo($UserId, $array = [])
    {
        try {
            if ($array == null) {
                $userInfo = self::where('id', $UserId)->get();
            } else {
                $userInfo = self::where('id', $UserId)->get($array);
//                $userInfo[0]['positions'] = Position::checkPosition($UserId);
            }
            return $userInfo;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('查询用户信息失败!', [$e->getMessage()]);
            return null;
        }
    }


    /**
     * 创建用户
     *
     * @param array $array
     * @return |null
     * @throws \Exception
     */
    public static function createUser($array = [])
    {
        try {
            return self::create($array) ?
                true :
                false;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('添加用户失败!', [$e->getMessage()]);
            return false;
        }
    }

    //人员管理界面查看用户(可根据状态)
    public static function getInfo($access_code,$page,$array=[]){
        try{
            return $access_code==null?
                User::select($array)->paginate($page):
                User::select($array)->where('access_code',$access_code)->paginate(8);
        }catch (\Exception $e){
            \App\Utils\Logs::logError('查询用户信息失败!', [$e->getMessage()]);
            return null;
        }
    }
    //删除用户
    public static function DeleteUser($user_id){
        try{
            $user=User::find($user_id);
            if($user!=null){
                $message=$user->name+'被删除了';
                \App\Utils\Logs::logInfo($message,Auth::user());
                return $user->delete();
            }else{
                return null;
            }
        }catch(\Exception $e){
            \App\Utils\Logs::logError('删除用户失败！', [$e->getMessage()]);
            return null;
        }
    }

    //搜索用户
    public static function Search($search,$page,$array=[]){
        try{
            return User::select($array)->where('name','like','%'.$search.'%')
                ->orwhere('phone_number','like','%'.$search.'%')
                ->orwhere('email','like','%'.$search.'%')->paginate($page);
        }catch(\Exception $e){
            \App\Utils\Logs::logError('搜索用户失败！', [$e->getMessage()]);
            return null;
        }
    }

    //获取指定用户信息
    public static function ShowUserInfo($user_id,$array=[]){
        try{
            return DB::table('users')->select($array)->where('users.id','=',$user_id)->get();
        }catch(\Exception $e){
            \App\Utils\Logs::logError('获取用户信息失败！', [$e->getMessage()]);
            return null;
        }
    }

    //修改用户
    public static function UpdateUserInfo($array=[]){
    try{
        if($array[2]==null){
            $user=User::where('id',$array[0])->update([
                'name'          =>  $array[1],
                'phone_number'  =>  $array[3],
                'email'         =>  $array[4],
                'access_code'   =>  $array[5],
                'state'         =>  $array[6],
                'updated_at'    =>  date("Y-m-d H:i:s")
            ]);
        }else{
            $user=User::where('id',$array[0])->update([
                'name'          =>  $array[1],
                'password'      =>  bcrypt($array[2]),
                'phone_number'  =>  $array[3],
                'email'         =>  $array[4],
                'access_code'   =>  $array[5],
                'state'         =>  $array[6],
                'updated_at'    =>  date("Y-m-d H:i:s")
            ]);
        }
        if($user){
            return true;
        }else{
            return false;
        }
    }catch(\Exception $e){
        \App\Utils\Logs::logError('修改用户信息失败！', [$e->getMessage()]);
        return false;
    }
    }

    //新增用户
    public static function AddUser($array=[]){
        try{
        $model=new User();
        $model->name=$array[0];
        $model->password=bcrypt($array[1]);
        $model->phone_number=$array[2];
        $model->email=$array[3];
        $model->access_code=$array[4];
        $model->created_at = date("Y-m-d H:i:s");
        $model->updated_at = date("Y-m-d H:i:s");
        $result=$model->save();
        if($result){
            return true;
        }else{
            return false;
        }
        }catch (\Exception $e){
            \App\Utils\Logs::logError('新增用户信息失败！', [$e->getMessage()]);
            return false;
        }
    }
}
