# HLS 直播自助  

[TOC]

## 前言

![0cbd6315-907e-4580-9c81-fe79decae054](.\0cbd6315-907e-4580-9c81-fe79decae054.png)

> 图片截自：http://caniuse.com/#search=HLS

你可以在上图了解到这一技术（ HLS ，HTTP Live Streaming ）目前在浏览器的支持情况，得益于 https://github.com/video-dev/hls.js 项目基于 MSE(Media Source Extensions) 技术的帮助，使得 HLS 能得到全部现代浏览器的支持。

![e9228aa1-fa4f-49a9-ad78-b7adec3a8094](.\e9228aa1-fa4f-49a9-ad78-b7adec3a8094.png)

> 图片截自：http://caniuse.com/#search=MSE

m3u8 文件的本质是一小段一小段 ts 视频文件的列表，因此机制使用 HLS 直播会有几十秒的延迟。

想了解更多 HLS 可参阅：https://github.com/ossrs/srs/wiki/v3_CN_DeliveryHLS

## 快速直播流程

可以在论坛的直播教程中找到直播需要用到的软件：

http://bbs.sise.com.cn/forum.php?mod=viewthread&tid=245022

### 服务器

![snipaste20170423_174006](.\snipaste20170423_174006.png)

解压 **7thlive.zip** 并确保所在路径没有中文。

![snipaste20170423_174056](.\snipaste20170423_174056.png)

双击 **go-nds.exe** 运行服务器，在结束直播后于任务管理器中找到并结束这一进程。

-

也可以在当前路径，`Shift` + `鼠标右键`打开 CMD 或 Powershell 窗口执行 .\go-nds.exe

在命令行窗口打开有助于查看是否有错误信息，例如 8080 端口被其他程序占用，导致启动失败。

当然即使不这么做也可以在 logs\error.log 文件查看错误信息。

我也不知道开发者们哪找到的 go-nds.exe ，请自行考虑是否使用。

你也可以使用自己的 Web 服务器，但需要解决**跨域问题**。

-

![snipaste20170423_173642](.\snipaste20170423_173642.png)

在 conf\nginx.conf 文件中已经配置了 Allow-Control-Allow-Origin 头以跨域，也可于此修改服务器的端口。

### 录制

启动 **OBS Studio** ，其它设置请参考 **RTMP 直播自助**。

![snipaste20170423_210807](.\snipaste20170423_210807.png)

设置录像路径为服务器 **www** 文件夹的路径，在我这路径为：C:\Users\Test\Downloads\7thlive\Dev-Server\www

录像质量根据机器性能自行选择。

录像格式一定是 **m3u8** 。

-

勾选生成没有空格文件名后如果文件名中有空格会被下划线取代。

自定义 Muxer，视音频复用器，想用的话自己去查 FFmpeg 自定义 Muxer。

回放缓存专门用来保存精彩瞬间，会在内存缓存自定义时长的内容，在需要的时候使用快捷键保存为视频文件。

-

![snipaste20170423_212221](.\snipaste20170423_212221.png)

文件名格式随便填，意思就是你想填什么填什么。

勾选如果文件存在，覆盖。字面意思，如果录像文件存在，录制的时候会覆盖这个文件。

确定后开始录制。

-

文件名格式允许以格式名保存文件，例如默认填的是%CCYY-%MM-%DD %hh-%mm-%ss 

即年-月-日 时-分-秒 1970-01-01 0-0-0 没有空格则为 1970-01-01_0-0-0

回放缓存文件名前缀 Replay，则快捷键保存精彩瞬间的文件名为 Replay 1970-01-01 0-0-0

-

### 直播平台设置

![snipaste20170423_214744](.\snipaste20170423_214744.png)

格式：http://你的 IP 地址:8080/abc.m3u8

IP 地址对应机器，端口对应软件，文件名对应文件名。

保存成功后，查看自己的房间是否能正常观看。记得不直播时关闭首页显示。 

-

如果 **www** 文件夹里面多放个文件夹 x ，而录像文件 abc.m3u8 放在 x 文件夹里

路径则为 http://你的 IP 地址:8080/x/abc.m3u8

-

CC BY-NC-SA 3.0 署名归属 https://github.com/stevennight/Stevennight_Live 项目

PDF 文件经由 Typora 导出。