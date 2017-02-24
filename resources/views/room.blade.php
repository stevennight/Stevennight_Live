@extends('layouts.global',['value' => $value])

@section('page_title')
    {{ trans('view.room.page_title') }}
@stop

@section('body')
    @parent

    <style type="text/css">
        #player{
            padding: 0px;

            moz-user-select: -moz-none;
            -moz-user-select: none;
            -o-user-select:none;
            -khtml-user-select:none;
            -webkit-user-select:none;
            -ms-user-select:none;
            user-select:none;
        }
        #divPlayerSend{
            position:absolute;
            z-index:101;
            background: rgba(0,0,0,0.5);
            display: none;
        }
        #divPlayerSend>input{
            color:white;
        }
        #divPlayerSendFake{
            position:absolute;
            z-index:101;
        }
        #divDanmaku{
            position: absolute;
            z-index: 100;
            overflow:hidden;
        }
        #divDanmakufade{
            position: absolute;
            z-index: 100;
            overflow:hidden;
        }
        #divDanmaku > div{
            position: absolute;
            display:inline-block;
            color:white;
            padding:5px;
            border-radius: 5px;
            font-size: 1.4em;
            white-space:nowrap;
            letter-spacing:1px;
            {{--字体描边--}}
            text-shadow:#000 1px 0 10px,#000 0 1px 0,#000 -1px 0 0,#000 0 -1px 0;
            -webkit-text-shadow:#000 1px 0 10px,#000 0 1px 0,#000 -1px 0 0,#000 0 -1px 0;
            -moz-text-shadow:#000 1px 0 10px,#000 0 1px 0,#000 -1px 0 0,#000 0 -1px 0;
            *filter: Glow(color=#000, strength=1);
        }
        @keyframes move
        {
            100% {left: -1000px;}
        }

        @-moz-keyframes move /* Firefox */
        {
            100% {left: -1000px;}
        }

        @-webkit-keyframes move /* Safari 和 Chrome */
        {
            100% {left: -1000px;}
        }

        @-o-keyframes move /* Opera */
        {
            100% {left: -1000px;}
        }
    </style>

    <div class="container">

        @if(count($errors))
            <div id="tips_card" class="row">
                <div class="pink accent-2 card lighten-1">
                    <div class="card-content white-text">
                        <span class="card-title">{{ trans('view.room.tips_title') }}</span>
                        <p>
                            <li>{{ $errors->first() }}</li>
                        </p>                    </div>
                </div>
            </div>
            <script type="text/javascript">
                $('#tips_card').on('click',function(){
                    $(this).css('display','none');
                })
            </script>
        @endif

        <div class="row">

        </div>
        <div class="row">
            <div class="">
                <div class="left col">
                    <img class="circle" style="width:100px;height:100px;" src="{{ session('config')->oauth_url.'api/getavatar/'.$value['room_info']->users->remote_userid }}" />
                </div>
                <div class="right col s7">
                    <div class="left col s12" style="display:table;">
                        <div class="word-break" style="font-size:large;vertical-align:middle;display:table-cell;height:100px;">「{{ $value['room_info']->roomintro == ''?trans('view.room.none_room_introduction'):$value['room_info']->roomintro }}」</div>
                    </div>
                </div>
                <div>
                    <div  style="font-weight: bolder;font-size: x-large">{{ $value['room_info']->roomname }}</div>
                </div>
                <div>
                    <div  style="font-size: large">{{ $value['room_info']->users->username }}</div>
                </div>
                <div>
                    <div  style="font-size: large">{{ $value['room_info']->categorys->name }}</div>
                </div>
            </div>
        </div>
        <div class="row">

            <div id="player" class="col s9">
                <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" style="height:100%;width:100%;">

                    <param name="movie" value="/files/public/player.swf">
                    <param name="quality" value="high">
                    <param name="flashvars" value="&src={{ $value['room_info']->rtmpurl }}{{ $value['room_info']->streamkey }}&autoHideControlBar=true&streamType=live&autoPlay=true&verbose=true">
                    <param name="allowfullscreen" value="false">
                    {{--<param name="wmode" value="transparent">--}}
                    <embed style="height:100%;width:100%;" src="/files/public/player.swf" flashvars="&src={{ $value['room_info']->rtmpurl }}{{ $value['room_info']->streamkey }}&autoHideControlBar=true&streamType=live&autoPlay=true&verbose=true" quality="high" allowfullscreen pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" {{--wmode="transparent"--}}></embed>
                </object>
                <div class="center" id="divPlayerSendFake">

                </div>
                <div class="" id="divPlayerSend">
                    @if( $value['room_info']->guestChat == 0 )
                        @if( session()->has('member') )
                            <div class="col s1"></div>
                            <input class="col s8" id="text_chatsend2" type="text" class="" style="margin:2px 0px;">
                            <div class="col s3">
                                <a id="btn_chatsend2" class="btn blue waves-effect waves-light">{{ trans('view.room.chat_send') }}</a>
                                <input type="checkbox" id="danmaku_display2" checked />
                                <label for="danmaku_display2">{{ trans('view.room.danmaku_display') }}</label>
                            </div>
                        @else
                            <div class='col s12 white-text' style="padding:5px;">
                                {{ trans('view.room.chat_must_login') }}
                            </div>
                        @endif
                    @else
                        <div class="col s1"></div>
                        <input class="col s8" id="text_chatsend2" type="text" class="" style="margin:2px 0px;">
                        <div class="col s3">
                            <a id="btn_chatsend2" class="btn blue darken-2 waves-effect waves-light">{{ trans('view.room.chat_send') }}</a>
                            <input type="checkbox" id="danmaku_display2" checked />
                            <label for="danmaku_display2">{{ trans('view.room.danmaku_display') }}</label>
                        </div>
                    @endif
                </div>
                <div id="divDanmaku">
                    <div id="danmakuLineHeight" style="display:none">Line Height</div>
                </div>
                <div id="divDanmakufade">

                </div>
            </div>

            <div id="chatlist" class="col s3" >
                <div class="card">
                    <div class="card-content"style="padding:5px;">
                        <div class="">
                            {{ trans('view.room.online_tips') }}<span id="chatOnline"></span>
                        </div>
                        <div id="chatcontent" class="word-break" style="overflow-y:auto;">

                        </div>
                    </div>
                    @if( $value['room_info']->guestChat == 0 )
                        @if( session()->has('member') )
                            <div class='card-action' style="padding:5px;">
                                <input id="text_chatsend" type="text" class="" style="margin:2px 0px;">
                                <a id="btn_chatsend" class="btn blue waves-effect waves-light">{{ trans('view.room.chat_send') }}</a>
                                <input type="checkbox" id="danmaku_display" checked />
                                <label for="danmaku_display">{{ trans('view.room.danmaku_display') }}</label>
                            </div>
                        @else
                            <div class='card-action' style="padding:5px;">
                                {{ trans('view.room.chat_must_login') }}
                            </div>
                        @endif
                    @else
                        <div class='card-action' style="padding:5px;">
                            <input id="text_chatsend" type="text" class="" style="margin:2px 0px;">
                            <a id="btn_chatsend" class="btn blue darken-2 waves-effect waves-light">{{ trans('view.room.chat_send') }}</a>
                            <input type="checkbox" id="danmaku_display" checked />
                            <label for="danmaku_display">{{ trans('view.room.danmaku_display') }}</label>
                        </div>
                    @endif
                </div>
            </div>

        </div>
        {{--联播房间显示--}}
        @if($value['cooperation_status'])
            @if($value['db_coop_rooms']->count() > 0)
                @foreach($value['db_coop_rooms'] as $db_coop_room)
                    @if($loop->first)
                        <div class="row">
                            <div>
                                <a style="font-size:1.8em;font-weight: bolder;">联播房间</a>
                            </div>
                    @endif
                            <a href="{{ route('room',['room_id' => $db_coop_room->id]) }}">
                                <div class="card col s3" style="padding:0px;">
                                    <div class="card-image">
                                        <img src="{{ $db_coop_room->coverurl }}">
                                        <span class="card-title center" style="width:100%;padding:5px;font-size:1.2em;background: rgba(0,0,0,0.5);">
                                                <img class="hide-on-small-and-down" style="float:left;border-radius:15px;width:30px;height:30px;" src="{{ session('config')->oauth_url.'api/getavatar/'.$db_coop_room->users->remote_userid }}" />
                                                <span style="padding-left:10px;">{{ $db_coop_room->users->username }}</span>
                                        </span>
                                    </div>
                                </div>
                            </a>

                    @if($loop->last)
                        </div>
                    @endif
                @endforeach
            @else
                {{ trans('view.room.can_not_found_cooperate_room') }}
            @endif
        @endif

        <script type="text/javascript">
            $(function(){

                {{--全局变量  global variable--}}
                var pre_windowWidth = 0;
                var pre_windowHeight = 0;
                var isFullScreen = false;
                var isDisplayDanmaku = true;
                var divSendChatTimeout;
                var commentid = 0; //用于辨认弹幕层  To recognize each danmaku div.
                var danmakuLineRight = [];

                setInterval(Resize,100);

                function Resize(isForce){
                    var windowWidth = window.innerWidth;
                    var windowHeight = window.innerHeight;
                    if(pre_windowWidth !=  windowWidth || pre_windowHeight != windowHeight || isForce){
                        {{--重置位置等参数   reset position set etc. of css--}}
                        //$('#player').css('position','');
                        $('#player').css('width','');
                        $('#player').css('left','');
                        $('#player').css('top','');
                        $('#divDanmaku').css('margin-top','');
                        $('#divDanmaku').css('left','');
                        $('#divDanmaku').css('top','');
                        $('#divDanmakufade').css('margin-top','');
                        $('#divDanmakufade').css('left','');
                        $('#divDanmakufade').css('top','');
                        $('#divPlayerSend').css('display','none');
                        $('#divPlayerSend').css('left','');
                        $('#divPlayerSend').css('top','');
                        $('#divPlayerSendFake').css('display','none');
                        $('#divPlayerSendFake').css('left','');
                        $('#divPlayerSendFake').css('top','');

                        var width = $('#player').width();
                        var height = width/16*9;
                        if(isFullScreen){
                            width = windowWidth;
                            height = windowHeight;
                            //$('#player').css('position','absolute');
                            $('#player').css('left',0);
                            $('#player').css('top',0);
                            $('#player').css('width',windowWidth);
                            $('#player').css('height',height);
                            $('#divDanmaku').css('left',0);
                            $('#divDanmaku').css('top',0);
                            $('#divDanmaku').css('width',width);
                            $('#divDanmaku').css('height',height-30);
                            $('#divDanmakufade').css('left',0);
                            $('#divDanmakufade').css('top',0);
                            $('#divDanmakufade').css('width',width);
                            $('#divDanmakufade').css('height',height-30);
                            $('#divPlayerSend').css('left',0);
                            $('#divPlayerSend').css('top',0);
                            $('#divPlayerSend').css('width',width);
                            $('#divPlayerSendFake').css('display','block');
                            $('#divPlayerSendFake').css('left',0);
                            $('#divPlayerSendFake').css('top',0);
                            $('#divPlayerSendFake').css('width',width);
                            $('#divPlayerSendFake').css('height',$('#divPlayerSend').height());
                        }else{
                            $('#player').css('height',height);
                            $('#chatcontent').css('height',height-145);
                            $('#divDanmaku').css('width',width);
                            $('#divDanmaku').css('height',height-30);
                            $('#divDanmaku').css('margin-top',-(height+7));
                            $('#divDanmakufade').css('width',width);
                            $('#divDanmakufade').css('height',height-30);
                            $('#divDanmakufade').css('margin-top',-(height+7));
                        }

                        pre_windowWidth = windowWidth;
                        pre_windowHeight = windowHeight;
                    }
                }

                {{--函数  Functions--}}
                {{--全屏函数  FullScreen Functions--}}
                function launchFullscreen(element) {
                    if(element.requestFullscreen) {
                        element.requestFullscreen();
                    } else if(element.mozRequestFullScreen) {
                        element.mozRequestFullScreen();
                    } else if(element.webkitRequestFullscreen) {
                        element.webkitRequestFullscreen();
                    } else if(element.msRequestFullscreen) {
                        element.msRequestFullscreen();
                    }
                }
                function exitFullscreen() {
                    if(document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if(document.mozCancelFullScreen) {
                        document.mozCancelFullScreen();
                    } else if(document.webkitExitFullscreen) {
                        document.webkitExitFullscreen();
                    }
                }
                {{--生成弹幕层  generate a div of danmaku.--}}
                function danmakuGenerate(commentid, comment){
                    var danmakuLine = danmakuLineSelect();

                    var danmaku = $('<div></div>');
                    danmaku.attr('id','danmaku'+ commentid);
                    danmaku.text(comment);
                    danmaku.css({ 'top':danmakuLine*($("#danmakuLineHeight").height()+15), 'left':$('#player').width() })
                    $('#divDanmaku').append(danmaku);

                    {{--Interval用变量--}}
                    var isupdate = true;
                    var moveInterval = setInterval(function(){
                        if(isupdate){
                            var right = $('#divDanmaku').width() - (parseFloat(danmaku.css('left')) + danmaku.width());
                            danmakuLineRight[danmakuLine] = right;
                        }
                        if(right >= 20){
                            isupdate = false;
                            clearInterval(moveInterval);
                        }
                    },100);
                    danmaku.animate(
                        {'left': -danmaku.width()-20},
                        $('#divDanmaku').width()/474*{{ session('config')->danmakuSpeed }},
                        'linear',
                        function(){
                            danmaku.remove();
                        }
                    );
                }

                {{--弹幕使用行数计算   danmaku line calculate to know which line to use.--}}
                function danmakuLineSelect(){
                    {{--总行数   how much line in can be display in this danmaku div in total.--}}
                    var totalLine = parseInt($("#divDanmaku").height() / ($("#danmakuLineHeight").height()+15));
                    {{--储存当前选择的行数，由0开始。  store a variable of present line, start a 0--}}
                    var persentLine = 0;
                    {{--最终选择行数   store the line finally select.--}}
                    var finalLine = null;

                    for(;persentLine < totalLine;persentLine++){
                        if(typeof(danmakuLineRight[persentLine]) == "undefined" || danmakuLineRight[persentLine] >= 20){
                            finalLine = persentLine;
                            {{--保留位置  keep a position--}}
                            danmakuLineRight[persentLine] = -10000;
                            break;
                        }
                    }

                    if(finalLine == null){
                        {{--没用空行，选最大值一行。  every line are not empty, choose a line which right value is largest.--}}
                        finalLine = danmakuLineRight.indexOf(Math.max.apply(Math,danmakuLineRight));
                        danmakuLineRight[finalLine] = -10000;
                    }

                    return finalLine;
                }



                {{--事件监听   Listener--}}
                {{--设置全屏或者取消全屏   set fullscreen or disable fullscreen--}}
                $("#divDanmakufade").on('dblclick',function(){
                    var player = window.document.getElementById('player');
                    if(isFullScreen){
                        exitFullscreen(player);
                        if(isDisplayDanmaku){
                            $("#danmaku_display").prop("checked",true);
                        }else{
                            $("#danmaku_display").prop("checked",false);
                        }
                        console.info(isDisplayDanmaku);
                        Resize(true);
                        isFullScreen = false;
                    }else{
                        launchFullscreen(player);
                        Resize(true);
                        isFullScreen = true;
                    }
                });
                var hidden = true;
                $("#divPlayerSendFake").mouseover(function(){
                    if(isFullScreen){
                        clearTimeout(divSendChatTimeout);
                        if(isDisplayDanmaku){
                            $("#danmaku_display2").prop("checked",true);
                        }else{
                            $("#danmaku_display2").prop("checked",false);
                        }
                        $("#divPlayerSend").css('display','block');
                        divSendChatTimeout = setTimeout(function(){
                            $("#divPlayerSend").css('display','none');
                        },5000);
                    }
                });
                $("#text_chatsend2").focusin(function(){
                    clearTimeout(divSendChatTimeout);
                });
                $("#text_chatsend2").focusout(function(){
                    divSendChatTimeout = setTimeout(function(){
                        $("#divPlayerSend").css('display','none');
                    },5000);
                });
                $("#danmaku_display").on('click',function(){
                    if($(this).is(':checked')){
                        $("#divDanmaku").css('display','block');
                        isDisplayDanmaku = true;
                    }else{
                        $("#divDanmaku").css('display','none');
                        isDisplayDanmaku = false;
                    }
                })
                $("#danmaku_display2").on('click',function(){
                    if($(this).is(':checked')){
                        $("#divDanmaku").css('display','block');
                        isDisplayDanmaku = true;
                    }else{
                        $("#divDanmaku").css('display','none');
                        isDisplayDanmaku = false;
                    }
                })


                {{--聊天模块  chat moudle--}}
                //focus在聊天框的时候,回车键发送消息
                document.onkeydown=function(event){
                    e = event ? event :(window.event ? window.event : null);
                    if(e.keyCode==13){
                        if(isFullScreen){
                            if($('#text_chatsend2').val()!=""){
                                send($('#text_chatsend2'));
                            }
                        }else{
                            if($('#text_chatsend').val()!=""){
                                send($('#text_chatsend'));
                            }
                        }
                    }
                }
                $("#btn_chatsend").on("click",function(){
                    if($('#text_chatsend').val()!=""){
                        send($('#text_chatsend'));
                    }
                })
                $("#btn_chatsend2").on("click",function(){
                    if($('#text_chatsend2').val()!=""){
                        send($('#text_chatsend2'));
                    }
                })

                var socket = io.connect('{{ session('config')->chatserver }}');

                socket.on('connect',function(data){
                    socket.emit('join',{
                        roomid:"{{ $value['final_roonkey_encry'] }}",
                        username: "{{ $value['room_info']->users->username }}",
                    });
                    $("#chatcontent").append("<a style='color:green;'> 已连接到聊天服务器。<br /> </a>");
                    //获取在线人数
                    socket.emit('getPeopleOnline',{
                        roomid:"{{ $value['final_roonkey_encry'] }}"
                    });
                });

                socket.on('disconnect',function(data){
                    $("#chatcontent").append("<a style='color:red;'> 与聊天服务器断开连接。<br /> </a>");
                });

                //收到在线人数改变
                socket.on('peopleOnline', function(data) {
                    if(data.roomid=="{{ $value['final_roonkey_encry'] }}"){
                        $("#chatOnline").text(data.online);
                    }
                });

                //收到别人发送的消息后，显示消息
                socket.on('broadcast_say', function(data) {
                    if(data.roomid=="{{ $value['final_roonkey_encry'] }}"){
                        console.log(data.username + '说: ' + data.text);
                        /*if(data.textcolor=="#ffffff"){
                            var bgcolor="#000000";
                        }
                        else{
                            var bgcolor="#ffffff";
                        }*/
                        var text = data.text;
                        {{--var splitText = text.split("@");
                        if(text.indexOf("@")==0&&splitText.length>=3){
                            if(splitText[1]==""){
                                var finalsplit="";
                                for(var i = 3;i<splitText.length;i++){
                                    finalsplit = finalsplit +"@"+ splitText[i];
                                    i++;
                                }
                                text = "<a style=\"color:red;\">@"+splitText[1]+"</a>"+splitText[2]+finalsplit;
                            }
                        }--}}
                        text = data.username+":"+text;
                        //显示在消息记录
                        $("#chatcontent").append(text+"<br>");
                        document.getElementById('chatcontent').scrollTop = document.getElementById('chatcontent').scrollHeight;

                        {{--显示成弹幕--}}
                         danmakuGenerate(commentid++,text);
                        /*
                        var o = document.getElementById("danmu");
                         //创建DIV
                         var div = document.createElement("div");
                        div.innerHTML = text;
                        //利用文本长度以及字体大小计算DIV长度，danmu_maxwidth/50计算字体大小，最大长度50；
                        //alert(text.length*12);
                        var danmu_maxwidth=$('#player').width()*0.8;
                        var fontsize=danmu_maxwidth/50;
                        var width=Math.random()*($('#danmu').width()-((text.length*fontsize<danmu_maxwidth)?text.length*fontsize:danmu_maxwidth));
                        var height=Math.random()*($('#danmu').height()-50);
                        div.style.cssText = "word-wrap:break-word;word-break:break-all;font-weight:bold;color:"+data.textcolor+";font-size:"+fontsize+"px;max-height:"+(fontsize+10)+"px;max-width:"+danmu_maxwidth+"px;overflow:hidden;position:absolute;left:"+width+"px;top:"+height+"px;background-color:"+bgcolor+";filter:alpha(opacity=50);-moz-opacity:0.5;opacity:0.5;";
                        o.appendChild(div);
                        setTimeout(function(){o.removeChild(div);},5000);
                        */
                    }
                });

                function send(inputContent) {
                    //获取文本框的文本
                    var text = inputContent.val();
                    /*var textcolor = $("#danmucolor").val();
                    if($("#danmucolor").val()=="#ffffff"){
                        var bgcolor="#000000";
                    }
                    else{
                        var bgcolor="#ffffff";
                    }*/
                    inputContent.val("");
                    //提交一个say事件，服务器收到就会广播
                    socket.emit('say', {
                        //username:"",
                        //bgcolor:bgcolor,
                        //textcolor: textcolor,
                        text: text,
                        roomid: "{{ $value['final_roonkey_encry'] }}"
                    });
                    text = text.replace(/</g,'');
                    text = text.replace(/>/g,'');
                    //text = text.replace(/@/g,'');
                    text = "自己："+text;
                    //显示在消息记录
                    $("#chatcontent").append("<a class='warn'> "+text+"<br /> </a>");
                    document.getElementById('chatcontent').scrollTop = document.getElementById('chatcontent').scrollHeight;

                    danmakuGenerate(commentid++,text);
                    /*
                    var o = document.getElementById("danmu");
                    //创建DIV
                    var div = document.createElement("div");
                    div.innerHTML = text;
                    //利用文本长度以及字体大小计算DIV长度，danmu_maxwidth/50计算字体大小，最大长度50；
                    //alert(text.length*12);
                    var danmu_maxwidth=$('#player').width()*0.8;
                    var fontsize=danmu_maxwidth/40;
                    var divwidth = fontsize*text.length;
                    var width=Math.random()*($('#danmu').width()-((text.length*fontsize<danmu_maxwidth)?text.length*fontsize+20:danmu_maxwidth));
                    var height=Math.random()*($('#danmu').height()-50);
                    div.style.cssText = "word-wrap:break-word;word-break:break-all;border:2px solid red;font-weight:bold;color:"+textcolor+";font-size:"+fontsize+"px;max-height:"+(fontsize*2+10)+"px;max-width:"+danmu_maxwidth+"px;overflow:hidden;position:absolute;left:"+width+"px;top:"+height+"px;background-color:"+bgcolor+";filter:alpha(opacity=50);-moz-opacity:0.5;opacity:0.5;";
                    o.appendChild(div);
                    setTimeout(function(){o.removeChild(div);},5000);
                    console.log(text.length);
                    */
                }

                /*setInterval(function () {
                    danmakuGenerate(commentid++,"自己：11111");
                },50);*/
            });
        </script>

    </div>
@stop