## 系统安装

目录

- 环境要求
- 安装
- 站点配置及说明

### 环境要求

- PHP >= 7.1.3
- PHP cURL 扩展
- PHP OpenSSL 扩展
- PHP fileinfo 拓展 素材管理模块需要用到
- Mysql >= 5.7
- Apache 或 Nginx
- Composer (用于管理第三方扩展包)
- 安装CA证书 (windows开发环境下)

> 必须先看[环境搭建文档](start-issue.md)，安装完毕后务必配置站点和对应的[伪静态](start-rewrite.md)还有[常见问题文档](start-environment.md)

### 安装

1、克隆

```
git clone https://github.com/jianyan74/rageframe2.git
```

2、进入目录

```
cd rageframe2
```

3、安装依赖

```
php composer.phar install 
```

4、初始化项目

```
php init // 然后输入0回车,再输入yes回车
```

5、配置数据库信息

```
找到 common/config/main-local.php 并配置相应的信息, 注意要先创建好数据库
```

6、安装数据库

```
php ./yii migrate/up
```

7、初始化账号密码，一键创建总管理员账号密码(注意保存)

```
php ./yii password/init
```

8、建议更新第三方扩展包(可选)

```
php composer.phar update
```

### 站点配置及说明

> 注意：先要设置好[伪静态](start-rewrite.md)

站点指向目录为当前项目的web下 例如: 

```
/path/to/rageframe2/web/
```

后台地址：当前域名/backend

微信地址：当前域名/wechat

Api地址：当前域名/api



