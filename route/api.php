<?php

use think\facade\Route;

Route::group('', function () {
    Route::group('api/:version', function () {
        Route::group('banner', function () {
            Route::get('name', 'api/:version.Banner/getBanner');
        });
        // 首页接口
        Route::group('index', function () {
            // 获取首页首屏banner
            Route::get('banner','api/:version.Index/indexBanner');
            // 获取首页推荐产品
            Route::get('rescProduct','api/:version.Index/rescProduct');
            // 获取首页上新产品
            Route::get('newsProduct','api/:version.Index/newsProduct');
            // 暂无用
            Route::get('topCate','api/:version.Index/topCate');
            // 获取推荐文创
            Route::get('rescTheme','api/:version.Index/themesResc');
        });

        // 专栏接口
        Route::group('column', function () {
            // 获取专栏头部推荐
            Route::get('indexResc','api/:version.Column/getIndexColumn');
            // 获取专栏详情
            Route::get('detail/:id','api/:version.Column/detail', [], ['id'=>'\d+']);
            // 获取分类下的专栏
            Route::get(':cate_id','api/:version.Column/getColumnsByCate',[],['cate_id'=>'\d+']);
            // 获取顶级分类
            Route::get('top_cate','api/:version.Column/getTopCate');
            // 获取推荐专栏  --- 暂不清楚
            Route::get('resc','api/:version.Column/getRescColumn');
            // 获取上下篇专栏
            Route::get('topBottom/:id','api/:version.Column/getTopAndBottom',[],['id'=>'\d+']);
            // 获取搜索专栏
            Route::get('search','api/:version.Column/search');
            // 获取分类下的子分类 -- 暂不清楚
            Route::get('list/:cate_id','api/:version.Column/getColumnList',[],['cate_id'=>'\d+']);
        });

        // 专栏分类接口
        Route::group('columnCate', function () {
            // 获取专栏分类 --  暂不清楚
            Route::get(':id','api/:version.ColumnCate/getColumnCateById',[],['id'=>'\d+']);
            // 获取当前分类下的子分类
            Route::get('sonCate','api/:version.ColumnCate/getSonById');
            // 获取专栏分类 --  暂不清楚
            Route::get('cate','api/:version.ColumnCate/getCate');
            // 获取顶级分类 --  暂不清楚
            Route::get('cateList','api/:version.ColumnCate/getColumnList');
            // 获取专栏面包屑
            Route::get('crumb','api/:version.ColumnCate/getCrumbCate');
        });

        // 产品接口
        Route::group('product', function () {
            // 获取产品详情
            Route::get(':id/detail','api/:version.Product/getOne',[],['id'=>'\d+']);
            // 获取产品分类
            Route::get(':cate_id','api/:version.Product/lst',[],['cate_id'=>'\d+']);
            // 获取分类下的产品 --- 暂不清楚
            Route::get('list/:cateid','api/:version.Product/getProductByCate',[],['cate_id'=>'\d+']);
            // 获取分类下推荐的产品
            Route::get('rescbycate','api/:version.Product/getRescProductByCate');
            // 获取推荐产品
            Route::get('resc','api/:version.Product/getRescProduct');
            // 通过标签id获取产品
            Route::get('label','api/:version.Product/getProductByLabel');
            // 通过搜索获取产品
            Route::get('search','api/:version.Product/search');
            // 栏目下的所有产品 参数cate_id  column：栏目
            Route::get('column','api/:version.Product/getProductByCategory');
        });

        // 文创接口
        Route::group('theme', function () {
            // 获取文创详情
            Route::get(':id/detail','api/:version.Theme/getThemeDetail',[],['id'=>'\d+']);
            // 获取分类下的产品 --- 暂不清楚
            Route::get('list/:cateid','api/:version.ThemeCategory/getProductByTheme',[],['cate_id'=>'\d+']);
        });

        // 文创分类接口
        Route::group('themeCate', function () {
            // 获取文创分类
            Route::get(':cate_id','api/:version.ThemeCategory/getCate',[],['cate_id'=>'\d+']);
            // 获取文创面包屑
            Route::get('crumb','api/:version.ThemeCategory/getParentCate');
        });

        // 新闻接口
        Route::group('news', function () {
            // 获取新闻详情
            Route::get(':id','api/:version.News/detail',[],['id'=>'\d+']);
            // 获取全部新闻
            Route::get('all','api/:version.News/getAllNews');
            // 获取上下篇
            Route::get('topBottom/:id','api/:version.News/getTopAndBottom',[],['id'=>'\d+']);
        });

        // 栏目接口
        Route::group('category', function () {
            // 获取栏目列表
            Route::get('list','api/:version.Category/getCate');
            // 获取推荐栏目
            Route::get('resc', 'api/:version.Category/getRescData');
        });

        // 礼品接口
        Route::group('gift', function () {
            // 获取礼品分类
            Route::get('cate','api/:version.Gift/getCate');
            // 获取当前分类下的子分类
            Route::get('sonCate','api/:version.Gift/getSonCate');
            // 获取父级分类 --- 暂不清楚
            Route::get('parents/:id','api/:version.Gift/getParentCate',[],['id'=>'\d+']);
            // 获取季节推荐
            Route::get('season','api/:version.Gift/getSeasonResc');
            // 获取banner轮播
            Route::get('banner','api/:version.Gift/getGiftBanner');
            // 获取礼品分类下的产品
            Route::get('list','api/:version.Gift/getProductByGiftCate');
            // 通过价格筛选
            Route::get('filter','api/:version.Product/getProductByPrice');
            // 通过礼品子分类获取产品  --- 暂不清楚
            Route::get('productByCate','api/:version.Gift/getProductByGiftSonCate');
        });

        // 礼品接口
        Route::group('cate', function () {
            // 获取二级分类
            Route::get('second','api/BaseController/cate');
            // 获取全部分类
            Route::get('all','api/:version.Cate/getAllCate');
            // 获取顶级分类
            Route::get('topCate','api/:version.Cate/getTopCate');
            // 获取子分类 --- 暂不清楚
            Route::get('sonAllCate/:cate_id','api/:version.Cate/getSonById',[],['cate_id'=>'\d+']);
            // 获取子分类  --- 暂不清楚
            Route::get('sonCate/:id','api/:version.Cate/getSonCate',[],['id'=>'\d+']);
            // 获取父级分类
            Route::get('parents/:id','api/:version.Cate/getParentCate',[],['id'=>'\d+']);
        });

    });

    Route::group('admin', function () {
        Route::group('banner', function () {
            Route::post('','admin/Banner/DoAdd');
            Route::put(':id','admin/Banner/DoEdit',[],['id'=>'\d+']);
            Route::delete('','admin/Banner/delBanner');
            Route::post('item','admin/Banner/addBannerItem');
            Route::patch('item','admin/Banner/editBannerItem');
            Route::delete('item', 'admin/Banner/delBannerItem');
            Route::patch('order','admin/Banner/listorder');
        });
    });
})->middleware(['ReflexValidate'])->allowCrossDomain();;

return [];
