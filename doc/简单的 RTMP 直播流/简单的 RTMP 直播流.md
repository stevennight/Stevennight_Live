# 简单的 RTMP 直播流

[TOC]

## 前言

![Stream](.\Stream.png)

推流端有 FFmpeg 、OBS(Open Broadcaster Software) 以及其他推流端。

RTMP 服务器有 SRS(Simple RTMP Server) 、Nginx+RTMP-module 以及其他 RTMP 服务器。

播放器有 VLC 、Flash 以及其他播放器。

推流端 → RTMP 服务器 → 播放器，构成简单的 RTMP(Real Time Messaging Protocol) 直播流。

## 实例

###选用 SRS 作为服务器 

![snipaste20170330_150656](.\snipaste20170330_150656.png)

按照 https://github.com/winlinvip/srs 项目的三步说明：

从 GitHub 上克隆项目到自己的机器上（经由 CDN 进行分发，而 CDN 被墙了，你懂的。）

编译 SRS ，最后运行。

![snipaste20170504_173200](.\snipaste20170504_173200.png)

也可以使用其他镜像源克隆，或者下载对应 Linux 发行版编译好的包：http://ossrs.net/srs.release/releases/

![snipaste20170330_144332](.\snipaste20170330_144332.png)

```shell
./objs/srs -c conf/srs.conf
```

srs -c 配置文件

###选用 FFmpeg 作为推流端

SRS 项目是有加入第三方项目 FFmpeg 进行编译生成：./objs/ffmpeg/bin/ffmpeg

编译配置参阅：https://github.com/ossrs/srs/wiki/v1_CN_Build

也可以在 http://ffmpeg.org/download.html 页面找到对应 Linux 发行版编译好的包。

![snipaste20170330_150548](.\snipaste20170330_150548.png)

```shell
ffmpeg -re -i 1.mp4 -c copy -f flv rtmp://127.0.0.1/live/abc;
```

ffmpeg -re 读取原生帧率 -i 输入 -c 编码器 为 copy 则复制编码流 -f 强制输出

FFmpeg Documentation：http://ffmpeg.org/ffmpeg-all.html

IP 地址对应机器，live 对应路径，Stream key（串流码 / 流密钥）对应直播流。

![snipaste20170330_154025](.\snipaste20170330_154025.png)

FFmpeg 推流中... 

###选用 VLC 作为播放器

![snipaste20170330_154808](.\snipaste20170330_154808.png)

Open Network Stream

![snipaste20170330_155123](.\snipaste20170330_155123.png)

![snipaste20170330_163723](.\snipaste20170330_163723.png)

> 图片截自：Lefty Hand Cream - なんでやねんねん

-

CC BY-NC-SA 3.0 署名归属 https://github.com/stevennight/Stevennight_Live 项目

PDF 文件经由 Typora 导出。

