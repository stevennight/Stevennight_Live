<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>@yield('page_title')--{{ session('config')->name }}</title>
    <!--Import Google Icon Font-->
    <!--<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">-->
    <link href="/css/icon.css" rel="stylesheet">
    <!--Import materialize.css-->
    <link rel="stylesheet" href="/css/materialize.min.css" />
    <!--Import Jquery-->
    <script type="text/javascript" src="/js/jquery-3.1.1.min.js"></script>
    <!--Import materialize.js-->
    <script type="text/javascript" src="/js/materialize.min.js"></script>
    <script type="text/javascript" src="/js/socket.io-1.3.7.js"></script>
    <!--这是固定footer的样式-->
    <style type="text/css">
        #body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        body{
            min-width: 720px;
        }
        .word-break{
            word-break: break-all;
        }

        ::-webkit-scrollbar-thumb {
            cursor: pointer!important;
            -webkit-border-radius: 10px;
            -webkit-border-radius: 0px;
            -moz-border-radius: 0px;
            -khtml-border-radius: 0px;
            border-radius: 0px;
            background: #308bc8;
            -webkit-box-shadow: inset 0 0 6px #0288d1 ;
        }
        ::-webkit-scrollbar-track {
            border-left: 1px solid #0b242f;
        }
        ::-webkit-scrollbar {
            width: 10px;
            background: #0b242f;
        }

        #main {
            flex: 1 0 auto;
        }
    </style>
</head>

<body class="blue lighten-5" id="body">
    <nav class="blue darken-2 z-depth-4">
        <div class="nav-wrapper">
            <a href="#" class="brand-logo"></a>
            <ul class="left">

                <li>
                    <a href="/" class="waves-effect waves-light">{{ session('config')->name }}</a>
                </li>
            </ul>

            <ul class="right">

                @if(session()->has('member'))
                    <li>
                        <a class="waves-effect waves-light">{{ session('member.username') }}</a>
                    </li>
                    <li>
                        <a href="#" data-activates="slide-out" id="button-collapse">{{ trans('view.header.edit') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('logout') }}" class="waves-effect waves-light">{{ trans('view.header.logout') }}</a>
                    </li>
                    @else
                    <li>
                        <a href="#" class="waves-effect waves-light">{{ trans('view.header.guest') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('login') }}" class="waves-effect waves-light">{{ trans('view.header.login') }}</a>
                    </li>
                    @endif
            </ul>

        </div>
    </nav>
    <div class="" id="main">
        @section('body')
            @if(session()->has('member'))
                <div>
                    <ul id="slide-out" class="side-nav">
                        <li>
                            <div class="userView">
                                <div class="background">
                                    <img src="/img/SideNav.jpg">
                                </div>
                                <div class="row">
                                    <a style="font-weight: bolder;"><img class="circle" src="{{ session('config')->oauth_url.'api/getavatar/'.session('member')['remote_userid'] }}" /> {{session('member.username')}}</a>
                                </div>
                                <div class="container">
                                    <div class="row">
                                        <form class="col s12" method="post" enctype="multipart/form-data" action="{{ route('roomedit') }}">
                                            {{ csrf_field() }}
                                            <div class="row">

                                            </div>
                                            @if(session()->has('room'))
                                            <div class="row">
                                                <div class="col s12">
                                                    <a id="form_tips" style="display:none;"></a>
                                                    <a id='btn_enter_room_owner' class="btn waves-effect waves-light" href="{{ route('roomOwner',['roomid'=>session('room.roomid')]) }}" >{{ trans('view.edit.enter_room_owner') }}</a>
                                                </div>
                                            </div>
                                            @else
                                                <div class="row">
                                                    <div class="col s12">
                                                        <a id="form_tips" style="display:none;"></a>
                                                        <a id='btn_refresh_userinfo' class="btn waves-effect waves-light" >{{ trans('view.edit.renew_user_info') }}</a>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="row">

                                            </div>
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <input id="last_name" name="roomname" type="text" class="validate" value="{{ old('roomname')?old('roomname'):(session()->has('room')?session('room.roomname'):'') }}">
                                                    <label for="last_name">{{ trans('view.edit.roomname') }}</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <textarea id="textarea1" name="roomintro" class="materialize-textarea">{{ old('roomintro')?old('roomintro'):(session()->has('room')?session('room.roomintro'):'') }}</textarea>
                                                    <label for="textarea1">{{ trans('view.edit.roomintro') }}</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <select name="category">
                                                        <option value="" disabled {{ session()->has('room')?"":"selected" }}>{{ trans('view.edit.category') }}</option>
                                                        @forelse($value['db_categorys'] as $db_category)
                                                            <option value="{{ $db_category->id }}" {{ session()->has('room')?(session('room.catagory') == $db_category->id?'selected':''):'' }}>{{ $db_category->name }}</option>
                                                        @empty
                                                            <option value="" disabled>{{ trans('view.form.roomedit.no_category') }}</option>
                                                        @endforelse
                                                    </select>
                                                </div>
                                            </div>

                                            <!--rtmp 地址-->
                                            <div class="row">
                                                <input type="checkbox" id="openrtmp" name="openrtmp" {{ old('openrtmp')?'checked':(session()->has('room')?(session('room.openrtmp')==1?'checked':''):'') }} />
                                                <label for="openrtmp">{{ trans('view.edit.openrtmp') }}</label>
                                            </div>
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <input type="text" class="validate" name="rtmpurl" value="{{ old('rtmpurl')?old('rtmpurl'):(session()->has('room')?session('room.rtmpurl'):'') }}">
                                                    <label for="last_name">{{ trans('view.edit.rtmpurl') }}</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <input type="text" class="validate" name="streamkey" value="{{ old('streamkey')?old('streamkey'):(session()->has('room')?session('room.streamkey'):'') }}">
                                                    <label for="last_name">{{ trans('view.edit.streamkey') }}</label>
                                                </div>
                                            </div>
                                            <!--hls地址-->
                                            <div class="row">
                                                <input type="checkbox" id="openhls" name="openhls" {{ old('openhls')?'checked':(session()->has('room')?(session('room.openhls')==1?'checked':''):'') }} />
                                                <label for="openhls">{{ trans('view.edit.openhls') }}</label>
                                            </div>
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <input type="text" id="hlsurl" class="validate" name="hlsurl" value="{{ old('hlsurl')?old('hlsurl'):(session()->has('room')?session('room.hlsurl'):'') }}">
                                                    <label for="hlsurl">{{ trans('view.edit.hlsurl') }}</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <input type="checkbox" id="rtmpfirst" name="rtmpfirst" {{ old('rtmpfirst')?'checked':(session()->has('room')?(session('room.rtmpfirst')==1?'checked':''):'') }} />
                                                <label for="rtmpfirst">{{ trans('view.edit.rtmpfirst') }}</label>
                                            </div>


                                            <div class="row">

                                                @if(session()->has('room'))
                                                    <img src="{{ session('room.coverurl')}}" />
                                                @endif

                                                <div class="file-field input-field">
                                                    <div class="btn">
                                                        <span>{{ trans('view.edit.cover_choose') }}</span>
                                                        <input type="file" name="cover" value="{{ old('cover') }}">
                                                    </div>
                                                    <div class="file-path-wrapper">
                                                        <input class="file-path validate" type="text">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <input type="text" class="validate" name="roomkey" value="{{ session()->has('room')?session('room.roomkey'):'' }}">
                                                    <label for="last_name">{{ trans('view.edit.roomkey') }}</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <input type="checkbox" id="test6" name="reroomkey" />
                                                <label for="test6">{{ trans('view.edit.reroomkey') }}</label>
                                            </div>

                                            <div class="row">
                                                <input type="checkbox" id="test7" name="cooperation" {{ old('cooperation')?'checked':(session()->has('room')?(session('room.cooperation')==1?'checked':''):'') }} />
                                                <label for="test7">{{ trans('view.edit.cooperation') }}</label>
                                            </div>
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <input type="text" class="validate" name="otherroomkey" value="{{ old('otherroomkey')?old('otherroomkey'):(session()->has('room')?session('room.otherroomkey'):'') }}">
                                                    <label for="last_name">{{ trans('view.edit.otherroomkey') }}</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <a>{{ trans('view.edit.cooperation_details') }}</a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <input type="checkbox" id="test5" name="isindex" {{ old('isindex')?'checked':(session()->has('room')?(session('room.isindex')==1?'checked':''):'') }} />
                                                <label for="test5">{{ trans('view.edit.isindex') }}</label>
                                            </div>
                                            <div class="row">
                                                <input type="checkbox" id="guestChat" name="guestChat" {{ old('guestChat')?'checked':(session()->has('room')?(session('room.guestChat')==1?'checked':''):'') }} />
                                                <label for="guestChat">{{ trans('view.edit.guestChat') }}</label>
                                            </div>

                                            <div class="row">
                                                <div class="col l12">
                                                    <button class="btn waves-effect waves-light" type="submit" name="action">{{ trans('view.edit.save') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                @endif
            @show
    </div>
    <footer class="blue darken-2 page-footer">
        <div class="container">
            <div class="row">
                <div class="col l6 s12">
                    <h5 class="white-text">{{ trans('view.footer.thanks') }}</h5>
                    <p class="grey-text text-lighten-4">给予我帮助以及建议的各位。</p>
                    <p class="grey-text text-lighten-4">Everyone who give me some advices and helps.</p>
                </div>
                <div class="col l4 offset-l2 s12">
                    <h5 class="white-text">{{ trans('view.footer.links') }}</h5>
                    <ul>
                        @forelse(session('links') as $link)
                            <li>
                                <a class="grey-text text-lighten-3" href="{{ $link->link }}">{{ $link->name }}</a>
                            </li>
                        @empty
                            <li>
                                <a class="grey-text text-lighten-3" >{{ trans('view.footer.without_link') }}</a>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-copyright">
            <div class="container">
                &copy; 2016-2017 Stevennight. Design By Xat.
                <a class="grey-text text-lighten-4 right" href="https://github.com/stevennight">Github</a>
            </div>
        </div>
    </footer>
    <script type="text/javascript">
        $('#button-collapse').sideNav({
            menuWidth: 480, // Default is 240
            edge: 'right', // Choose the horizontal origin
            closeOnClick: true, // Closes side-nav on <a> clicks, useful for Angular/Meteor
            draggable: true // Choose whether you can drag to open on touch screens
        });
        $(document).ready(function() {
            $('select').material_select();
        });

        //更新用户信息
        $('#btn_refresh_userinfo').on('click',function(){
           $.post('{{ route('refreshUserInfo') }}'),{ '_token':'{{ csrf_token() }}' },function(result){
                var div_tips = $('#form_tips');
                div_tips.css('display','block');
                div_tips.html(result);
           }
        });
    </script>
</body>

</html>