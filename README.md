![输入图片说明](https://img.alicdn.com/imgextra/i1/2064565174/O1CN01hXCaou1o5k5UUTHYu_!!2064565174.png)

<div align="center">

基于**KFadmin**快速创建属于你自己的多应用**Saas**系统

</div>

<div align="center" >
    <a href="https://kfadmin.net/">
        <img src="https://img.shields.io/badge/license-Apache%202-blue.svg" />
    </a>
    <a href="https://kfadmin.net/">
        <img src="https://img.shields.io/badge/Edition-4.5-blue.svg" />
    </a>
     <a href="https://kfadmin.net/">
        <img src="https://img.shields.io/badge/Download-150m-red.svg" />
    </a>
</div>

####

<div align="center">

[官网](https://kfadmin.net/) |
[在线体验](https://demo.kfadmin.net/admin/) |
[帮助文档](https://kfadmin.net/) |
[技术社区](https://support.qq.com/products/423209/)
[应用市场](https://kfadmin.net/)

</div>
<div align="center" >
<a href="https://kaifa.cc">贵州猿创科技</a>
</div>

### 介绍

KFadmin 是一套基于最新技术的研发的多应用 Saas 框架，支持在线升级和安装模块及模板，拥有良好的开发框架、成熟稳定的技术解决方案、提供丰富的扩展功能。为开发者赋能，助力企业发展、国家富强，致力于打造最受欢迎的多应用 Saas 系统。

### 系统亮点

```
多 语 言：后台随时配置语言包，移动端支持多语言切换；
高 性 能：基于webman开发，一样的写法，十倍的性能；
前后端分离：内核采用TP6的ORM，前端采用Vite+TypeScript+Vue3+远程组件+热更新+动态渲染+多主题等等全新技术栈；
代码规范：遵循PSR-2命名规范、Restful标准接口、代码严格分层、注释齐全、统一错误码；
权限管理：内置强大灵活的权限管理，可以控制到每一个菜单；
开发配置：低代码增加配置、系统组合数据模块；
二开效率：PHP快速生成表单、内置所有事件、后台在线编辑器、代码注释齐全、完整接口文档；
快速上手：详细帮助文档、接口文档、数据库字典、代码注释、一键安装；
系统安全：系统操作日志、系统生产日志、文件校验、数据备份；
用户体验：等您来评！
```

### 运行环境

```
Nignx
PHP 8.0 ~ 8.1
MySQL 5.7
Redis
```

> 温馨提示：虚拟空间不支持，推荐使用 bt 宝塔面板，服务器推荐阿里云 ecs 或腾讯云 cvm 云服务器。

### 安装教程

> 1、下载框架

 <a href="https://gitee.com/yc_open/kfadmin-cloud/repository/archive/master.zip" target="_blank">
 点击下载KFAdmin框架
 </a>

> 2、将代码上传至站点根目录

> 3、设置站点根目录为public

> 4、设置nginx配置

> 在server外部设置
```
upstream kfadmin_net {
  # HPAdmin HTTP Server 的 IP 及 端口
  server 127.0.0.1:39600;
}
```
以下配置在nginx的server内部配置
```
location /install/ {
    try_files $uri $uri/ =404;
}
# Http
location / {
    # 将客户端的 Host 和 IP 信息一并转发到对应节点
    proxy_set_header Host $http_host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    
    # 转发Cookie，设置 SameSite
    proxy_cookie_path / "/; secure; HttpOnly; SameSite=strict";
    # 关闭重试机制
    proxy_next_upstream off;
    
    # 跨域请求
    if ($request_method = OPTIONS) {
        add_header Access-Control-Allow-Origin $http_origin; # 必须要有
        add_header Access-Control-Allow-Headers *; # 必须要有
        add_header Access-Control-Allow-Methods "GET,POST,PUT, DELETE,OPTION"; # 不加也行
        #add_header Access-Control-Allow-Credentials true; # 不加也行
        return 200; # 204也可以，只要返回成功码即可
    }
    
    location ~ .*\.(css|js|jpg|jpeg|png|bmp|swf)$
    {
        proxy_pass http://kfadmin_net;
    }
    
    # 判断是否访问根域名
    if ( -e $request_uri) {
        proxy_pass http://kfadmin_net;
        break;
    }
    # 执行代理访问真实服务器
    if ( !-e $request_filename ){
        proxy_pass http://kfadmin_net;
        break;
    }
}
```
> 5、执行框架启动：执行php webman start 启动框架

> 6、执行数据配置安装
> 访问域名，http://你自己的域名 根据步骤进行安装


### 部署教程
```
1、安装进程守护管理器
宝塔->软件商店->进程守护管理器->安装

2、设置守护进程管理器

打开守护进程管理器->添加守护进程

设置名称：你自己设置的站点名称

启动用户为：root

运行目录：站点根目录

启动命令：php webman start

进程数量：1

自此KFAdmin框架全部安装与部署流程全部完成
```


### 官方社区

产品 BUG、优化建议，欢迎社区反馈：https://support.qq.com/products/423209/

### 系统演示

![KFadmin租户后台](https://img.alicdn.com/imgextra/i1/2064565174/O1CN01dfDrWp1o5k5Wbcbii_!!2064565174.png)

管理后台： http://demo.kfadmin.net/admin 账号：demo 密码：123456

租户后台： http://demo.kfadmin.net/store 账号：18786709420 密码：123456

### 部分页面展示

![输入图片说明](https://img.alicdn.com/imgextra/i1/2064565174/O1CN01hXCaou1o5k5UUTHYu_!!2064565174.png)
![输入图片说明](https://img.alicdn.com/imgextra/i1/2064565174/O1CN01hXCaou1o5k5UUTHYu_!!2064565174.png)
![输入图片说明](https://img.alicdn.com/imgextra/i4/2064565174/O1CN01kUKrGT1o5k5QMR0G4_!!2064565174.png)
![输入图片说明](https://img.alicdn.com/imgextra/i1/2064565174/O1CN01qi54gf1o5k5WbnTzB_!!2064565174.png)

### 产品生态

| 项目名称       | 关注量 | 项目介绍                               |
| -------------- | ------ | -------------------------------------- |
| 学法减分专业版 | ★★★★★  | 基于人工智能 AI，题库一键搜索          |
| 达人探店专业版 | ★★★★★  | 一站式本地生活服务撮合平台             |
| 外链大师专业版 | ★★★★★  | 公域流量转私域流量的最佳神器           |
| AI 绘画专业版  | ★★★★★  | 人像漫画,AI 换脸，各种 AI 聚合玩法     |
| 视频号分销助手 | ★★★★★  | 基于视频号生态的第三方分销平台         |
| 素材抓取大师   | ★★★★★  | 万能的素材下载助手，批量采集，一键下载 |
| ChatGPT 分销版 | ★★★★★  | GPT 的元老级产品，功能强大到无法想象   |
| 未来更多...... | ★★★★★  | 关注 KFadmin 未来生态                  |

### 商业版购买

开源不易，以下如果有需要，请支持一下，感谢您的支持，让我们更多动力！

欢迎反馈问题 [反馈问题](https://gitee.com/yc_open/kfadmin-cloud/issues)。

欢迎提交代码 [提交代码](https://gitee.com/yc_open/kfadmin-cloud/pulls)。

### 特别鸣谢

排名不分先后，感谢这些软件的开发者：thinkphp、iview、vue、mysql、redis、uniapp、echarts、tree-table-vue、swiper、form-create 等，如有遗漏请联系我！

### 核心开发团队

产品：张宇凡

研发：余心、楚羽幽、杨漂

UI：Hmm、林深见灵鹿`

### 使用须知

1.允许用于个人学习、毕业设计、教学案例、公益事业、商业使用;

2.如果商用必须保留版权信息，请自觉遵守;

3.禁止将本项目的代码和资源进行任何形式的出售，产生的一切任何后果责任由侵权者自负。

### 版权信息

本项目包含的第三方源码和二进制文件之版权信息另行标注。

版权所有 Copyright © 2019-2023 by KFadmin (https://kfadmin.net)

All rights reserved。

KFadmin® 商标和著作权所有者为贵州猿创科技有限责任公司。
