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

        # 设置超时时间
        proxy_connect_timeout 600;
        proxy_send_timeout 600;
        proxy_read_timeout 600;
        
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
            proxy_pass http://{SERVER_NAME};
        }
        
        # 判断是否访问根域名
        if ( -e $request_uri) {
            proxy_pass http://{SERVER_NAME};
            break;
        }
        # 执行代理访问真实服务器
        if ( !-e $request_filename ){
            proxy_pass http://{SERVER_NAME};
            break;
        }
    }