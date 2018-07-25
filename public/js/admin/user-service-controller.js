/**
 * Created by new on 2016/5/26.
 */
(function () {

    angular.module('ybf.services').factory('userService', ['$resource', '$q', 'ybfConstants', function ($resource, $q, ybfConstants) {
        return $resource(ybfConstants.BASE_URL + "user/:action", {"action": "@action"}, {
            listUsers: {
                isArray: false,
                params: {action: 'list'}
            },
            saveUser: {
                isArray: false,
                method: 'POST',
                params: { action: 'saveUser' }
            }
        });
    }]);

    angular.module('ybf.controllers').controller('userCtrl', function ($scope, userService, ybfConstants) {
        $scope.pagingInfo = {per_page: ybfConstants.PAGE_SIZE, current_page: 1};
        $scope.users = [];
        $scope.listUsers = function (page) {
            if (page != null) {
                $scope.pagingInfo.current_page = page;
            }
            userService.listUsers($scope.pagingInfo, function (data) {
                $scope.users = data.data;
                $scope.pagingInfo.total = data.total;
            });
        };
        $scope.listUsers();
    });
})();
