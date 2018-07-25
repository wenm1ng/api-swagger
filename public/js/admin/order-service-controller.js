(function () {

    angular.module('ybf.services').factory('orderService', ['$resource', '$q', 'ybfConstants', function ($resource, $q, ybfConstants) {
        return $resource(ybfConstants.BASE_URL + "order/:action", {"action": "@action"}, {
            listOrders: {
                isArray: false,
                params: {action: 'list'}
            },
            listOrderItems: {
                isArray: true,
                params: {action: 'list_order_items'}
            },
            saveOrder: {
                isArray: false,
                params: {action: 'save_order'}
            }
        });
    }]);

    angular.module('ybf.controllers').controller('orderCtrl', function ($scope, orderService, ybfConstants) {
        $scope.pagingInfo = {per_page: ybfConstants.PAGE_SIZE, current_page: 1};
        $scope.orders = [];
        $scope.listOrders = function (page) {
            if (page != null) {
                $scope.pagingInfo.current_page = page;
            }

            orderService.listOrders($scope.pagingInfo, function (data) {
                $scope.orders = data.data;
                $scope.pagingInfo.total = data.total;
            });
        };
        $scope.listOrders();

        $scope.orderItems = [];
        $scope.openDetailForm = function (o) {
            $scope.selectedOrder = o;
            orderService.listOrderItems({order_id: o.id}, function (data) {
                $scope.orderItems = data;
                $('#detail-form').modal('show');
            });
        };

        $scope.statusList = [
            {text: '已下单', val: 1},
            {text: '已支付', val: 2},
            {text: '已发货', val: 4},
            {text: '已完成', val: 8},
            {text: '已取消', val: 16}
        ];

        $scope.getStatusName = function (s) {
            var name = '';
            angular.forEach($scope.statusList,function (i) {
                if (s == i.val) {
                    name = i.text;
                }
            });
            return name;
        };

        $scope.openEditForm = function (o) {
            $scope.selectedOrder = o;
            $('#edit-form').modal('show');
        };

        $scope.saveOrder = function () {
            orderService.saveOrder({
                order_id: $scope.selectedOrder.id,
                status: $scope.selectedOrder.status,
                delivery_no: $scope.selectedOrder.delivery_no
            }, function (data) {
                if (data.status == 0) {
                    $('#edit-form').modal('hide');
                    $scope.listOrders();
                }
            });
        };
    });
})();
