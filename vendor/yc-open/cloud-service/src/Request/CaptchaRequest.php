<?php
namespace YcOpen\CloudService\Request;

use YcOpen\CloudService\Request;
/**
 * 验证码相关接口
 * Class CaptchaRequest
 * @package YcOpen\CloudService\Request
 */
class CaptchaRequest extends Request
{
    /**
     * 获取验证码
     * @return CaptchaRequest
     */
    public function captchaCode()
    {
        $this->setUrl('Captcha/captchaCode');
        return $this;
    }
}