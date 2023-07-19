<?php
namespace YcOpen\CloudService\Request;

use YcOpen\CloudService\Request;
use YcOpen\CloudService\Validator;

/**
 * 系统更新相关接口
 * Class SystemUpdateRequest
 * @package YcOpen\CloudService\Request
 */
class SystemUpdateRequest extends Request
{
    /**
     * 验证是否需要更新
     * @return SystemUpdateRequest
     */
    public function verify()
    {
        $this->setUrl('SystemUpdate/verify');
        $validator=new Validator;
        $validator->rules([
            'version_name'=>'required',
            'version'=>'required'
        ]);
        $this->validator=$validator;
        return $this;
    }
    /**
     * 获取插件详情
     * @return SystemUpdateRequest
     */
    public function detail()
    {
        $this->setUrl('SystemUpdate/detail');
        $validator=new Validator;
        $validator->rules([
            'version_name'=>'required',
            'version'=>'required'
        ]);
        $this->validator=$validator;
        return $this;
    }
    /**
     * 获取插件密钥
     * @return SystemUpdateRequest
     */
    public function getKey()
    {
        $this->setUrl('SystemUpdate/getKey');
        $validator=new Validator;
        $validator->rules([
            'target_version'=>'required'
        ]);
        $this->validator=$validator;
        return $this;
    }
}