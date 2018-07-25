<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Collection;
class UserController extends Controller
{
     private $time = 3600;
     private $member_id_list = array();
	/**
     * @SWG\Post(path="/api/login",
     *   tags={"接口列表"},
     *   summary="用户登录接口",
     *   operationId="login",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     in="formData",
     *     name="phone",
     *     type="string",
     *     description="用户名",
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="password",
     *     type="string",
     *     description="密码",
     *   ),
     *   @SWG\Response(
     *   response="default", 
     *   description="返回成功",
     *   	@SWG\Schema(ref="#/definitions/login")
     *   ),
     * )
     */
	public function login(Request $request){
		if(empty($request->phone)){
			return response()->json(array('error_code'=>1,'error_msg'=>'用户名为空！','data'=>array()));exit;
		}
		if(empty($request->password)){
			return response()->json(array('error_code'=>1,'error_msg'=>'密码为空！','data'=>array()));exit;
		}
		
		$info = DB::connection('mysql_erp')->select("SELECT CAST(memberId AS CHAR) AS member_id,name,weixinNo AS weixin_no,('1') AS is_erp,password,CAST(star AS CHAR) AS star,phone,CAST(parentId AS CHAR) AS parentId FROM member WHERE status = 1 AND phone = ?",[htmlspecialchars(trim($request->phone))]);

		if(!empty($info)){

			//有数据
			if($info[0]->password != substr(md5(htmlspecialchars(trim($request->password))), 8, 16))
			{
				return response()->json(array('error_code'=>1,'error_msg'=>'密码不正确！','data'=>array()));exit;
			}

			unset($info[0]->password);

               $member_id = $info[0]->member_id;
               //判断redis里面有无这个键值
               if(!Redis::exists('ybf:family:login:'.$member_id)){
                    $ticket = 'ticket'.$this->getRandChar(11);
                    Redis::set('ybf:family:login:'.$member_id,json_encode(['ticket'=>$ticket,'password'=>$request->password]));
               }else{
                    $content = json_decode(Redis::get('ybf:family:login:'.$member_id));
                    $ticket = $content->ticket;
               }
               Redis::expire('ybf:family:login:'.$member_id,$this->time);
               $info[0]->ticket = $ticket;

               $superior = $this->getAllParent($member_id);
               
			return response()->json(array('error_code'=>0,'error_msg'=>'返回成功','data'=>$info,'superior'=>$superior));exit;
		}else{
			return response()->json(array('error_code'=>0,'error_msg'=>'返回成功','data'=>array(array('is_erp'=>"0")),'superior'=>$superior));exit;
		}
		
	}

     public function getAllParent($memberId){
          $info = DB::connection('mysql_erp')->select("SELECT parentId FROM member_hierarchy where memberId = ? AND parentId > 1 ORDER BY depth DESC",[4190]);
          return $info;
     }


     //获取随机字符串
     public function getRandChar($length){
         $str = '';
         $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789abcdefghijklmnopqrstuvwxyz";//大小写字母以及数字
         $max = strlen($strPol)-1;
         
         for($i=0;$i<$length;$i++){
            $str.=$strPol[rand(0,$max)];
         }
         return $str;
    }

	/**
     *
     * @SWG\Post(path="/api/subordinates",
     *   tags={"接口列表"},
     *   summary="查询下级接口",
     *   operationId="subordinates",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     in="formData",
     *     name="member_id",
     *     type="string",
     *     description="用户id",
     *   ),
     *   @SWG\Response(
     *      response="default", 
     *   	description="返回成功",
     *   	@SWG\Schema(ref="#/definitions/subordinates")
     *   ),
     * )
     */
	public function subordinates(Request $request){
		if(empty($request->member_id)){
			return response()->json(array('error_code'=>1,'error_msg'=>'用户id为空！','data'=>array()));exit;
		}

		$info = DB::connection('mysql_erp')->select("SELECT `memberId` FROM member WHERE status = 1 AND memberId = ?",[htmlspecialchars(trim($request->member_id))]);

		if(empty($info)){
			return response()->json(array('error_code'=>1,'error_msg'=>'该用户不存在！','data'=>array()));exit;
		}

		//获取该用户下级
		$relationship_list = DB::connection('mysql_erp')->select("SELECT CAST(m.memberId AS CHAR) AS member_id,m.name,m.weixinNo AS weixin_no,('1') AS is_erp,m.password,CAST(m.star AS CHAR) AS star,m.phone FROM `member` m LEFT JOIN `member_hierarchy` mh ON m.memberId = mh.memberId WHERE mh.parentId = ? AND status = 1" , [htmlspecialchars(trim($request->member_id))]);
          // print_r($relationship_list);exit;
		//存入redis
		Redis::set('ybf:member:id:'.htmlspecialchars(trim($request->member_id)) , json_encode($relationship_list));

		return response()->json(array('error_code'=>0,'error_msg'=>'返回成功','data'=>array(array('relationship'=>'ybf:member:id:'.htmlspecialchars(trim($request->member_id))))));exit;
	}


     public function saveAddress(Request $request){
          if(empty($request->member_id)){
               return response()->json(array('error_code'=>1,'error_msg'=>'用户id为空！','data'=>array()));exit;
          }

          if(empty($request->address)){
               return response()->json(array('error_code'=>1,'error_msg'=>'地址为空！','data'=>array()));exit;
          }

          $info = DB::connection('mysql_erp')->select("SELECT `memberId` FROM member WHERE status = 1 AND memberId = ?",[htmlspecialchars(trim($request->member_id))]);

          if(empty($info)){
               return response()->json(array('error_code'=>1,'error_msg'=>'该用户不存在！','data'=>array()));exit;
          }

          //修改地址
          DB::connection('mysql_erp')->select("UPDATE member SET address = ? WHERE memberId = ?",[htmlspecialchars(trim($request->address)),htmlspecialchars(trim($request->member_id))]);

          return response()->json(array('error_code'=>0,'error_msg'=>'操作成功','data'=>array()));exit;
     }


     public function getQuantity(Request $request){
          if(empty($request->member_id)){
               return response()->json(array('error_code'=>1,'error_msg'=>'用户id为空！','data'=>array()));exit;
          }

          $info = DB::connection('mysql_erp')->select("SELECT `memberId` FROM member WHERE status = 1 AND memberId = ?",[htmlspecialchars(trim($request->member_id))]);

          if(empty($info)){
               return response()->json(array('error_code'=>1,'error_msg'=>'该用户不存在！','data'=>array()));exit;
          }

          //获取提货数据
          $list = DB::connection('mysql_erp')->select("SELECT SUM(quantity) AS total,(select star from member where memberId = :memberId) AS star FROM stock WHERE oweMemberId = :oweMemberId AND ownerId <> oweMemberId",[':oweMemberId'=>$request->member_id,':memberId'=>$request->member_id]);

          return response()->json(array('error_code'=>0,'error_msg'=>'操作成功','data'=>$list));exit;
     }


     public function userInfo(Request $request){
          if(empty($request->member_id)){
               return response()->json(array('error_code'=>1,'error_msg'=>'用户id为空！','data'=>array()));exit;
          }

          $info = DB::connection('mysql_erp')->select("SELECT `weixinNo` FROM member WHERE status = 1 AND memberId = ?",[htmlspecialchars(trim($request->member_id))]);

          return response()->json(array('error_code'=>0,'error_msg'=>'操作成功','data'=>$info));exit;
     }

     public function getMemberListByStar(Request $request){
          if(empty($request->star)){
               return response()->json(array('error_code'=>1,'error_msg'=>'级别参数缺失！','data'=>array()));exit;
          }

          $list = DB::connection('mysql_erp')->select("SELECT `memberId` FROM member WHERE status = 1  AND star = ?",[htmlspecialchars(trim($request->star))]);
          
          return response()->json(['error_code'=>0,'error_msg'=>'返回成功','data'=>$list]);
     }
}