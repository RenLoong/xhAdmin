<?php

namespace plugin\{TEAM_PLUGIN_NAME}\app\model;

/**
 * 文章内容
 * @author 贵州猿创科技有限公司
 * @Email 416716328@qq.com
 * @DateTime 2023-03-11
 */
class PluginArticles extends AppidModel
{
    /**
     * 关联分类
     * @return \think\model\relation\HasOne
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function category()
    {
        return $this->hasOne(PluginArticlesCate::class, 'id', 'cid');
    }

    /**
     * 获取分类选项
     * @return array
     * @author 贵州猿创科技有限公司
     * @copyright 贵州猿创科技有限公司
     * @email 416716328@qq.com
     */
    public function getCategoryOptions()
    {
        $fields = [
            'id as value',
            'title as label'
        ];
        $where  = [
            'status'    => '20',
        ];
        $data = PluginArticlesCate::where($where)->field($fields)->select()->toArray();
        return $data;
    }
}
