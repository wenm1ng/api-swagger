<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Swagger\Annotations as SWG;
use Redis;
use Illuminate\Support\Facades\Cookie;

class SwaggerController extends Controller
{
    /**
 * @SWG\Swagger(
 *     schemes={"http","https"},
 *     host="api.ybf-china.com",
 *     basePath="/",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="益家人API文档",
 *         description="深圳益百分",
 *         termsOfService="",
 *     ),
 *    @SWG\Definition(
*       definition="login",
*       required={"error_code", "msg" , "data"},
*       @SWG\Property(
*           property="error_code",
*           type="integer",
*           format="int32",
*           description="错误码",
*           example="0",
*       ),
*       @SWG\Property(
*           property="msg",
*           type="string",
*           description="返回说明",
*           example="返回成功",
*       ),
*       @SWG\Property(
*           property="data",
*           type="Array",
*           description="member_id：会员id，name：会员姓名，weixin_no：微信号，is_erp：是否为erp账号（1是 0否），star：（1总代 2省级 3市级 4县级 5零售 6特约），phone：登录账号",
*           example="[{'member_id':'12','name':'雯雯','weixin_no':'橙子','is_erp':'1','star':'3','phone':'18888888888'}]",
*       ),
*      ),
 *    @SWG\Definition(
*       definition="subordinates",
*       required={"error_code", "msg" , "data"},
*       @SWG\Property(
*           property="error_code",
*           type="integer",
*           format="int32",
*           description="错误码",
*           example="0",
*       ),
*       @SWG\Property(
*           property="msg",
*           type="string",
*           description="返回说明",
*           example="返回成功",
*       ),
*       @SWG\Property(
*           property="data",
*           type="Array",
*           description="relationship：包含用户下级关系的redis键名",
*           example="[{'relationship':'ybf:member:id:1'}]",
*       ),
*      ),
 *    @SWG\Definition(
*       definition="goods_list",
*       required={"error_code", "msg" , "data"},
*       @SWG\Property(
*           property="error_code",
*           type="integer",
*           format="int32",
*           description="错误码",
*           example="0",
*       ),
*       @SWG\Property(
*           property="msg",
*           type="string",
*           description="返回说明",
*           example="返回成功",
*       ),
*       @SWG\Property(
*           property="data",
*           type="Array",
*           description="goods_id：商品id，goods_name：商品名称，goods_price：商品价格，goods_describe：商品描述，goods_detail：商品图文详情文字内容，image_url：商品缩略图链接",
*           example="[{'goods_id':2,'goods_name':'益生君','goods_price':'168','goods_describe':'很好的产品111','goods_detail':'反反复复bbbvvv','image_url':'http://api.com/\images\\2018-05-30\180530043702Wfp2.jpg'}]",
*       ),
*      ),
*    @SWG\Definition(
*       definition="goods_detail",
*       required={"error_code", "msg" , "data"},
*       @SWG\Property(
*           property="error_code",
*           type="integer",
*           format="int32",
*           description="错误码",
*           example="0",
*       ),
*       @SWG\Property(
*           property="msg",
*           type="string",
*           description="返回说明",
*           example="返回成功",
*       ),
*       @SWG\Property(
*           property="data",
*           type="Array",
*           description="goods_id：商品id，goods_name：商品名称，goods_price：商品价格，goods_describe：商品描述，goods_detail：商品图文详情文字内容，data：包含的商品详情图集合，image_url：商品详情图链接",
*           example="[{'goods_id':1,'goods_name':'123','goods_price':'123','goods_describe':'32123','goods_detail':'123','data':[{'image_url':'\images\2018-05-30\180530044529nUQ7.png'}]}]",
*       ),
*      ),
 *    @SWG\Definition(
*       definition="goods_category",
*       required={"error_code", "msg" , "data"},
*       @SWG\Property(
*           property="error_code",
*           type="integer",
*           format="int32",
*           description="错误码",
*           example="0",
*       ),
*       @SWG\Property(
*           property="msg",
*           type="string",
*           description="返回说明",
*           example="返回成功",
*       ),
*       @SWG\Property(
*           property="data",
*           type="Array",
*           description="category_id：种类id，name：种类名称，data：旗下种类集合",
*           example="[{'category_id':1,'name':'品牌','data':[{'category_id':3,'name':'益生君'}]}]"
*       ),
*      ),
* )
 */

    public function getJSON()
    {
    	// 你可以将API的`Swagger Annotation`写在实现API的代码旁，从而方便维护，
        // `swagger-php`会扫描你定义的目录，自动合并所有定义。这里我们直接用`Controller/`
        // 文件夹。
        $swagger = \Swagger\scan(app_path('Http/Controllers/'));
        // $swagger = \Swagger\scan(realpath(__DIR__.'/../../'));

        return response()->json($swagger, 200);

    }

    public function redis_test(Request $request)
    {
        session_start();
        Cookie::queue('token',session_id(),10);

        // print_r(Cookie::get('token'));
        // $info = Redis::set('wenming','zhuzhuaini');
        // print_r($info);
        // print_r(Cookie::get('token'));
        return view('test',['token'=>Cookie::get('token')]);
    }

}
