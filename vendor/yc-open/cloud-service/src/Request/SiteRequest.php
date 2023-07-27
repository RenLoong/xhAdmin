<?php
namespace YcOpen\CloudService\Request;

use YcOpen\CloudService\DataModel;
use YcOpen\CloudService\Request;
use YcOpen\CloudService\Validator;

/**
 * 网站相关接口
 */
class SiteRequest extends Request
{
    /**
     * 提交网站信息
     * @param mixed $params
     * @return SiteRequest
     */
    public function install(mixed $params=null)
    {
        $this->setMethod('POST');
        $this->setUrl('Site/install');
        $validator=new Validator;
        $validator->rules([
            'domain'=>'required',
            'title'=>'required'
        ]);
        $this->validator=$validator;
        if($params){
            $this->setParams($params);
        }
        return $this;
    }
    /**
     * 设置响应数据模型
     * @return DataModel
     */
    public function setResponse(mixed $data):DataModel
    {
        # 将data写入网站根目录下的ycOpen.json文件
        file_put_contents($this->siteinfo_file,json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
        return new DataModel($data);
    }
}