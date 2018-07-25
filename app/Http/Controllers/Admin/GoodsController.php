<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GoodsController extends Controller
{
	public function listGoods(Request $request){
		$list = DB::select("SELECT `goods_id`,`goods_name`,`goods_price`,`goods_describe`,`goods_detail` FROM `goods` WHERE `status` = 1 AND is_deleted = 0");
		// print_r(DB::getQueryLog());
		return response()->json(array('data'=>$list));
	}

	public function saveGoods(Request $request){

		if($request->get('goods_id') > 0){
			//修改
			$info = DB::select("SELECT `goods_id`,`goods_name`,`goods_price`,`goods_describe`,`goods_detail` FROM `goods` WHERE `goods_id` = :goods_id",['goods_id'=>$request->get('goods_id')]);

			if(!empty($info)){
				// $save_data = $request->except('goods_id');
				// $save_data['goods_id'] = $request->input('goods_id');

				$result = DB::update("UPDATE `goods` SET `goods_name` = ?,`goods_price` = ?,`goods_describe` = ?,`goods_detail` = ?,`updated_time` = NOW() WHERE goods_id = ?",[$request->input('goods_name'),$request->input('goods_price'),$request->input('goods_describe'),$request->input('goods_detail'),$request->input('goods_id')]);

				// log_error('sql_log',json_encode(DB::getQueryLog()));
			}
		}else{
			//新增
			$result = DB::insert("INSERT INTO `goods` (`goods_name`,`goods_price`,`goods_describe`,`goods_detail`,`created_time`,`updated_time`) VALUES(?,?,?,?,?,?)",[$request->input('goods_name'),$request->input('goods_price'),$request->input('goods_describe'),$request->input('goods_detail'),date('Y-m-d H:i:s'),date('Y-m-d H:i:s')]);

		}
		if($result){
			return response()->json(array('status'=>'1'));
		}
	}

	//图片列表
	public function listImage(Request $request){
		$list = DB::select("SELECT image_url,image_type,image_id FROM `goods_image` WHERE goods_id = :goods_id and is_deleted = 0",['goods_id' => $request->input('goods_id')]);

		// log_error('list',json_encode($list));
		$list = $this->select_image_type($list);
		return response()->json(array('data' => $list));
	}

	//保存图片
	public function saveImage(Request $request){

		$result = 0;

		$relativeUrl = $this->saveFile($request, 'image_url');

		if(!empty($relativeUrl)){
            
			if($request->input('image_id') > 0){
				//修改
				$info = DB::select("SELECT COUNT(*) FROM `goods_image` WHERE image_id = :image_id" , ['image_id' => $request->input('image_id')]);

				if(!empty($info)){
					$result = DB::update("UPDATE `goods_image` SET image_url = ?,image_type = ?,updated_time = NOW() WHERE image_id = ?" , [$relativeUrl,$request->input('image_type'),$request->input("image_id")]);
				}
			}else{
				//新增
				$result = DB::insert("INSERT INTO `goods_image` (image_url,image_type,goods_id,created_time,updated_time) VALUES(?,?,?,NOW(),NOW())",[$relativeUrl,$request->input('image_type'),$request->input('goods_id')]);
			}
		}

		if($result){
			return response()->json(array('status'=>'1'));
		}
	}

	//上传图片
	private function saveFile(Request $request, $name)
    {
        $relativePath = '';
        if ($request->hasFile($name) && $request->file($name)->isValid()) {
            $path = public_path() . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
            $extension = $request->file($name)->guessExtension();
            if ($extension == 'jpeg') {
                $extension = 'jpg';
            }
            log_error('path',$path);
            $fileName = date('ymdhis') . str_random(4) . '.' . $extension;

            if(!file_exists($path)){
            	mkdir($path);
            }

            if(!file_exists($path . DIRECTORY_SEPARATOR . date('Y-m-d'))){
            	mkdir($path . DIRECTORY_SEPARATOR . date('Y-m-d'));
            }

            $path = $path . DIRECTORY_SEPARATOR . date('Y-m-d') . DIRECTORY_SEPARATOR;

            $request->file($name)->move($path, $fileName);

            $relativePath = str_replace(public_path(), '', $path) . $fileName;
        }
        return $relativePath;
    }

    //类型赋名称
	public function select_image_type($arr){
		foreach ($arr as &$val) {
			switch ($val->image_type) {
					case '1':
						$val->image_type = '缩略图';
						break;
					case '2':
						$val->image_type = '详情图';
						break;
					default:
						$val->image_type = '图文图';
						break;
				}	
		}
		return $arr;
	}

	//删除商品
	public function deleteGoods(Request $request){
		$result = 0;
		if($request->input('goods_id') > 0){
			DB::beginTransaction();

			$result = DB::update("UPDATE `goods` SET deleted_time = NOW(),is_deleted = 1 WHERE goods_id = ?",[$request->input('goods_id')]);
		}

		if($result){
			$result_img = DB::update("UPDATE `goods_image` SET deleted_time = NOW(), is_deleted = 1 WHERE goods_id = ?" , [$request->input('goods_id')]);
			if($result_img){
				DB::commit();
			}else{
				DB::rollBack();
			}

			return response()->json(array('status'=>1));
		}
	}

	//删除图片
	public function deleteImage(Request $request){
		$result = 0;
		if($request->input('image_id') > 0){
			$result = DB::update("UPDATE `goods_image` SET deleted_time = NOW(),is_deleted = 1 WHERE image_id = ?",[$request->input('image_id')]);
		}

		if($result){
			// if(!empty($request->input('image_url'))){
			// 	if(file_exists('.'.$request->input('image_url'))){
			// 		unlink('.'.$request->input('image_url'));
			// 	}
			// }
			return response()->json(array('status'=>1));
		}
	}

	//商品分类列表
	public function listCategory(Request $request){
		$list = DB::select("SELECT c.name AS name,c.parent_name AS parent_name,gc.id AS id FROM category AS c LEFT JOIN goods_category AS gc ON c.category_id = gc.category_id WHERE gc.goods_id = ? AND gc.is_deleted = 0" ,[$request->input("goods_id")]);

		return response()->json(array('data'=>$list));
	}

	//获取选中商品的可添加的商品分类
	public function getCategory(Request $request){
		if($request->input('goods_id') > 0){
			// DB::select("SELECT (SELECT name FROM category WHERE category_id = c.category_id) AS parent_name,name FROM `goods_category` gc LEFT JOIN `category` c ON gc.category_id = c.category_id WHERE gc.goods_id = ? AND gc.category_id = null AND c.is_parent = 0");
			$list = DB::select("SELECT name,parent_name,category_id FROM category  WHERE category_id NOT IN (SELECT `category_id` FROM `goods_category` WHERE goods_id = ? AND is_deleted = 0) AND is_parent = 0" , [$request->input("goods_id")]);

			return response()->json(array('data'=>$list));
		}
	}

	//为商品加分类
	public function saveCategory(Request $request){
		$result = 0;
		if($request->input("goods_id") > 0){
			if($request->input("id") > 0){
				//修改
				$info = DB::select("SELECT COUNT(*) AS total FROM goods_category WHERE id = ? AND is_deleted = 0",[$request->input("id")]);

				if($info[0]->total > 0){
					$result = DB::update("UPDATE goods_category SET category_id = ? WHERE id = ?" , [$request->input('category_id'),$request->input('id')]);
				}
			}else{
				if($request->category_id > 0){
					//新增
					$result = DB::select("INSERT INTO `goods_category`(goods_id,category_id,created_time,updated_time) VALUES(?,?,NOW(),NOW())",[$request->input("goods_id"),$request->input("category_id")]);
				}
				
				// log_error('bbbbbbbbbbbb',json_encode($result));
			}
		}

		if($result){
			return response()->json(array('status'=>1));
		}
	}

	public function deleteCategory(Request $request){
		$result = 0;
		if($request->input("id") > 0){
			$result = DB::update("UPDATE goods_category SET is_deleted = 1,deleted_time = NOW() WHERE id = ?" , [$request->id]);
		}

		if($result){
			return response()->json(array('status'=>1));
		}
	}
}