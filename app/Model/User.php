<?php

namespace App\Model;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Authenticatable;


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
}
