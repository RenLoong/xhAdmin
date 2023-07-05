<?php
namespace YcOpen\CloudService\Request;

use YcOpen\CloudService\Request;
/**
 * 用户相关接口
 * Class UserRequest
 * @package YcOpen\CloudService\Request
 */
class UserRequest extends Request
{
    /**
     * 获取用户信息
     * @return UserRequest
     */
    public function info()
    {
        $this->setUrl('User/info');
        return $this;
    }
    /**
     * 获取用户账单
     * @return UserRequest
     */
    public function getUserBill()
    {
        $this->setUrl('User/getUserBill');
        return $this;
    }
}