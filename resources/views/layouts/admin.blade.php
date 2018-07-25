<!doctype html>
<html lang="{{ app()->getLocale() }}" ng-app="ybfapp">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @yield('title')
    <script src="/js/jquery.min.js"></script>
    <script src="/js/angular.min.js"></script>
    <script src="/js/angular-resource.min.js"></script>
    <script src="/js/shop-app.js"></script>
    <script src="/js/tether.js"></script>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/ng-file-upload-all.min.js"></script>
    <script src="/js/paging.min.js"></script>
</head>
<body>
<div class="container">
    <div class="navbar navbar-default navbar-fixed-top notprint">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">益家人商城管理后台</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="{{ url('/admin/goods') }}">商品列表</a></li>
                <li><a href="{{ url('/admin/category') }}">分类列表</a></li>
            </ul>
        </div>
    </div>
</div>
@yield('content')
</body>
</html>
