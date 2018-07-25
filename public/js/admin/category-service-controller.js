(function () {

    angular.module('ybf.services').factory('categoryService', ['$resource', '$q', 'ybfConstants', function ($resource, $q, ybfConstants) {
        return $resource(ybfConstants.BASE_URL + "category/:action", {"action": "@action"}, {
            listParentCategory: {
                isArray: false,
                params: {action: 'list'}
            },
            saveCategory: {
                isArray: false,
                method: 'POST',
                params: { action: 'save_category' }
            },
            deleteParentCategory: {
                isArray : false,
                method : 'POST',
                params: { action: 'delete_category' }
            },
            SonCategoryList: {
                isArray : false,
                params:{action: 'son_list'}
            },
            saveSonCategory:{
                isArray : false,
                method : 'POST',
                params : {action : 'save_son_category'}
            },
            deleteSonCategory: {
                isArray : false,
                method : 'POST',
                params: { action: 'delete_son_category' }
            }
        });
    }]);


    angular.module('ybf.controllers').controller('categoryCtrl', function ($scope, categoryService, Upload,ybfConstants) {
        $scope.ParentCategory = [];
        $scope.listParentCategory = function () {
            categoryService.listParentCategory({}, function (data) {
                $scope.ParentCategory = data.data;
            });
        };
        $scope.listParentCategory();

        $scope.selectedParentCategory = {};
        $scope.openParentCategoryForm = function (p) {
            if (p != null) {
                $scope.selectedParentCategory = p;
            }else{
                $scope.selectedParentCategory = {};
            }
            $('#parent-category-form').modal('show');
        };

        $scope.SonCategoryList = function (category_id) {
            categoryService.SonCategoryList({category_id : category_id}, function (data) {
                console.log(data);
                $scope.SonCategory = data.data;
                $scope.parent_id = category_id;

            });
        };

        $scope.openSonCategoryList = function (p) {

            $scope.SonCategoryList(p.category_id);
            $('#son-category-list').modal('show');
        };

        $scope.selectedSonCategory = {};
        $scope.openSonCategoryForm = function (p) {
            if (p != null) {
                $scope.selectedSonCategory = p;
            }else{
                $scope.selectedSonCategory = {};
            }
            $('#son-category-form').modal('show');
        };
        
        $scope.saveCategory = function () {
            categoryService.saveCategory({
                name: $scope.selectedParentCategory.name,
                category_id : $scope.selectedParentCategory.category_id
            },function (data){
                if (data.status) {
                    $('#parent-category-form').modal('hide');
                    $scope.listParentCategory();
                }
            });
        };

        $scope.saveSonCategory = function () {
            categoryService.saveSonCategory({
                name: $scope.selectedSonCategory.name,
                category_id : $scope.selectedSonCategory.category_id,
                parent_id : $scope.parent_id
            },function (data){
                if (data.status) {
                    $('#son-category-form').modal('hide');
                    $scope.SonCategoryList($scope.parent_id);
                }
            });
        };

        $scope.deleteParentCategory = function (m) {
            if (confirm('确定删除')) {
                categoryService.deleteParentCategory({ category_id: m.category_id },function (data) {
                    if (data.status) {
                        $scope.listParentCategory();
                    }else{
                        if(data.msg){
                            alert(data.msg);
                        }
                    }
                });
            }
        }

        $scope.deleteSonCategory = function (m) {
            if (confirm('确定删除')) {
                categoryService.deleteSonCategory({ category_id: m.category_id },function (data) {
                    if (data.status) {
                        $scope.SonCategoryList($scope.parent_id);
                    }else{
                        if(data.msg){
                            alert(data.msg);
                        }
                    }
                });
            }
        }
    });
})();
