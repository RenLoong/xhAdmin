<?php
namespace YcOpen\CloudService\Request;

use YcOpen\CloudService\DataModel;
use YcOpen\CloudService\Redis;
use YcOpen\CloudService\Request;
use YcOpen\CloudService\Validator;

/**
 * 登录相关接口
 * Class LoginRequest
 * @package YcOpen\CloudService\Request
 */
class LoginRequest extends Request
{
    /**
     * 登录接口
     * @return LoginRequest
     */
    public function login()
    {
        $this->setMethod('POST');
        $this->setUrl('Login/login');
        $validator=new Validator;
        $validator->rules([
            'username'=>'required',
            'password'=>'required',
            'scode'=>'required'
        ]);
        $this->validator=$validator;
        return $this;
    }
    /**
     * 设置响应数据模型
     * @return DataModel
     */
    public function setResponse(mixed $data):DataModel
    {
        # 将data中token写入Redis
        Redis::set('yc-cloud-service-token',$data['token']);
        return new DataModel($data);
    }
}