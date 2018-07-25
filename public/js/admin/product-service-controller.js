(function () {

    angular.module('ybf.services').factory('productService', ['$resource', '$q', 'ybfConstants', function ($resource, $q, ybfConstants) {
        return $resource(ybfConstants.BASE_URL + "goods/:action", {"action": "@action"}, {
            listGoods: {
                isArray: false,
                params: {action: 'list'}
            },
            saveGoods: {
                isArray: false,
                method: 'POST',
                params: { action: 'save_goods' }
            },
            deleteGoods: {
                isArray : false,
                method : 'POST',
                params: { action: 'delete_goods' }
            },
            listImages: {
                isArray : false,
                params:{action: 'image_list'}
            },
            deleteImage:{
                isArray : false,
                method : 'POST',
                params : {action : 'delete_image'}
            },
            listCategory:{
                isArray : false,
                params : {action : 'category_list'}
            },
            getCategory : {
                isArray : false,
                method : 'POST',
                params : {action : 'get_category'}
            },
            saveCategory : {
                isArray : false,
                method : 'POST',
                params : {action : 'save_category'}
            },
            deleteCategory : {
                isArray : false,
                method : 'POST',
                params : {action : 'delete_category'}
            }
        });
    }]);

    angular.module('ybf.controllers').controller('productCtrl', function ($scope, productService, Upload,ybfConstants) {
        $scope.products = [];
        $scope.listGoods = function () {
            productService.listGoods({}, function (data) {
                $scope.products = data.data;
            });
        };
        $scope.listGoods();

        $scope.selectedProduct = {};
        $scope.openProductForm = function (p) {
            if (p != null) {
                $scope.selectedProduct = p;
            }
            $('#product-form').modal('show');
        };

        //文明
        $scope.images =  new Array();
        $scope.listImages = function (goods_id){
            productService.listImages({goods_id : goods_id} , function (data){
                $scope.images = data.data;
                $scope.goods_id = goods_id;
            })
        }

        $scope.image_option = [{
            'name':'缩略图',
            'value':'1'
        },{
            'name':'详情图',
            'value':'2'
        },{
            'name':'图文图',
            'value':'3'
        }];
        // $scope.image_option = ['缩略图','详情图','图文图'];

        // $scope.saveGoods = function () {
        //     thumbnailFile.upload = Upload.upload({
        //         url: ybfConstants.BASE_URL + 'goods/save_goods',
        //         data: {
        //             goods_id: $scope.selectedProduct.goods_id,
        //             goods_name: $scope.selectedProduct.goods_name,
        //             goods_price: $scope.selectedProduct.goods_price,
        //             goods_describe: $scope.selectedProduct.goods_describe,
        //             goods_detail: $scope.selectedProduct.goods_detail
        //         }
        //     });

        //     thumbnailFile.upload.then(function (response) {
        //         if (response.data.status == 0) {
        //             $('#product-form').modal('hide');
        //             $scope.listGoods();
        //         }
        //     }, null, null);
        // };

         $scope.saveGoods = function () {
            productService.saveGoods({
                goods_id: $scope.selectedProduct.goods_id,
                goods_name: $scope.selectedProduct.goods_name,
                goods_price: $scope.selectedProduct.goods_price,
                goods_describe: $scope.selectedProduct.goods_describe,
                goods_detail: $scope.selectedProduct.goods_detail
            
            },function (data){
                if (data.status) {
                    $('#product-form').modal('hide');
                    $scope.listGoods();
                }
            });
        };



        $scope.openImageList = function (p) {
            if (p != null) {
                $scope.goods_id = p.goods_id;
            }

            $scope.listImages($scope.goods_id);

            $('#product-detail-form').modal('show');
        };

        $scope.openImageForm = function (p){
            // console.log(p);
            if(p != null){
                $scope.selectedImage = p;
            }else{
                $scope.selectedImage = '';
            }

            $("#product-image-form").modal('show');
        }

        $scope.selectedImage = {};
        $scope.saveImage = function (thumbnailFile) {
            $scope.selectedImage.image_type = $scope.selectedImage.image_type_detail; 
            // console.log($scope.selectedImage);
            thumbnailFile.upload = Upload.upload({
                url: ybfConstants.BASE_URL + 'goods/save_image',
                data: {
                    goods_id: $scope.goods_id,
                    image_type : $scope.selectedImage.image_type,
                    image_id : $scope.selectedImage.image_id,
                    image_url: thumbnailFile
                }
            });

            thumbnailFile.upload.then(function (data) {
                if (data.status) {
                    $('#product-image-form').modal('hide');
                    $scope.listImages($scope.goods_id);
                }
            }, null, null);
        };

        $scope.deleteImage = function (m) {
            if (confirm('确定删除')) {
                productService.deleteImage({ image_id: m.image_id ,image_url : m.image_url},function (data) {
                    if (data.status) {
                        $scope.listImages($scope.goods_id);
                    }
                });
            }
        }

        $scope.deleteGoods = function (m) {
            if (confirm('确定删除')) {
                productService.deleteGoods({ goods_id:m.goods_id},function (data) {
                    if (data.status) {
                        $scope.listGoods();
                    }
                });
            }
        }

        $scope.now_has_category = new Array();

        //商品分类列表
        $scope.listCategory = function(goods_id){
            productService.listCategory({goods_id : goods_id},function (data){
                console.log(data.data);
                $scope.now_has_category = data.data;
                $scope.selected_goods_id = goods_id;
            })
        }

        $scope.openCategoryList = function(m){
            if(m != null){
                $scope.listCategory(m.goods_id);

            }
            $("#category-list").modal('show');
        }


        $scope.openCategoryForm = function(m){
            if(m != null){
                $scope.selected_category = m;
            }else{
                $scope.selected_category = {};
                $scope.selected_category.id = 0;
            }
            //获取所有该商品没有的分类
            $scope.getCategory(m);

            $("#category-form").modal('show');
        }


        $scope.selected_category = {}; 
        $scope.getCategory = function(){
            productService.getCategory({goods_id : $scope.selected_goods_id}, function (data){
                $scope.can_add_category = data.data;
                // console.log($scope.can_add_category);
            })
        }

        $scope.saveCategory = function(){
            productService.saveCategory({
                goods_id : $scope.selected_goods_id,
                category_id : $scope.goods_category,
                id : $scope.selected_category.id
            },function (data){
                if(data.status){
                    //获取新数据
                    $("#category-form").modal('hide');
                    $scope.listCategory($scope.selected_goods_id);
                }
            })
        }

        $scope.deleteCategory = function(m){
            productService.deleteCategory({
                id : m.id
            },function (data){
                if(data.status){
                    $scope.listCategory($scope.selected_goods_id);
                }
            })
        }
    });
})();
