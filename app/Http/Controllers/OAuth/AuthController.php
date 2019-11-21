<?php

namespace App\Http\Controllers\OAuth;

use App\Http\Requests\OAuth\Auth\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\OAuth\Auth\RegisteredRequest;
use App\Model\User;

class AuthController extends Controller
{

    /**
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.check', ['except' => ['login', 'registered']]);
    }

    /**
     * 用户登陆
     *
     * @param LoginRequest $loginRequest
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function login(LoginRequest $loginRequest)
    {
        try {
            $credentials = self::credentials($loginRequest);
            if (!$token = auth()->attempt($credentials)) {
                return response()->fail(100, '账号或者用户名错误!', null);
            }
            return self::respondWithToken($token, '登陆成功!');
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('登陆失败!', [$e->getMessage()]);
            return response()->fail(500, '登陆失败!', null, 500);
        }
    }


    /**
     * 注册用户
     *
     * @param RegisteredRequest $registeredRequest
     * @return mixed
     * @throws \Exception
     */
    public function registered(RegisteredRequest $registeredRequest)
    {
        $registeredInfo = $registeredRequest->except('password_confirmation');
        $registeredInfo['password'] = bcrypt($registeredInfo['password']);
        if (User::createUser($registeredInfo)) {
            return response()->success(200, '注册成功!', null);
        } else {
            return response()->fail(100, '注册失败!', null);
        }
    }

    /**
     * 注销登陆
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            auth()->logout();
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('注销登陆失败!', [$e->getMessage()]);
        }
        return auth()->check() ?
            response()->fail(100, '注销登陆失败!', null) :
            response()->success(200, '注销登陆成功!', null);
    }

    /**
     * 获取用户信息
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function info()
    {
        $UserInfo = User::getUserInfo(auth()->id(), array('email', 'name', 'phone_number', 'access_code'));
        return $UserInfo != null ?
            response()->success(200, '获取成功!', $UserInfo) :
            response()->fail(100, '获取失败!');
    }

    /**
     * 刷新token
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function refresh()
    {
        try {
            $newToken = auth()->refresh();
        } catch (\Exception $e) {
            \App\Utils\Logs::logError('刷新token失败!', [$e->getMessage()]);
        }
        return $newToken != null ?
            self::respondWithToken($newToken, '刷新成功!') :
            response()->fail(100, '刷新token失败!');
    }

    /**
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $msg)
    {
        return response()->success(200, $msg, array(
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ));
    }

    /**
     * 生成用户凭证
     * @param $request
     * @return array
     */
    protected function credentials($request)
    {
        return ['email' => $request['email'], 'password' => $request['password']];
    }
}
