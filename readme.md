# Stevennight Live -- Stevennight 直播

[TOC]

## 简介

​	这是一个用于汇聚局域网直播的一个平台。注意，这个应用本身没有充当直播串流服务器的功能。该应用有房间管理、罗列展示、分类、聊天、弹幕的功能。该应用需要使用stevennight_account_center应用进行用户的注册登录认证(邮箱验证)管理等功能。注意：具有聊天功能的服务器暂未上传至GITHUB。要使用的话可以更改相关JS部分以及编写一个简单的聊天服务器进行完善该功能。

## 使用说明

### 环境要求

- PHP版本 >= 5.6.4
- PHP扩展：OpenSSL
- PHP扩展：PDO
- PHP扩展：Mbstring
- PHP扩展：Tokenizer

*以上要求，是Laravel所需要的PHP环境要求。

另外我们还需要的还有：

Laravel 支持的数据库中其中的一种数据库，

- MySQL
- Postgres
- SQLite
- SQL Server

以及一台支持SMTP发送邮件的邮件服务器，用于邮箱验证以及密码找回这些需要邮件发送的功能。

### 初始化配置

1、将文件打包下载下来，并放到你想要放置的地方。

2、将网站的根目录只想文件夹中的public目录即可。

3、创建一个.env文件，配置范例可以查看.env example文件。需要配置的有数据库的信息，smtp服务器的信息等等。

4、打开命令行，进入该应用的根目录并输入以下命令进行生成app key。

```cmd
php artisan key:generate
```

5、输入以下命令初始化数据库。

```cmd
php artisan migrate
```

执行以上步骤之后，并且正确配置了网站之后，通常我们就可以浏览该应用的网页了，而不是输出一些错误信息。

### 配置网站设置

因为没有做好后台，所以……网站的配置需要在数据库中进行。以后会尽量补上。

#### 1、网站的全局配置

​	该配置在(数据表前缀)config_global_website表内。

- id: 不用管。

- name: 网站的名字。

- email: 网站邮箱，要注意的是，有时候这个邮箱必须和smtp设置中登录的邮箱相同，避免像腾讯邮件服务器等邮箱会辨别这个发送者邮箱发现其中不一致无法成功发送邮件。

- oauth_client_id: 从用户中心获取的客户端ID，如果你不是ACCOUNT_CENTER的管理员，请向该应用的管理员获取ID。

- oauth_client_secret: 从用户中心获取的客户端密钥，因为该项极为重要，请妥善保管不要泄露。如果你不是ACCOUNT_CENTER的管理员，请向该应用的管理员获取ID。

- roomcover_prefix: 网站文件目录所在位置，注意，该项命名问题，请不要理解为是房间封面的目录所在，那是有误的！

- roomcover_default: 默认房间封面的文件路径。

- chatserver: 聊天服务器的地址。

- danmakuSpeed: 弹幕存留的时间。设置不同会改变弹幕的移动速度。

  以上关于时间的配置，单位都为秒(s)。

#### 2、直播分类设置

在(数据表前缀)category数据表中，增加(insert)友情链接的条目就可以了。

- id: 不用管。
- name: 分类名称。

#### 3、友情链接的配置

在(数据表前缀)links数据表中，增加(insert)友情链接的条目就可以了。

- id: 不用管。
- name: 友情链接标题，显示的文字。
- link: 友情链接指向的路径。

## 更新日志

2017/02/18

修复一处代码错误，导致找不到数据表中的列。

2017/02/17

初次完整更新好了。包括这个readme。

## License

[GNU General Public License, version 3](license)