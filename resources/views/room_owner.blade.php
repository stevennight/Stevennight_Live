<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title></title>
    <link href="/css/icon.css" rel="stylesheet">
    <!--Import materialize.css-->
    <link rel="stylesheet" href="/css/materialize.min.css" />
    <!--Import Jquery-->
    <script type="text/javascript" src="/js/jquery-3.1.1.min.js"></script>
    <!--Import materialize.js-->
    <script type="text/javascript" src="/js/materialize.min.js"></script>
    <script type="text/javascript" src="/js/socket.io-1.3.7.js"></script>
    <style>
        *{
            font-size: 1.5rem
        }
    </style>
</head>
<body>
    <div class="row">
        <div class="col s12 m12 l12">
            <div class="card">

                <div class="card-content" style="height: 760px;">
                    <div class="">
                        {{ trans('view.room.online_tips') }}<span id="chatOnline">1</span>
                    </div>
                    <div id="chatcontent" class="word-break" style="height:615px;overflow-y:auto;">
                </div>
                <div class="card-action">
                    <input id="text_chatsend" type="text" placeholder="在这里输入文字">
                    <!--<a id="btn_chatsend" class="waves-effect waves-light btn-floating right"><i class="material-icons">send</i></a>-->
                </div>
            </div>
        </div>
        <div class="fixed-action-btn toolbar">
            <a class="btn-floating btn-large pulse red">
                <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
                <li id="btn_toggle_index" class="waves-effect waves-light"><a href="#!" id="switch"><i class="material-icons">power_settings_new</i></a></li>
                <li class="waves-effect waves-light"><a href="{{ route('roomEditSingle') }}"><i class="material-icons">settings</i></a></li>
            </ul>
        </div>
    </div>


    <script>
        {{--函数区域 functions--}}
        $("#btn_toggle_index").on("click",function(){
            $.post( "{{ route('roomToggleIndex',[ 'roomid'=> $value['db_room']->id ]) }}" ,{ '_token':'{{ csrf_token() }}' },function(result){
                var info;
                if(result['code']==0){
                    info = "已关闭首页显示房间";
                }else if(result['code']==1){
                    info = "已开启首页显示房间";
                }else{
                    info = "错误！" + result['code'] + ":" + result['reason'];
                }

                Materialize.toast(info,4000,'');
            });
        });

        {{--聊天模块  chat moudle--}}
        //focus在聊天框的时候,回车键发送消息
        var btn_send_element = document.getElementById('text_chatsend');
        btn_send_element.onkeydown=function(event){
            e = event ? event :(window.event ? window.event : null);
            if(e.keyCode==13){
                if($('#text_chatsend').val()!=""){
                    send($('#text_chatsend'));
                }
            }
        }
        $("#btn_chatsend").on("click",function(){
            if($('#text_chatsend').val()!=""){
                send($('#text_chatsend'));
            }
        });

        var socket = io.connect('{{ session('config')->chatserver }}');

        socket.on('connect',function(data){
            socket.emit('join',{
                roomid:"{{ $value['final_roomkey_encry'] }}",
                username: "{{ session()->has('member')?session()->get('member.username'):'guest'}}",
            });
            $("#chatcontent").append("<a style='color:green;'> 已连接到聊天服务器。<br /> </a>");
            //获取在线人数
            socket.emit('getPeopleOnline',{
                roomid:"{{ $value['final_roomkey_encry'] }}"
            });
        });

        socket.on('disconnect',function(data){
            $("#chatcontent").append("<a style='color:red;'> 与聊天服务器断开连接。<br /> </a>");
        });

        //收到在线人数改变
        socket.on('peopleOnline', function(data) {
            if(data.roomid=="{{ $value['final_roomkey_encry'] }}"){
                $("#chatOnline").text(data.online);
            }
        });

        //收到别人发送的消息后，显示消息
        socket.on('broadcast_say', function(data) {
            if(data.roomid=="{{ $value['final_roomkey_encry'] }}"){
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
                roomid: "{{ $value['final_roomkey_encry'] }}"
            });
            text = text.replace(/</g,'');
            text = text.replace(/>/g,'');
            //text = text.replace(/@/g,'');
            text = "自己："+text;
            //显示在消息记录
            $("#chatcontent").append("<a class='warn'> "+text+"<br /> </a>");
            document.getElementById('chatcontent').scrollTop = document.getElementById('chatcontent').scrollHeight;

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

    </script>
</body>
</html>