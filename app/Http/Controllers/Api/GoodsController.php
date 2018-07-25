<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GoodsController extends Controller
{
	/**
     *
     * @SWG\Post(path="/api/goods/list",
     *   tags={"接口列表"},
     *   summary="商品列表接口",
     *   operationId="listGoods",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     in="formData",
     *     name="goods_category",
     *     type="string",
     *     description="商品分类拼接字符串,例：'3,4,5,6'",
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="page_num",
     *     type="string",
     *     description="分页页数,例：'1'",
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="page_count",
     *     type="string",
     *     description="每页数据行数,例：'1'",
     *   ),
     *   @SWG\Response(
     *      response="default", 
     *   	description="返回成功",
     *   	@SWG\Schema(ref="#/definitions/goods_list")
     *   ),
     * )
     */
	public function listGoods(Request $request){
		
		if(empty($request->page_num)){
			return response()->json(array('error_code'=>1,'error_msg'=>'分页参数为空！','data'=>array()));exit;
		}
		if(empty($request->page_count)){
			return response()->json(array('error_code'=>1,'error_msg'=>'分页数值为空！','data'=>array()));exit;
		}


		$limit = ($request->page_num-1)*$request->page_count;

          $sql = "SELECT CAST(g.goods_id AS CHAR) AS goods_id,g.goods_name,g.goods_price,g.goods_describe,g.goods_detail,`image_url` FROM goods AS g LEFT JOIN (SELECT goods_id FROM goods_category ";

		if(!empty($request->goods_category)){
               $sql .= " WHERE category_id IN (?) ";
		}

          $sql .= " GROUP BY goods_id ) AS cate ON g.goods_id = cate.goods_id LEFT JOIN goods_image gi ON gi.goods_id = g.goods_id WHERE g.status = 1 AND g.is_deleted = 0 ";

          if(!empty($request->key_words)){
               $sql .= " AND goods_name LIKE '{$request->key_words}%' ";
          }

          $sql .= " LIMIT $limit , $request->page_count ";

          if(!empty($request->goods_category)){
               $list = DB::select($sql,[htmlspecialchars(trim($request->goods_category))]);
          }else{
               $list = DB::select($sql);
          }


		$goods_id_arr = array();
		$goods_arr = array();
		
		if(!empty($list)){
			
			foreach ($list as $key => $val) {
				if(!empty($val->image_url)){
					$list[$key]->image_url = 'http://'.$_SERVER['HTTP_HOST'].$val->image_url;
				}
				// $list[$key]->goods_id = (string)$val->goods_id;
				if(!in_array($val->goods_id,$goods_id_arr)){
					$goods_id_arr[] = $val->goods_id;
					$goods_arr[] = $list[$key];
				}
			}
		}
		
		return response()->json(array('error_code'=>0,'error_msg'=>'返回成功','data'=>$goods_arr));exit;
	}



	/**
     *
     * @SWG\Post(path="/api/goods/detail",
     *   tags={"接口列表"},
     *   summary="商品详情接口",
     *   operationId="detailGoods",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     in="formData",
     *     name="goods_id",
     *     type="string",
     *     description="商品id,例：'6'",
     *   ),
     *   @SWG\Response(
     *      response="default", 
     *   	description="返回成功",
     *   	@SWG\Schema(ref="#/definitions/goods_detail")
     *   ),
     * )
     */
	public function detailGoods(Request $request){
		if(empty($request->goods_id)){
			return response()->json(array('error_code'=>1,'error_msg'=>'商品id为空！','data'=>array()));exit;
		}

		$info = DB::select("SELECT CAST(goods_id AS CHAR) AS goods_id,goods_name,goods_price,goods_describe,goods_detail FROM goods WHERE goods_id = ? AND is_deleted = 0 AND status = 1" ,[htmlspecialchars(trim($request->goods_id))]);

		$image_list = DB::select("SELECT image_url FROM goods_image WHERE goods_id = ? AND is_deleted = 0 AND image_type = 2",[htmlspecialchars(trim($request->goods_id))]);

		foreach ($image_list as $key => $val) {
			if(!empty($val->image_url)){
				$image_list[$key]->image_url = 'http://'.$_SERVER['HTTP_HOST'].$val->image_url;
			}
		}

		if(!empty($info)){
			$info[0]->data = $image_list;
		}

		return response()->json(array('error_code'=>0,'error_msg'=>'返回成功','data'=>$info));exit;
	}

	/**
     *
     * @SWG\Post(path="/api/goods/category",
     *   tags={"接口列表"},
     *   summary="商品列表接口",
     *   operationId="categoryGoods",
     *   produces={"application/json"},
     *   @SWG\Response(
     *      response="default", 
     *   	description="返回成功",
     *   	@SWG\Schema(ref="#/definitions/goods_category")
     *   ),
     * )
     */

	public function categoryGoods(Request $request){
		$parent_list = DB::select("SELECT CAST(category_id AS CHAR) AS category_id,name FROM category WHERE status = 1 AND is_deleted = 0 AND is_parent = 1");

		foreach ($parent_list as $key => $val) {
			$parent_list[$key]->data = DB::select("SELECT CAST(category_id AS CHAR) AS category_id,name FROM category WHERE status = 1 AND is_deleted = 0 AND parent_id = '$val->category_id'");
		}

		return response()->json(array('error_code'=>0,'error_msg'=>'返回成功','data'=>$parent_list));exit;
	}
}