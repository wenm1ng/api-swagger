@extends('layouts.admin')

@section('title')
    <title>商品管理</title>
@endsection

@section('content')
    <div ng-controller="categoryCtrl">
        <div class="container" style="margin-top:60px">
            <div class="row">
                <div class="form-inline">
                    <a ng-click="openParentCategoryForm()" class="btn btn-default btn-danger">新增</a>
                </div>
            </div>
            <div class="row">
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>分类名称</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="m in ParentCategory">
                        <td>@{{m.category_id}}</td>
                        <td>@{{m.name}}</td>
                        <td>
                            <button class="btn btn-sm btn-default" ng-click="openParentCategoryForm(m)">编辑</button>
                            <button class="btn btn-sm btn-default" ng-click="openSonCategoryList(m)">子类编辑</button>
                            <button class="btn btn-sm btn-default" ng-click="deleteParentCategory(m)">删除</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="parent-category-form" class="modal fade form-horizontal" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">分类设置</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row form-group">
                            <label for="password" class="col-md-3">分类名称</label>
                            <div class="col-md-6">
                                <input placeholder="" ng-model="selectedParentCategory.name" class="form-control"
                                       type="text">
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

        <div id="son-category-list" class="modal fade form-horizontal" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">子分类列表</h4>
                    </div>
	                <div class="form-inline" style="margin-left:5%">
	                    <a ng-click="openSonCategoryForm()" class="btn btn-default btn-danger">新增</a>
	                </div>
                    <div class="modal-body">
                        <div class="row">
			                <table class="table table-striped table-hover table-bordered">
			                    <thead>
			                    <tr>
			                        <th>类别名称</th>
			                        <th>操作</th>
			                    </tr>
			                    </thead>
			                    <tbody>
			                    <tr ng-repeat="m in SonCategory">
			                        <td>@{{m.name}}</td>
			                        <td>
			                            <button class="btn btn-sm btn-default" ng-click="openSonCategoryForm(m)">编辑</button>
			                            <button class="btn btn-sm btn-default" ng-click="deleteSonCategory(m)">删除</button>
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

        <div id="son-category-form" class="modal fade form-horizontal" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">子分类设置</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row form-group">
                            <label for="password" class="col-md-3">分类名称</label>
                            <div class="col-md-6">
                                <input placeholder="" ng-model="selectedSonCategory.name" class="form-control"
                                       type="text">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#!" class="btn btn-default" data-dismiss="modal">取消</a>
                        <a href="#!" class="btn btn-default" ng-click="saveSonCategory()">保存</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/js/admin/category-service-controller.js"></script>
    
@endsection