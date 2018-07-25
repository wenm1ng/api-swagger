<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
	public function listCategory(Request $request){
		$list = DB::select("SELECT category_id,name,parent_id FROM `category` WHERE `status` = 1 AND is_deleted = 0 AND is_parent = 1");

		return response()->json(array('data'=>$list));
	}

	public function saveCategory(Request $request){
		$result = 0;

		if($request->input('category_id') > 0){
			//修改
			$info = DB::select("SELECT COUNT(*) FROM `category` WHERE category_id = ?",[$request->input('category_id')]);

			if(!empty($info)){
				$result = DB::update("UPDATE `category` SET name = ?,updated_time = NOW() WHERE category_id = ?" , [$request->input('name'),$request->input('category_id')]);
			}
		}else{
			if(!empty($request->input('name'))){

				$result = DB::insert("INSERT INTO `category` (name,is_parent,created_time,updated_time) VALUES(?,1,NOW(),NOW())",[$request->input('name')]);
			}
		}
		

		if($result){
			return response()->json(array("status"=>1));
		}
	}


	public function deleteCategory(Request $request){

		//判断是否有商品用该分类
		$info = DB::select("SELECT COUNT(*) as total FROM `goods_category` g LEFT JOIN (SELECT category_id FROM `category` WHERE parent_id = ?) AS son ON son.category_id = g.category_id WHERE g.is_deleted = 0 LIMIT 1" , [$request->input('category_id')]);

		if(!empty($info['total'])){
			//有商品用了旗下的分类
			return response()->json(array('status'=>0,'msg'=>'该分类下有商品，请先删除商品'));exit;
		}

		$result = DB::update("UPDATE `category` SET is_deleted = 1,deleted_time = NOW() WHERE category_id = ? OR parent_id = ?",[$request->input('category_id'),$request->input('category_id')]);

		// //删除商品分类关联表
		// $result_goods_category = DB::update("UPDATE `goods_category` SET is_deleted = 1 AND deleted_time = NOW() WHERE category_id IN (SELECT category_id FROM `category` WHERE parent_id = ?)" , [$request->input("category_id")]);

		if($result){
			return response()->json(array('status'=>1));
		}
	}

	public function sonListCategory(Request $request){
		$list = DB::select("SELECT category_id,name,parent_id FROM `category` WHERE parent_id = ? AND status = 1 AND is_deleted = 0" , [$request->input('category_id')]);

		return response()->json(array('data'=>$list));
	}

	public function saveSonCategory(Request $request){
		$result = 0;

		if($request->input('category_id') > 0){
			//修改
			$info = DB::select("SELECT COUNT(*) FROM `category` WHERE category_id = ?",[$request->input('category_id')]);

			if(!empty($info)){
				$result = DB::update("UPDATE `category` SET name = ?,updated_time = NOW() WHERE category_id = ?" , [$request->input('name'),$request->input('category_id')]);
			}
		}else{
			if(!empty($request->input('name'))){
				$parent_info = DB::select("SELECT `name` FROM `category` WHERE category_id = ? LIMIT 1" , [$request->input("parent_id")]);
				// log_error('bvbvvb',json_encode($parent_info[0]->name));
				$result = DB::insert("INSERT INTO `category` (name,parent_id,parent_name,created_time,updated_time) VALUES(?,?,?,NOW(),NOW())",[$request->input('name'),$request->input("parent_id"),$parent_info[0]->name?:""]);
			}
		}
		

		if($result){
			return response()->json(array("status"=>1));
		}
	}

	//子类删除
	public function deleteSonCategory(Request $request){

		//判断是否有商品用该分类
		$info = DB::select("SELECT COUNT(*) as total FROM `goods_category` WHERE category_id = ?" , [$request->input('category_id')]);

		if(!empty($info['total'])){
			//有商品用了旗下的分类
			return response()->json(array('status'=>0,'msg'=>'该分类下有商品，请先删除商品'));exit;
		}

		$result = DB::update("UPDATE `category` SET is_deleted = 1,deleted_time = NOW() WHERE category_id = ?",[$request->input('category_id')]);

		// //删除商品分类关联表
		// $result_goods_category = DB::update("UPDATE `goods_category` SET is_deleted = 1 AND deleted_time = NOW() WHERE category_id IN (SELECT category_id FROM `category` WHERE parent_id = ?)" , [$request->input("category_id")]);

		if($result){
			return response()->json(array('status'=>1));
		}
	}
}