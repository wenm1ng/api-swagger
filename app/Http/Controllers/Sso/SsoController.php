<?php

namespace App\Http\Controllers\Sso;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Collection;
class SsoController extends Controller
{
	public function getErpStatus(Request $request){
		if(empty($request->member_id)){
			return response()->json(['error_code'=>1,'error_msg'=>'用户id参数缺失！','data'=>array()]);exit;
		}
		if(empty($request->ticket)){
			return response()->json(['error_code'=>1,'error_msg'=>'ticket参数缺失！','data'=>array()]);exit;
		}

		$member_id = htmlspecialchars(trim($request->member_id));
		$ticket = htmlspecialchars(trim($request->ticket));
		
		if(Redis::exists('ybf:family:login:'.$member_id)){
			if(json_decode(Redis::get('ybf:family:login:'.$member_id))->ticket == $ticket){
				return response()->json(['error_code'=>0,'error_msg'=>'登录成功','data'=>array('url'=>'http://erp.ybf-china.com/login.php?member_id='.$member_id.'&ticket='.$ticket)]);
			}else{
				return response()->json(['error_code'=>1,'error_msg'=>'ticket参数不正确！','data'=>array()]);exit;
			}
		}else{
			return response()->json(['error_code'=>1,'error_msg'=>'您还未登录，请登录','data'=>array()]);exit;
		}
	}

	public function loginCheck(Request $request){
		if(empty($request->member_id)){
			return response()->json(['error_code'=>1,'error_msg'=>'用户id参数缺失！','data'=>array()]);exit;
		}
		if(empty($request->ticket)){
			return response()->json(['error_code'=>1,'error_msg'=>'ticket参数缺失！','data'=>array()]);exit;
		}

		$member_id = htmlspecialchars(trim($request->member_id));
		$ticket = htmlspecialchars(trim($request->ticket));
		
		if(Redis::exists('ybf:family:login:'.$member_id)){
			if(json_decode(Redis::get('ybf:family:login:'.$member_id))->ticket == $ticket){
				$password = json_decode(Redis::get('ybf:family:login:'.$member_id))->password;
				return response()->json(['error_code'=>0,'error_msg'=>'登录成功','data'=>array('password'=>$password)]);
			}else{
				return response()->json(['error_code'=>1,'error_msg'=>'ticket参数不正确！','data'=>array()]);exit;
			}
		}else{
			return response()->json(['error_code'=>1,'error_msg'=>'您还未登录，请登录','data'=>array()]);exit;
		}
	}
}