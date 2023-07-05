<?php
namespace YcOpen\CloudService\Request;

use YcOpen\CloudService\Request;
use YcOpen\CloudService\Validator;

/**
 * 应用插件相关接口
 * Class PluginRequest
 * @package YcOpen\CloudService\Request
 */
class PluginRequest extends Request
{
    /**
     * 获取插件列表
     * @return PluginRequest
     */
    public function list()
    {
        $this->setUrl('Plugin/list');
        return $this;
    }
    /**
     * 获取插件详情
     * @return PluginRequest
     */
    public function detail()
    {
        $this->setUrl('Plugin/detail');
        $validator=new Validator;
        $validator->rules([
            'name'=>'required'
        ]);
        $this->validator=$validator;
        return $this;
    }
    /**
     * 购买插件
     * @return PluginRequest
     */
    public function buy()
    {
        $this->setUrl('Plugin/buy');
        $validator=new Validator;
        $validator->rules([
            'name'=>'required'
        ]);
        $this->validator=$validator;
        return $this;
    }
    /**
     * 获取插件密钥
     * @return PluginRequest
     */
    public function getKey()
    {
        $this->setUrl('Plugin/getKey');
        $validator=new Validator;
        $validator->rules([
            'name'=>'required',
            'version'=>'required',
            'saas_version'=>'required',
            'local_version'=>'required'
        ]);
        $this->validator=$validator;
        return $this;
    }
}