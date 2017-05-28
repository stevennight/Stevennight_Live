#HTTP-FLV 直播自助

[TOC]

## 前言

![e9228aa1-fa4f-49a9-ad78-b7adec3a8094](.\e9228aa1-fa4f-49a9-ad78-b7adec3a8094.png)

> 图片截自：http://caniuse.com/#search=MSE

得益于 https://github.com/Bilibili/flv.js 项目基于 MSE(Media Source Extensions) 技术的帮助，使得 FLV 能得到除 iOS 外所有现代浏览器的支持。

想了解更多 HTTP-FLV 可参阅：https://github.com/ossrs/srs/wiki/v2_CN_DeliveryHttpStream

## 快速直播流程

### 服务器

#### 克隆 SRS 项目

SRS-HTTP-FLV 部署实例：https://github.com/ossrs/srs/wiki/v3_CN_SampleHttpFlv

参照部署实例克隆项目，不过由于 SRS 3.0 才支持跨域，目前处于开发分支，我们需要克隆 develop 分支。

```shell
git clone -b develop https://github.com/ossrs/srs.git
cd srs/trunk
```

git 克隆 -b  分支 源

cd 切换路径为 srs/trunk

因为经由 CDN 进行分发，而 CDN 被墙了，你懂的。

也可以使用其它源：https://github.com/ossrs/srs

![snipaste20170504_173200](.\snipaste20170504_173200.png)

例如：

```shell
git clone -b develop https://code.csdn.net/winlinvip/srs-csdn.git
cd srs/trunk
```

#### 编译 SRS

```shell
./configure && make
```

在不支持跨域的 2.0 版本：https://github.com/ossrs/srs/wiki/v2_CN_SampleHttpFlv

```shell
./configure --disable-all \
    --with-hls --with-ssl --with-http-server \
    --with-http-api && 
make
```

编译选项请参阅并注意版本上的区别：

https://github.com/ossrs/srs/wiki/v2_CN_Build

https://github.com/ossrs/srs/wiki/v3_CN_Build

#### 启动 SRS

![snipaste20170504_233031](.\snipaste20170504_233031.png)

虽然配置文件全部写好在 conf 文件夹里，但是需要自己修改 http.flv.live.conf 配置文件启用跨域。

```nginx
    crossdomain     on;
```

保存好配置文件后启动 srs。

```shell
./objs/srs -c conf/http.flv.live.conf
```

srs -c 配置文件为 http.flv.live.conf

### 推流端推流

用 OBS 推流请参考 RTMP 直播自助。

用 FFmpeg 推流请参考简单的 RTMP 直播流。

也可以按照 SRS-HTTP-FLV 部署实例的步骤：

```shell
for((;;)); do \
    ./objs/ffmpeg/bin/ffmpeg -re -i ./doc/source.200kbps.768x320.flv \
    -vcodec copy -acodec copy \
    -f flv -y rtmp://192.168.1.170/live/livestream; \
    sleep 1; \
done
```

编译好的 ffmpeg 在 ./objs/ffmpeg/bin/ 路径里，也就是在 obs → ffmpeg →bin 一层层的文件夹里，这是 SRS 项目内置的第三方部分，用于示例的 source.200kbps.768x320.flv 视频则在 doc 文件夹里。

生成的流地址为：

- RTMP 流地址为：`rtmp://192.168.1.170/live/livestream`

- HTTP-FLV: `http://192.168.1.170:8080/live/livestream.flv`

  注意将  IP 地址 192.168.1.170 替换为你自己的 IP 地址。

### 直播平台设置

![snipaste20170504_234331](.\snipaste20170504_234331.png)

格式：http://你的 IP 地址:8080/live/abc.flv

IP 地址对应机器，端口对应软件，live 对应文件夹路径，文件名对应文件名。

![snipaste20170504_234909](.\snipaste20170504_234909.png)

格式：rtmp://你的 IP 地址/live/

保存成功后，查看自己的房间是否能正常观看。记得不直播时关闭首页显示。 

## 其他

### 启动配置文件为 http.hls.conf 

```shell
./objs/srs -c conf/http.hls.conf
```

如果配置文件为 http.hls.conf 那么则生成 HLS 流地址，同样要修改配置文件启用跨域。

### 虚拟机网卡设置

如果是使用 VirtualBox ，以下是关于网卡切换的一点提示。

![snipaste20170504_235837](.\snipaste20170504_235837.png)

鼠标右键

![snipaste20170504_235720](.\snipaste20170504_235720.png)

网络...

![snipaste20170505_000058](.\snipaste20170505_000058.png)

![snipaste20170505_000133](.\snipaste20170505_000133.png)

网络地址转换(NAT)：使用宿主机的网络。

桥接网卡：对外表现独立网卡。

-

CC BY-NC-SA 3.0 署名归属 https://github.com/stevennight/Stevennight_Live 项目

PDF 文件经由 Typora 导出。