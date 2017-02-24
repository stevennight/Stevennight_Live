<?php
namespace App\Http\Controllers;

use App\Custom\Functions;
use App\Database\Category;
use App\Database\Rooms;

class RoomController extends Controller{

    public function showRoom($roomid){

        Functions::getMyRoomInfo();
        Functions::getConfigGlobalWebsite();

        $db_room = Rooms::where('id','=',$roomid)->first();
        if($db_room == null){
            return redirect()->route('index')->withErrors(['can_not_found_room' => trans('view.room.can_not_found_room')]);
        }
        $db_categorys = Category::all();

        //计算使用自己的房间密钥还是合作的他人的密钥
        $final_roomkey = Functions::getRoomkey($roomid);
        $cooperation_status = false;
        if($final_roomkey != $db_room->roomkey){
            $cooperation_status = true;
        }elseif ($db_room->cooperation == 1){
            $cooperation_status = true;
        }

        $db_coop_rooms = null;
        if($cooperation_status){
            $db_coop_rooms = Rooms::where('otherroomkey','=',$final_roomkey)->where('id','!=',$roomid)->where('isindex','=',1)->orWhere('roomkey','=',$final_roomkey)->where('id','!=',$roomid)->where('isindex','=',1)->get();
            //$db_coop_rooms = Rooms::where('otherroomkey','=',$final_roomkey)->orWhere('roomkey','=',$final_roomkey)->get();
        }

        $value = [
            'room_info' => $db_room,
            'final_roomkey' => $final_roomkey,
            'final_roonkey_encry' => Functions::getEncryRoomkey($final_roomkey),
            'cooperation_status' => $cooperation_status,
            'db_coop_rooms' => $db_coop_rooms,
            'db_categorys' => $db_categorys,
        ];
        return view('room',['value' => $value]);
    }

}