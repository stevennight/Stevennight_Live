# Stevennight Live -- Stevennight 直播

[![GitHub version](https://badge.fury.io/gh/stevennight%2FStevennight_Live@2x.png)](https://badge.fury.io/gh/stevennight%2FStevennight_Live)
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

```shell
php artisan key:generate
```

5、输入以下命令初始化数据库。

```shell
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

- danmakuSpeed: 弹幕存留的时间。设置不同会改变弹幕的移动速度。（最小播放器大小下的弹幕通过时间，实际通过时间会根据播放器的宽度大小而改变。单位：毫秒。）

- mustVerifyEmail: 登录账号的邮箱未验证，允许登录为0，拒绝登录为1。

- mustVerifyQQ: 登陆账号的QQ未验证，允许登录为0，拒绝登录为1。

- mustVerifyEmail_when_createroom: 创建房间账号是否需要验证过邮箱，是为1，否为0。

- mustVerifyQQ_when_createroom: 创建房间账号是否需要验证过QQ，是为1，否为0。

#### 2、直播分类设置

在(数据表前缀)category数据表中，增加(insert)友情链接的条目就可以了。

- id: 不用管。
- name: 分类名称。

#### 3、友情链接的配置

在(数据表前缀)links数据表中，增加(insert)友情链接的条目就可以了。

- id: 不用管。
- name: 友情链接标题，显示的文字。
- link: 友情链接指向的路径。

## 程序更新

关于更新，不支持跨版本更新，除非您可以处理好数据库更新问题。

更新前请注意，数据记得备份，不备份而造成的数据损坏无法恢复请自行承担风险。

### 1、文件替换

​	从github中下载最新的文件覆盖替换原有文件。

### 2、数据库更新

​	在命令行中，前往应用目录，并执行以下命令更新数据库。

```shell
php artisan migrate:refresh
```

​	如果更新发生错误，可以根据错误提示自行解决，如果程序文件的错误，也欢迎提交Issues。

## 更新日志

2017/05/19

增加两个适配手机的主播专用页面，一个是单独的聊天页面以及房间开关控制，一个是单独的修改信息页面。

2017/04/25

增加hls直播的方式。使用html5播放器(dplayer)进行播放m3u8 hls live。

调整部分版式，以更适合手机使用。（全屏自动横屏）

调整小屏隐藏logo的问题，导致没有链接返回首页。

2017/02/24

调整部分模板配色与布局。

调整弹幕间距以及速度问题。

增加游客发言控制。

2017/02/23

增加根据邮箱/QQ验证状态以确定是否允许登录本应用的功能。

增加根据邮箱/QQ验证状态以确定是否允许创建房间的功能。

站点账号的封禁独立于账号中心账号管理(账号中心账号非封禁状态，本应用也可以控制是否允许账号登录。账号中心账号被封禁，本应用该账号也将自动封禁。(因为账号中心不会将该操作推送通知给各个应用，会在登录或信息重新从账号中心拉取时实施。)(修改users表中，baned属性一列。))

2017/02/18

修复一处代码错误，导致找不到数据表中的列。

2017/02/17

初次完整更新好了。包括这个readme。

## License

[GNU General Public License, version 3](license)

*flash 播放器由Flash Media Server 中提取并使用，版权属Adobe所有。如有不当，请联系，将撤下使用的播放器，届时各位要使用的话请自行嵌入播放器到相关位置。

*html5 播放器为[dplayer](https://github.com/DIYgod/DPlayer) 播放器。感谢DIYgod。
