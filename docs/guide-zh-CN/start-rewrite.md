## 伪静态

目录

- nginx
- apache

### Nginx

推荐配置

```
location / {
    try_files $uri $uri/ /index.php$is_args$args;
}
location /backend {
    try_files $uri $uri/ /backend/index.php$is_args$args;
}
location /api {
    try_files $uri $uri/ /api/index.php$is_args$args;
}
location /wechat {
    try_files $uri $uri/ /wechat/index.php$is_args$args;
}
```

类似Apache的配置

```
location / 
{
     index  index.html index.htm index.php;

     if (!-e $request_filename) {
           rewrite ^/backend(.*)$ /backend/index.php?s=$1 last;
           rewrite ^/wechat(.*)$ /wechat/index.php?s=$1 last;
           rewrite ^/api(.*)$ /api/index.php?s=$1 last;
           rewrite ^/(.*)$ /index.php?s=$1 last;
           break;
     }
         
     #autoindex  on;
}
```

### Apache

> 注意系统默认自带了.htaccess，所以环境如果是apache可以不用再配置

```
Options +FollowSymLinks
IndexIgnore */*
RewriteEngine on

# if a directory or a file exists, use it directly
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

# otherwise forward it to index.php
RewriteRule . index.php
```
