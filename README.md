# 关于框架

本框架 php 要求 php8.0 + Mysql5.7 以上

# nginx配置规则
```nginx
upstream kf_admin {
  # HPAdmin HTTP Server 的 IP 及 端口
  server 127.0.0.1:39600;
}

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
        proxy_pass http://kf_admin;
    }
    
    # 判断是否访问根域名
    if ( -e $request_uri) {
        proxy_pass http://kf_admin;
        break;
    }
    # 执行代理访问真实服务器
    if ( !-e $request_filename ){
        proxy_pass http://kf_admin;
        break;
    }
}
```

# 框架主页及文档

## LICENSE

MIT

## 鸣谢所用到扩展

```
HTTP 客户端
https://www.workerman.net/plugin/94

生成可读的操作日志
https://www.workerman.net/plugin/96

form-builder
http://php.form-create.com/

think-orm
https://www.kancloud.cn/manual/thinkphp6_0/1037580
https://www.workerman.net/doc/webman/db/thinkorm.html

think-cache
https://github.com/top-think/think-cache

think-validate
https://www.kancloud.cn/manual/thinkphp6_0/1037624

webman-console
https://www.workerman.net/doc/webman/plugin/console.html

https://www.workerman.net/doc/webman/components/captcha.html
webman-captcha
```
