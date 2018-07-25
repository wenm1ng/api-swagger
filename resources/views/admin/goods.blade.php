@extends('layouts.admin')

@section('title')
    <title>商品管理</title>
@endsection

@section('content')
    <div ng-controller="productCtrl">
        <div class="container" style="margin-top:60px">
            <div class="row">
                <div class="form-inline">
                    <a ng-click="openProductForm()" class="btn btn-default btn-danger">新增</a>
                </div>
            </div>
            <div class="row">
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>商品名称</th>
                        <th>价格</th>
                        <!-- <th>商品图片</th> -->
                        <th>描述</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="m in products">
                        <td>@{{m.goods_id}}</td>
                        <td>@{{m.goods_name}}</td>
                        <td>@{{m.goods_price}}</td>
                        <!-- <td><a href="@{{ m.goods_image_url }}" target="_blank"><img ng-src="@{{ m.goods_image_url }}" width="50" height="50"/></a></td> -->
                        <td>@{{m.goods_describe}}</td>
                        <td>
                            <button class="btn btn-sm btn-default" ng-click="openProductForm(m)">编辑</button>
                            <button class="btn btn-sm btn-default" ng-click="openImageList(m)">图片编辑</button>
                            <button class="btn btn-sm btn-default" ng-click="openCategoryList(m)">分类编辑</button>
                            <button class="btn btn-sm btn-default" ng-click="deleteGoods(m)">删除</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="product-form" class="modal fade form-horizontal" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">产品设置</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row form-group">
                            <label for="password" class="col-md-3">商品名称</label>
                            <div class="col-md-6">
                                <input placeholder="" ng-model="selectedProduct.goods_name" class="form-control"
                                       type="text">
                            </div>
                        </div>
                        <div class="row form-group">
                            <label for="password" class="col-md-3">商品价格</label>
                            <div class="col-md-6">
                                <input placeholder="" ng-model="selectedProduct.goods_price" class="form-control"
                                       type="text">
                            </div>
                        </div>
                        <div class="row form-group">
                            <label for="password" class="col-md-3">商品描述</label>
                            <div class="col-md-6">
                                <textarea placeholder="" ng-model="selectedProduct.goods_describe" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label for="password" class="col-md-3">图文详情</label>
                            <div class="col-md-6">
                                <textarea placeholder="" ng-model="selectedProduct.goods_detail" class="form-control"></textarea>
                            </div>
                        </div>
                       
                    </div>
                    <div class="modal-footer">
                        <a href="#!" class="btn btn-default" data-dismiss="modal">取消</a>
                        <a href="#!" class="btn btn-default" ng-click="saveGoods()">保存</a>
                    </div>
                </div>
            </div>
        </div>

        <div id="product-detail-form" class="modal fade form-horizontal" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">商品图片列表</h4>
                    </div>
	                <div class="form-inline" style="margin-left:5%">
	                    <a ng-click="openImageForm()" class="btn btn-default btn-danger">新增</a>
	                </div>
                    <div class="modal-body">
                        <div class="row">
			                <table class="table table-striped table-hover table-bordered">
			                    <thead>
			                    <tr>
			                        <th>商品图片</th>
			                        <th>图片类别</th>
			                        <th>操作</th>
			                    </tr>
			                    </thead>
			                    <tbody>
			                    <tr ng-repeat="m in images">
			                        <td><a href="@{{ m.image_url }}" target="_blank"><img ng-src="@{{ m.image_url }}" width="50" height="50"/></a></td>
			                        <td>@{{m.image_type}}</td>
			                        <td>
			                            <button class="btn btn-sm btn-default" ng-click="openImageForm(m)">编辑</button>
			                            <button class="btn btn-sm btn-default" ng-click="deleteImage(m)">删除</button>
			                        </td>
			                    </tr>
			                    </tbody>
			                </table>
			            </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#!" class="btn btn-default" data-dismiss="modal">取消</a>
                    </div>
                </div>
            </div>
        </div>

        <div id="product-image-form" class="modal fade form-horizontal" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">商品明细图</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row form-group">
                            <label for="password" class="col-md-3">图片类型</label>
                            <div class="col-md-6">
                            <!-- ng-options="x.name for x in image_option" -->
                                <select class="form-control" ng-model="selectedImage.image_type_detail" >
                                	<!-- <option ng-repeat="x in image_option" value="@{{x.value}}">@{{x.name}}</option> -->
                                	<option value="1">缩略图</option>
                                	<option value="2">详情图</option>
                                	<option value="3">图文图</option>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label for="password" class="col-md-3">商品图</label>
                            <div class="col-md-6">
                                <input type="file" ngf-select ng-model="selectedImage.image_url" name="file"
                                       accept="image/*" ngf-max-size="10MB" required
                                       ngf-model-invalid="errorFile"/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#!" class="btn btn-default" data-dismiss="modal">取消</a>
                        <a href="#!" class="btn btn-default" ng-click="saveImage(selectedImage.image_url)">保存</a>
                    </div>
                </div>
            </div>
        </div>

        <div id="category-list" class="modal fade form-horizontal" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">商品分类列表</h4>
                    </div>
                    <div class="form-inline" style="margin-left:5%">
                        <a ng-click="openCategoryForm()" class="btn btn-default btn-danger">新增</a>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <table class="table table-striped table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th>第一类别</th>
                                    <th>第二类别</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="m in now_has_category">
                                    <td>@{{m.parent_name}}</td>
                                    <td>@{{m.name}}</td>
                                    <td>
                                        <button class="btn btn-sm btn-default" ng-click="openCategoryForm(m)">编辑</button>
                                        <button class="btn btn-sm btn-default" ng-click="deleteCategory(m)">删除</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#!" class="btn btn-default" data-dismiss="modal">取消</a>
                    </div>
                </div>
            </div>
        </div>

        <div id="category-form" class="modal fade form-horizontal" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">商品分类明细</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row form-group">
                            <label for="password" class="col-md-3">商品类型</label>
                            <div class="col-md-6">
                            <!-- ng-options="x.name for x in image_option" -->
                            <!-- ng-options="x.name for x in can_add_category" -->
                                <select class="form-control" ng-model="goods_category" >
                               
                                    <option ng-repeat="x in can_add_category" value="@{{x.category_id}}">@{{x.parent_name}} : @{{x.name}}</option>
                                    
                                </select>
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <a href="#!" class="btn btn-default" data-dismiss="modal">取消</a>
                        <a href="#!" class="btn btn-default" ng-click="saveCategory()">保存</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/js/admin/product-service-controller.js"></script>
    
@endsection