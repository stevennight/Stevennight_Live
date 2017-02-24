<?php
namespace App\Http\Controllers;

use App\Custom\Functions;
use App\Database\Category;
use App\Database\ConfigGlobalWebsite;
use App\Database\Rooms;
use App\Database\Users;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller{

    public function index(){
        Functions::getMyRoomInfo();
        Functions::getConfigGlobalWebsite();

        $room_number = 0;
        $db_category_rooms = [];
        $db_categorys = Category::all();
        foreach( $db_categorys as $db_category ){
            $category_id = $db_category->id;
            $db_rooms = Rooms::all()->where('category','=',$category_id)->where('isindex','=',1);
            $db_category_rooms[$category_id] = $db_rooms;
            if(count($db_rooms)){
                $room_number+=count($db_rooms);
            }
        }

        $value = [
            'roomcover_prefix' => ConfigGlobalWebsite::all()->first()->roomcover_prefix,
            'db_categorys' => $db_categorys,
            'db_category_rooms' => $db_category_rooms,
            'room_number' => $room_number,
        ];

        return view('index',['value' => $value]);
    }

    public function roomedit(Request $request){
        $userid = $request->session()->get('member.userid');
        $username = $request->session()->get('member.username');

        $db_user = Users::where('id','=',$userid)->first();
        if($db_user == null){

        }
        if($request->session()->get('config')->mustVerifyEmail_when_createroom == 1){
            if($db_user->email_active != 1){
                return redirect()->back()->withErrors(['upload_cover_error' => trans('view.form.roomedit.must_verify_email')])->withInput();
            }
        }
        if($request->session()->get('config')->mustVerifyQQ_when_createroom == 1){
            if($db_user->QQ_active != 1){
                return redirect()->back()->withErrors(['upload_cover_error' => trans('view.form.roomedit.must_verify_QQ')])->withInput();
            }
        }

        $this->validate($request,[
            'roomname' => 'required|between:3,15',
            'roomintro' => 'max:70',
            'category' => 'required',
            'rtmpurl' => 'required|regex:/^(rtmp:\/\/)[A-Za-z0-9\.\/:]+\/$/u',
            'streamkey' => 'required|alpha_dash|between:1,20',
            'cover' => '',
            'otherroomkey' => 'max:255',
            'isindex' => '',
        ],[
            'required' => trans('view.form.roomedit.required'),
            'between' => trans('view.form.roomedit.between'),
            'max' => trans('view.form.roomedit.max'),
            'regex' => trans('view.form.roomedit.regex'),
            'alpha_dash' => trans('view.form.roomedit.alpha_dash'),
        ],[
            'roomname' => trans('view.form.roomedit.roomname'),
            'roomintro' => trans('view.form.roomedit.roomintro'),
            'category' => trans('view.form.roomedit.category'),
            'rtmpurl' => trans('view.form.roomedit.rtmpurl'),
            'streamkey' => trans('view.form.roomedit.streamkey'),
            'cover' => trans('view.form.roomedit.cover'),
            'isindex' => trans('view.form.roomedit.isindex'),
        ]);

        //文件上传   upload file.
        $file = $request->file('cover');
        if($file!=null){
            if(!$file->isValid()){
                return redirect()->back()->withErrors(['upload_cover_error' => trans('view.form.roomedit.upload_cover_error')])->withInput();
            }
            $extension = $file->extension();
            if(!in_array($extension,['jpg','png','jpeg','gif'])){
                return redirect()->back()->withErrors(['invalid_extension' => trans('view.form.roomedit.invalid_extension')])->withInput();
            }
            $path = $file->store('room/cover');
        }

        $db_room = Rooms::where('userid','=',$userid)->first();
        if($db_room == null) {
            $db_room = new Rooms();
            $db_room->userid = $userid;
            $db_room->coverurl = $request->session()->get('config')->roomcover_default;
            $db_room->roomkey = $this->generateRoomkey($username);
        }else{
            if($request->has('reroomkey')){
                $db_room->roomkey = $this->generateRoomkey($username);
            }
        }
        $db_room->roomname = $request->get('roomname');
        $db_room->roomintro = $request->get('roomintro');
        $db_room->category = $request->get('category');
        $db_room->rtmpurl = $request->get('rtmpurl');
        $db_room->streamkey = $request->get('streamkey');
        if(isset($path)){
            $db_room->coverurl = $request->session()->get('config')->roomcover_prefix.$path;
        }
        if ($request->has('cooperation')){
            $db_room->cooperation = 1;
        }else{
            $db_room->cooperation = 0;
        }
        if($request->has('otherroomkey')){
            $db_room->otherroomkey = $request->get('otherroomkey');
        }else{
            $db_room->otherroomkey = '';
        }
        $db_room->isindex = $request->has('isindex')?1:0;
        $db_room->guestChat = $request->has('guestChat')?1:0;
        if(!$db_room->save()){
            return redirect()->back()->withErrors(['save_error' => trans('view.form.roomedit.save_error')])->withInput();
        }

        return redirect()->back()->withErrors(['success_save_room_edit' => trans('view.form.roomedit.success_save_room_edit')])->withInput();
    }

    public function login(){
        $db_config_global_website = ConfigGlobalWebsite::all()->first();
        $oauth_url = $db_config_global_website->oauth_url;
        $oauth_client_id = $db_config_global_website->oauth_client_id;
        return redirect($oauth_url.'?clientid='.$oauth_client_id);
    }

    public function logout(Request $request){
        $request->session()->forget('member');
        return redirect()->route('index');
    }

    private function generateRoomkey($username){
        $roomkey = md5($username.time()).Hash::make($username.time());
        $db_room = Rooms::where('roomkey','=',$roomkey)->first();
        if($db_room != null){
            $roomkey = md5($username.time()).Hash::make($username.time());
            $db_room = Rooms::where('roomkey','=',$roomkey)->first();
        }
        return $roomkey;
    }
}