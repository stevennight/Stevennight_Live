<?php
namespace App\Http\Controllers;

use App\Custom\Functions;
use App\Database\ConfigGlobalWebsite;
use App\Database\OauthTokens;
use App\Database\Users;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class OAuthController extends Controller
{

    public function recall(Request $request,$authcode)
    {
        Functions::getMyRoomInfo();
        Functions::getConfigGlobalWebsite();

        //$oauth_url = 'http://account/oauth/';
        $oauth_gettoken_url = session('config')->oauth_url.'api/getaccesstoken';
        //$oauth_url_is_ssl = preg_match('/^(https):\/\/(.)+$/u',$oauth_gettoken_url)?true:false;
        $postdatas = [
            'authcode' => $authcode,
            'secret' => session('config')->oauth_client_secret,
        ];

        //获取Token   get token.
        $client = new Client();
        $res = $client->post($oauth_gettoken_url, ['query' => $postdatas]);
        if($res->getStatusCode() != 200){
            return redirect()->route('index')->withErrors(['oauth_error' => trans('view.oauth.recall.oauth_server_can_not_connect')]);
        }

        //写入Token信息   write the token information to database.
        $token_info = json_decode($res->getBody());
        if($token_info->status == 'error'){
            return redirect()->route('index')->withErrors(['oauth_error' => trans('view.oauth.recall.oauth_server_get_error').$token_info->details ]);
        }
        $access_token = $token_info->access_token;
        $expires_at = $token_info->expires_at;
        $update_token = $token_info->update_token;
        $update_token_expires_at = $token_info->update_token_expires_at;
        $db_oauthtokens = new OauthTokens();
        $db_oauthtokens->userid = 0;
        $db_oauthtokens->session_id = $request->session()->getId();
        $db_oauthtokens->access_token = $access_token;
        $db_oauthtokens->expires_at = $expires_at;
        $db_oauthtokens->update_token = $update_token;
        $db_oauthtokens->update_token_expires_at = $update_token_expires_at;
        if(!$db_oauthtokens->save()){
            return redirect()->route('index')->withErrors(['oauth_error' => trans('view.oauth.recall.oauth_token_can_not_save')]);
        }

        //从账号中心更新用户信息到本地  update user information to local from account center.
        $result = $this->userInfoUpdate($request,$access_token,$update_token);
        if(!$result['status']){
            return redirect()->route('index')->withErrors($result['details']);
        }
        $db_user = $result['db_user'];

        //是否允许邮件、QQ未激活用户登录
        if($db_user->baned == 1){
            return redirect()->route('index')->withErrors(['oauth_error' => trans('view.oauth.recall.account_is_baned')]);
        }
        if($request->session()->get('config')->mustVerifyEmail == 1){
            if($db_user->email_active != 1){
                return redirect()->route('index')->withErrors(['oauth_error' => trans('view.oauth.recall.must_verify_email')]);
            }
        }
        if($request->session()->get('config')->mustVerifyQQ == 1){
            if($db_user->QQ_active != 1){
                return redirect()->route('index')->withErrors(['oauth_error' => trans('view.oauth.recall.must_verify_qq')]);
            }
        }

        $db_user->last_login = time();
        $db_user->save();

        //成功登录  login successful.
        Functions::refreshUserInfoIntoSession($db_user);
        return redirect()->route('index');

        /*$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$oauth_gettoken_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $oauth_url_is_ssl); // 信任任何证书
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdatas);
        $output = curl_exec($ch);
        $errorno = curl_errno($ch);
        if ($errorno) {
            return dd(array('errorno' => false, 'errmsg' => $errorno));
        }
        curl_close($ch);
            return print($output);*/
    }

    public function refreshUserInfo(Request $request){
        $userid = $request->session()->get('member.userid');
        $sessionid = $request->session()->getId();

        $db_oauth_token = OauthTokens::where('userid','=',$userid)->where('session_id','=',$sessionid)->first();
        if($db_oauth_token == null){
            $data = [
                'status' => 'error',
                'details' => trans('view.oauth.refreshinfo.not_found_token'),
            ];
            return response()->json($data);
        }

        $result = $this->userInfoUpdate($request,$db_oauth_token->access_token,$db_oauth_token->update_token);
        if(!$result['status']){
            $data = [
                'status' => 'error',
                'details' => $result['details'],
            ];
            return response()->json($data);
        }

        //更新成功。
        $db_user = $result['db_user'];
        Functions::refreshUserInfoIntoSession($db_user);
        $data = [
            'status' => 'success',
        ];
        return response()->json($data);
    }

    public function userInfoUpdate(Request $request,$access_token,$update_token){

        //获取api那边用户的信息   get the user information from remote server.
        $oauth_getuser_url = session('config')->oauth_url.'api/getuserinfo';
        $client_secret = session('config')->oauth_client_secret;
        $postdatas = [
            'client_secret' => $client_secret,
            'access_token' => $access_token,
            'update_token' => $update_token,
        ];

        $client = new Client();
        $res = $client->post($oauth_getuser_url, ['query' => $postdatas]);
        if($res->getStatusCode() != 200){
            return [
                'status' => false,
                'details' => ['oauth_error' => trans('view.oauth.recall.oauth_server_can_not_connect')],
            ];
        }
        $response = json_decode($res->getBody());

        if($response->status == 'error'){
            return [
                'status' => false,
                'details' => $response->details,
            ];
        }

        $db_user = Users::where('remote_userid','=',$response->details->userid)->first();
        if($db_user == null){
            $db_user = new Users();
            $db_user->baned = 0;
            $db_user->last_login = 0;
        }
        if($response->details->baned == 1){
            $db_user->baned = $response->details->baned;
        }
        $db_user->remote_userid = $response->details->userid;
        $db_user->username = $response->details->username;
        $db_user->group = $response->details->group;
        $db_user->email = $response->details->email;
        $db_user->email_active = $response->details->email_active;
        $db_user->QQ = $response->details->QQ;
        $db_user->QQ_active = $response->details->QQ_active;
        $db_user->reg_address = $response->details->reg_address;
        if(!$db_user->save()){
            return [
                'status' => false,
                'details' => ['oauth_error' => trans('view.oauth.recall.fail_to_save_user_into_database')],
            ];
        }

        $request->session()->regenerate();

        $db_oauth_tokens = OauthTokens::where('access_token','=',$access_token)->where('update_token','=',$update_token)->first();
        if($db_oauth_tokens == null){
            return [
                'status' => false,
                'details' => ['oauth_error' => trans('view.oauth.recall.can_not_found_token').'-'.$access_token.'-'.$update_token],
            ];
        }
        if($response->token->status == 'renew'){
            $db_oauth_tokens->access_token = $response->token->access_token;
            $db_oauth_tokens->expires_at = $response->token->expires_at;
            $db_oauth_tokens->update_token = $response->token->update_token;
            $db_oauth_tokens->update_token_expires_at = $response->token->update_token_expires_at;
        }
        $db_oauth_tokens->session_id = $request->session()->getId();
        $db_oauth_tokens->userid = $db_user->id;
        if(!$db_oauth_tokens->save()){
            return [
                'status' => false,
                'details' => ['oauth_error' => trans('view.oauth.recall.fail_to_save_renew_token')],
            ];
        }

        return [
            'status' => true,
            'db_user' => $db_user,
        ];
    }

}