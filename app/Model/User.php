<?php

namespace App\Model;

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
    public static function getAllUsers()
    {
        try {
            $res = DB::table('users as t1')
                ->join('project_members as t2', 't1.id', '=', 't2.user_id')
                ->join('projects as t3', 't2.project_id', 't3.id')
                ->join('positions as t4', 't1.id', 't4.user_id')
                ->select('t1.name', 't2.type', 't4.position_code', 't1.phone_number', 't1.email', 't3.name')
                ->paginate(4);
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('获取所有人员失败!', [$e->getMessage()]);
        }
        return $res;
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
                ->leftJoin('project_members as t2', 't1.id', 't2.user_id')
                ->leftJoin('projects as t3', 't2.project_id', 't3.id')
                ->where('t1.id', $id)
                ->update([
                    't3.name' => $request->name,
                    't2.type' => $request->type
                ]);
            return $res;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('修改人员失败!', [$e->getMessage()]);
        }
    }

    /**
     *移除人员
     */
    public static function deleteUser($pname, $id)
    {
        try {
            $res = DB::table('users as t1')
                ->leftjoin('project_members as t2', 't1.id', 't2.user_id')
                ->leftjoin('projects as t3', 't3.id', 't2.project_id')
                ->select('t2.id')
                ->where('t3.name', $pname)
                ->where('t1.id', $id)
                ->get()
                ->toarray();
            $data = DB::table('project_members')
                ->where('id', $res[0]->id)
                ->update([
                    'project_id' => 0,
                ]);
            return $data;
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
                ->where('t2.type', $data['type'])
                ->where('t3.name', $data['pname'])
                ->paginate(4);
            return $res;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('获取人员失败!', [$e->getMessage()]);
        }
    }

    /**
     * 查询
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
                ->paginate(4)
                ->toarray();
            return $res;
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('搜索失败!', [$e->getMessage()]);
        }
    }
}
