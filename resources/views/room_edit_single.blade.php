<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
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
    <div class="container">
        @if(count($errors))
            <div id="tips_card" class="row">
                <div class="pink accent-2 card">
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
        <form class="col s12" method="post" enctype="multipart/form-data" action="{{ route('roomedit') }}">
            {{ csrf_field() }}
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


        <div class="fixed-action-btn toolbar">
            <a class="btn-floating btn-large pulse red">
                <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
                <li class="waves-effect waves-light"><a href="{{ route('roomOwner',['roomid'=>session('room.roomid')]) }}"><i class="material-icons">chat_bubble</i></a></li>
                <!--<li class="waves-effect waves-light"><a href="InfoControl.html"><i class="material-icons">settings</i></a></li>-->
            </ul>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('select').material_select();
        });

        //更新用户信息
        $('#btn_refresh_userinfo').on('click', function () {
            $.post('http://live.sise.sevaft.com/oauth/user/refresh'), {'_token': 'nLxdYn0YAXK3TT9bqrXsAKOZgFClF2eE9jCZgCjj'}, function (result) {
                var div_tips = $('#form_tips');
                div_tips.css('display', 'block');
                div_tips.html(result);
            }
        });

    </script>
</body>
</html>