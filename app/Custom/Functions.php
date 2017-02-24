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
            return;
        }
        $userid = session('member.userid');
        $db_rooms = Rooms::where('userid','=',$userid)->first();
        if($db_rooms == null){
            return;
        }

        $data = [
            'roomname' => $db_rooms->roomname,
            'roomintro' => $db_rooms->roomintro,
            'catagory' => $db_rooms->category,
            'rtmpurl' => $db_rooms->rtmpurl,
            'streamkey' => $db_rooms->streamkey,
            'coverurl' => $db_rooms->coverurl,
            'roomkey' => $db_rooms->roomkey,
            'cooperation' => $db_rooms->cooperation,
            'otherroomkey' => $db_rooms->otherroomkey,
            'isindex' => $db_rooms->isindex,
            'guestChat' => $db_rooms->guestChat,
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

}