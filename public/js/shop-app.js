(function () {

    angular.module('ybf.services', ['ngResource']);
    angular.module('ybf.controllers', []);
    angular.module('ybf.filters', []);
    angular.module('ybf.directives', []);
    angular.module('ybf.constants', []).constant('ybfConstants', {
        //BASE_URL: 'http://ui.ybf-china.com/',
        //BASE_URL: 'http://shop.flower-wine.com/',
        // BASE_URL: 'http://wine-shop.com:8099/',
        BASE_URL:'   ',
        PAGE_SIZE: 15
    });

    angular.module('ybfapp', ['ybf.services', 'ybf.controllers', 'ybf.constants', 'ybf.directives','ybf.filters',"bw.paging",'ngFileUpload']);

}());


