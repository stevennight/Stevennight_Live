@extends('layouts.global',['value' => $value])

@section('page_title')
    {{ trans('view.index.index_page_title') }}
    @stop

@section('body')
    @parent
    <script type="text/javascript">
        var socket = io.connect('{{ session('config')->chatserver }}');
    </script>

    <div class="container">
        <div class="row">
            <div class="col s12">
                <div class="card blue">
                    <div class="card-content white-text">
                        <span class="card-title">{{ trans('view.index.index_card_title') }}</span>
                        <p>
                            @if(count($errors))
                                <li>{{ $errors->first() }}</li>
                                @else
                                {{ trans('view.index.index_card_tips') }}
                                @endif
                        </p>
                    </div>
                    <div class="card-action">

                        @if( !session()->has('member') )
                            <a href="{{ route('login') }}" class="btn red lighten-1 waves-effect waves-light">{{ trans('view.index.index_card_login_btn') }}</a>
                            @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    @forelse( $value['db_categorys'] as $db_category )
        @forelse( $value['db_category_rooms'][$db_category->id] as $db_category_room )
            @if($loop->first)
                <div class="container">
                    <div class="row">
                        <div class="col s12">
                            <h4>{{ $db_category->name }}</h4>
                        </div>
                    </div>
                    <div class="progress">
                        <div class="blue indeterminate"></div>
                    </div>
            @endif

            @if( $loop->index%3 == 0 )
                    <div class="row">
            @endif
                        <a href="{{ route('room',['room_id' => $db_category_room->id]) }}">
                            <div id="room{{ $db_category_room->id }}" class="col push-s0 m4" rel="{{ \App\Custom\Functions::getEncryRoomkey(\App\Custom\Functions::getRoomkey($db_category_room->id)) }}">
                                <div class="card large hoverable">
                                    <div class="card-image">
                                        <img src="{{ $db_category_room->coverurl }}">
                                        <span class="card-title center" style="width:100%;padding:5px;font-size:1.2em;background: rgba(0,0,0,0.5);">
                                            <img class="hide-on-small-and-down" style="float:left;border-radius:15px;width:30px;height:30px;" src="{{ session('config')->oauth_url.'api/getavatar/'.$db_category_room->users->remote_userid }}" />
                                            <span style="padding-left:10px;">{{ $db_category_room->users->username }}</span>
                                        </span>
                                    </div>
                                    <div class="card-content" style="word-break: break-all">
                                        <span>{{ $db_category_room->roomintro }}</span>
                                    </div>
                                    <div class="card-action">
                                        <span class='blue-text text-darken-3' style="font-weight: bolder;" href="#">{{ $db_category_room->roomname }}</span>
                                        <span class="new badge {{ ($loop->index%3 == 0)?'blue':(($loop->index%3==1)?'red':'green') }}" data-badge-caption="Online" id="chatOnline{{$db_category_room->id}}">1</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <script type="text/javascript">
                            socket.emit('getPeopleOnline',{
                                roomid: $('#room{{ $db_category_room->id }}').attr('rel')
                            });
                            socket.on('peopleOnline', function(data) {
                                if(data.roomid==$('#room{{ $db_category_room->id }}').attr('rel')){
                                    $("#chatOnline{{$db_category_room->id}}").text(data.online);
                                }
                            });
                        </script>

            @if( $loop->index%3 == 2 || $loop->last)
                    </div>
            @endif


            @if($loop->last)
                </div>
            @endif
        @empty

        @endforelse
    @empty
        <div class="container">{{ trans('view.index.without_category') }}</div>
    @endforelse

    @if($value['room_number'] == 0)
        <div class="container">{{ trans('view.index.without_room_on_live') }}</div>
    @endif

@stop