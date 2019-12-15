<?php

namespace App\Model;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;

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
    public $timestamps = true;
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
     * @param $UserId
     * @param array $array
     * @return mixed
     * @throws \Exception
     */
    public static function getUserInfo($UserId, $array = [])
    {
        try {
            return $array == null ?
                self::where('id', $UserId)->get() :
                self::where('id', $UserId)->get($array);
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

    /**
     * 定义与project_members的关联
     */
    public function projectMembers()
    {
        return $this->hasOne('App\ProjectMember', 'user_id', 'id');
    }


    /**
     * 获取所有人员
     *
     * @return
     * @throws \Exception
     */
    public static function getAllUsers($id)
    {
        try {
            $res = DB::table('users as t1')
                ->leftjoin('project_members as t2', 't1.id', '=', 't2.user_id')
                ->leftjoin('projects as t3', 't2.project_id', 't3.id')
                ->leftjoin('positions as t4', 't1.id', 't4.user_id')
                ->select('t1.id', 't1.name', 't2.type', 't4.position_code', 't1.phone_number', 't1.email', 't3.name as project_name')
                ->where('t3.amdin_user_id', $id)
                ->paginate(env('PAGE_NUM'));
            return $res;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('获取所有人员失败!', [$e->getMessage()]);
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public static function getUpdateUsers($id)
    {
        try {
            $data = DB::table('users as t1')
                ->leftJoin('positions as t2', 't1.id', 't2.user_id')
                ->select('t1.id', 't1.name', 't1.phone_number', 't1.email', 't2.position_code')
                ->where('t1.id', $id)
                ->get();
            return $data;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('获取修改人员失败!', [$e->getMessage()]);
        }
    }

    /**
     *获取要修改人员
     * */
    public static function getUpdateUser($id)
    {
        try {
            $user = User::find($id);
            if ($user != null) {
                $message = $user->name . '被删除了';
                \App\Utils\Logs::logInfo($message, Auth::user());
                return $user->delete();
            } else {
                return null;
            }
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('删除用户失败！', [$e->getMessage()]);
            return null;
        }
    }

    /**
     *修改人员
     */
    public static function updateUser($request, $id)
    {
        try {
            $res = DB::table('users as t1')
                ->join('project_members as t2', 't1.id', 't2.user_id')
                ->join('projects as t3', 't2.project_id', 't3.id')
                ->join('positions as t4', 't4.user_id', 't1.id')
                ->where('t1.id', $id)
                ->where('t3.id', $request->pid)
                ->update([
                    't4.position_code' => $request->pcode
                ]);
            return $res;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('修改人员失败!', [$e->getMessage()]);
        }
    }

    /**
     *移除人员
     */
    public static function deleteUser($pid, $id)
    {
        try {
            $res = DB::table('project_members')
                ->where('project_id', $pid)
                ->where('user_id', $id)
                ->delete();
            return $res;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('移除人员失败!', [$e->getMessage()]);
        }
    }

    /**
     *查询人员(根据传入数据的不同查出不同的数据)
     */
    public static function getUsers($data)
    {
        try {
            $res = DB::table('users as t1')
                ->join('project_members as t2', 't1.id', '=', 't2.user_id')
                ->join('projects as t3', 't2.project_id', 't3.id')
                ->join('positions as t4', 't1.id', 't4.user_id')
                ->select('t1.id', 't1.name', 't2.type', 't4.position_code', 't1.phone_number', 't1.email', 't3.name')
                ->where('t4.position_code', $data['pcode'])
                ->where('t3.id', $data['pid'])
                ->paginate(env('PAGE_NUM'));
            return $res;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('获取人员失败!', [$e->getMessage()]);
        }
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public static function searchUser($data)
    {
        try {
            $res = DB::table('users as t1')
                ->leftJoin('project_members as t2', 't1.id', '=', 't2.user_id')
                ->leftJoin('projects as t3', 't2.project_id', 't3.id')
                ->join('positions as t4', 't1.id', 't4.user_id')
                ->select('t1.id', 't1.name', 't2.type', 't4.position_code', 't1.phone_number', 't1.email', 't3.name')
                ->where('t1.name', 'like', '%' . $data . '%')
                ->orwhere('t1.email', 'like', '%' . $data . '%')
                ->orwhere('t1.phone_number', 'like', '%' . $data . '%')
                ->paginate(env('PAGE_NUM'))
                ->toarray();
            return $res;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('搜索失败!', [$e->getMessage()]);
            return null;
        }
    }

    /**
     * 修改用户密码
     * @param $request
     * @return bool
     * @throws \Exception
     */
    public static function updateUserPassword($request)
    {
        try {
            if (self::checkOldPassword($request->old_password)) {
                return self::where('id', Auth::id())->update(['password' => bcrypt($request->new_password)]) ?
                    true :
                    false;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('修改密码发生错误!', [$e->getMessage()]);
            return false;
        }
    }

    /**
     * 检查用户原密码
     * @param $old_password
     * @return mixed
     * @throws \Exception
     */
    protected static function checkOldPassword($old_password)
    {
        try {
            return Hash::check($old_password, self::where('id', Auth::id())->first()->password);
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('检查用户原密码发生错误!', [$e->getMessage()]);
            return false;
        }
    }

    /**
     * @param $str
     * @return bool
     * @throws \Exception
     */
    public static function queryUsers($str)
    {
        try {
            $data = self::where('name', 'like', '%' . $str . '%')
                ->orwhere('phone_number', 'like', '%' . $str . '%')
                ->orwhere('email', 'like', '%' . $str . '%')->paginate(env('PAGE_NUM'));
            return $data;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('查询用户失败!', [$e->getMessage()]);
            return false;
        }
    }

    /**
     *  查询用户
     * @return __anonymous@8413|null
     * @throws \Exception
     */
    public static function selectUser()
    {
        try {
            $user = self::orderBy('id', 'desc')->where('state', 0)->paginate(env('PAGE_NUM'));
            $item = Project::all();
            $data = new class
            {
            };
            $data->user = $user;
            $data->item = $item;
            return $data;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('添加用户失败!', [$e->getMessage()]);
            return null;
        }
    }


    /**
     * @param $access_code
     * @param $page
     * @param array $array
     * @return |null
     * @throws \Exception
     */
    public static function getInfo($access_code, $page, $array = [])
    {
        try {
            return $access_code == null ?
                User::select($array)->paginate($page) :
                User::select($array)->where('access_code', $access_code)->paginate($page);
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('查询用户信息失败!', [$e->getMessage()]);
            return null;
        }
    }

    /**
     * 删除用户
     * @param $user_id
     * @return |null
     * @throws \Exception
     */
    public static function adminDeleteUser($user_id)
    {
        try {
            $user = User::find($user_id);
            if ($user != null) {
                $message = $user->name + '被删除了';
                \App\Utils\Logs::logInfo($message, Auth::user());
                return $user->delete();
            } else {
                return null;
            }
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('删除用户失败！', [$e->getMessage()]);
            return null;
        }
    }


    /**
     * 搜索用户
     * @param $search
     * @param $page
     * @param array $array
     * @return |null
     * @throws \Exception
     */
    public static function Search($search, $page, $array = [])
    {
        try {
            return User::select($array)->where('name', 'like', '%' . $search . '%')
                ->orwhere('phone_number', 'like', '%' . $search . '%')
                ->orwhere('email', 'like', '%' . $search . '%')->paginate($page);
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('搜索用户失败！', [$e->getMessage()]);
            return null;
        }
    }

    /**
     * 获取指定用户信息
     * @param $user_id
     * @param array $array
     * @return |null
     * @throws \Exception
     */
    public static function ShowUserInfo($user_id, $array = [])
    {
        try {
            return DB::table('users')->select($array)->where('users.id', '=', $user_id)->get();
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('获取用户信息失败！', [$e->getMessage()]);
            return null;
        }
    }


    /**
     * 修改用户
     * @param array $array
     * @return bool
     * @throws \Exception
     */
    public static function UpdateUserInfo($array = [])
    {
        try {
            if ($array[2] == null) {
                $user = User::where('id', $array[0])->update([
                    'name' => $array[1],
                    'phone_number' => $array[3],
                    'email' => $array[4],
                    'access_code' => $array[5],
                    'state' => $array[6],
                ]);
            } else {
                $user = User::where('id', $array[0])->update([
                    'name' => $array[1],
                    'password' => bcrypt($array[2]),
                    'phone_number' => $array[3],
                    'email' => $array[4],
                    'access_code' => $array[5],
                    'state' => $array[6],
                ]);
            }
            if ($user) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('修改用户信息失败！', [$e->getMessage()]);
            return false;
        }
    }


    /**
     * 添加用户
     * @param array $array
     * @return bool
     * @throws \Exception
     */
    public static function AddUser($array = [])
    {
        try {
            $model = new User();
            $model->name = $array[0];
            $model->password = bcrypt($array[1]);
            $model->phone_number = $array[2];
            $model->email = $array[3];
            $model->access_code = $array[4];
            $result = $model->save();
            if ($result) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('新增用户信息失败！', [$e->getMessage()]);
            return false;
        }
    }

}
