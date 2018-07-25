(function () {

    angular.module('ybf.services').factory('bannerService', ['$resource', '$q', 'ybfConstants', function ($resource, $q, ybfConstants) {
        return $resource(ybfConstants.BASE_URL + "banner/:action", {"action": "@action"}, {
            listBanners: {
                isArray: true,
                params: {action: 'list'}
            },
            saveBanner: {
                isArray: false,
                method: 'POST',
                params: { action: 'save_banner' }
            },
            deleteBanner: {
                params: { action: 'delete_banner' }
            }
        });
    }]);

    angular.module('ybf.controllers').controller('bannerCtrl', function ($scope, bannerService, Upload,ybfConstants) {
        $scope.banners = [];
        $scope.listBanners = function () {
            bannerService.listBanners({}, function (data) {
                $scope.banners = data;
            });
        };
        $scope.listBanners();

        $scope.selectedBanner = {};
        $scope.openBannerForm = function (p) {
            if (p != null) {
                $scope.selectedBanner = p;
            }
            $('#banner-form').modal('show');
        };
        $scope.saveBanner = function (banner) {
            banner.upload = Upload.upload({
                url: ybfConstants.BASE_URL + 'admin/banner/save_banner',
                data: {
                    sort: $scope.selectedBanner.sort,
                    url: banner
                }
            });

            banner.upload.then(function (response) {
                if (response.data.status == 0) {
                    $('#banner-form').modal('hide');
                    $scope.listBanners();
                }
            }, null, null);
        };

        $scope.deleteBanner = function (m) {
            if (confirm('确定删除')) {
                bannerService.deleteBanner({ id: m.id },function (data) {
                    if (data.status == 0) {
                        $scope.listBanners();
                    }
                });
            }
        }
    });
})();
