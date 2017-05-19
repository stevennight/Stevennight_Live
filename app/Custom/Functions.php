<?php
namespace App\Custom;

use App\Custom;
use App\Database\ConfigGlobalWebsite;
use App\Database\Links;
use App\Database\Rooms;
use App\Database\Users;

class Functions{

    public static function refreshUserInfoIntoSession($db_user){

        if($db_user == null){
            session()->forget('member');
            return;
        }

        if($db_user->baned == 1){
            session()->forget('member');
            return;
        }

        $member = [
            'userid' => $db_user->id,
            'remote_userid' => $db_user->remote_userid,
            'username' => $db_user->username,
            'group' => $db_user->group,
            'baned' => $db_user->baned,
            'email' => $db_user->email,
            'email_active' => $db_user->email_active,
            'QQ' => $db_user->QQ,
            'QQ_active' => $db_user->QQ_active,
            'reg_address' => $db_user->reg_address,
            'created_at' => $db_user->created_at,
            'updated_at' => $db_user->updated_at,
            'last_login' => $db_user->last_login,
        ];

        session()->put('member',$member);
    }

    public static function getMyRoomInfo(){

        if(!session()->has('member')){
            if(session()->has('room')){
                session()->forget('room');
            }
            return;
        }
        $userid = session('member.userid');
        $db_room = Rooms::where('userid','=',$userid)->first();
        if($db_room == null){
            if(session()->has('room')){
                session()->forget('room');
            }
            return;
        }

        $data = [
            'roomid' => $db_room->id,
            'roomname' => $db_room->roomname,
            'roomintro' => $db_room->roomintro,
            'catagory' => $db_room->category,
            'openrtmp' => $db_room->openrtmp,
            'rtmpurl' => $db_room->rtmpurl,
            'streamkey' => $db_room->streamkey,
            'openhls' => $db_room->openhls,
            'hlsurl' => $db_room->hlsurl,
            'rtmpfirst' => $db_room->rtmpfirst,
            'coverurl' => $db_room->coverurl,
            'roomkey' => $db_room->roomkey,
            'cooperation' => $db_room->cooperation,
            'otherroomkey' => $db_room->otherroomkey,
            'isindex' => $db_room->isindex,
            'guestChat' => $db_room->guestChat,
        ];
        session()->put('room',$data);
        return;
    }

    public static function getConfigGlobalWebsite(){
        try{
            $config = ConfigGlobalWebsite::all()->first();
        }catch( \Exception $e){
            return false;
        }
        if($config == null){
            return false;
        }
        session()->put('config',$config);

        $db_links = Links::all();
        session()->put('links',$db_links);
        return true;
    }

    //房间密钥获取   get the room key
    public static function getRoomkey($roomid){
        $db_room = Rooms::where('id','=',$roomid)->first();
        if($db_room == null){
            return;
        }

        $other_room_key = $db_room->otherroomkey;
        if($other_room_key == ''){
            return $db_room->roomkey;
        }

        $db_other_room = Rooms::where('roomkey','=',$other_room_key)->first();
        if($db_other_room == null){
            return $db_room->roomkey;
        }
        if($db_other_room->cooperation == 0){
            return $db_room->roomkey;
        }

        return $db_other_room->roomkey;
    }
    //加密的房间密钥获取  get the encryed room kry.
    public static function getEncryRoomkey($roomkey){
        return md5($roomkey).substr($roomkey,40,10).substr($roomkey,10,10);
    }

    //判断html 播放器视频类型。
    public static function getHtml5PlayerType($url){
        if(preg_match('/\.m3u8$/is',$url)){
            return "m3u8";
        }else if(preg_match('/\.flv$/is',$url)){
            return "flv";
        }
        return "auto";
    }
}

