<?php
namespace App\Http\Controllers;

use App\Custom\Functions;
use App\Database\Category;
use App\Database\Rooms;
use Illuminate\Http\Request;

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
            'final_roomkey_encry' => Functions::getEncryRoomkey($final_roomkey),
            'cooperation_status' => $cooperation_status,
            'db_coop_rooms' => $db_coop_rooms,
            'db_categorys' => $db_categorys,
        ];
        return view('room',['value' => $value]);
    }

    public function roomOwner(Request $request,$roomid){
        Functions::getMyRoomInfo();
        Functions::getConfigGlobalWebsite();

        $db_room = Rooms::where('id','=',$roomid)->first();
        if($db_room == null){
            return redirect()->route('index')->withErrors(['can_not_found_room' => trans('view.room.can_not_found_room')]);
        }
        if($db_room->userid != $request->session()->get('member.userid')){
            return redirect()->route('index')->withErrors(['can_not_found_room' => trans('view.room.not_room_owner')]);
        }

        $final_roomkey =  Functions::getEncryRoomkey(Functions::getRoomkey($roomid));

        $value = [
            'final_roomkey_encry' => $final_roomkey,
            'db_room' => $db_room,
        ];
        return view('room_owner',['value' => $value]);
    }

    /*  独立的房间编辑页面     a page what is editing room information.
     * */
    public function showRoomEditSingle(Request $request){

        Functions::getMyRoomInfo();
        Functions::getConfigGlobalWebsite();


        $value = [
            'db_categorys' => Category::all(),
        ];
        return view('room_edit_single',['value' => $value]);

    }

    public function toggleIndex(Request $request,$roomid){
        Functions::getMyRoomInfo();
        Functions::getConfigGlobalWebsite();

        if(!$request->session()->has('member')){
            return response()->json(['status'=>'error','code'=>'-2','reason'=>"must login."]);
        }

        $db_room = Rooms::where('id','=',$roomid)->first();
        if($db_room == null){
            return response()->json(['status'=>'error','code'=>'-1','reason'=>"no room."]);
        }
        if($db_room->userid != $request->session()->get('member.userid')){
            return response()->json(['status'=>'error','code'=>'-2','reason'=>"not owner."]);
        }

        if($db_room->isindex == 0){
            $db_room->isindex = 1;
        }else{
            $db_room->isindex = 0;
        }
        if(!$db_room->save()){
            return response()->json(['status'=>'error','code'=>'-3','reason'=>"save failed."]);
        }

        return response()->json(['status'=>'success','code'=>$db_room->isindex,'reason'=>"success."]);
    }

}