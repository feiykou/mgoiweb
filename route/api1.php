<?php
//
//use think\facade\Route;
//
//Route::group('', function () {
//    Route::group('api/:version', function () {
//        // 首页接口
//        Route::group('index', function () {
//            // 获取首页首屏banner
//            Route::get('banner','api/:version.Index/indexBanner');
//            // 获取首页推荐产品
//            Route::get('rescProduct','api/:version.Index/rescProduct');
//            // 获取首页上新产品
//            Route::get('newsProduct','api/:version.Index/newsProduct');
//            // 暂无用
//            Route::get('topCate','api/:version.Index/topCate');
//            // 获取推荐文创
//            Route::get('rescTheme','api/:version.Index/themesResc');
//        });
//        // 专栏接口
//        Route::group('column', function () {
//            // 获取专栏头部推荐
//            Route::get('indexResc','api/:version.Column/getIndexColumn');
//            // 获取专栏详情
//            Route::get('detail/:id','api/:version.Column/detail', [], ['id'=>'\d+']);
//            // 获取分类下的专栏
//            Route::get(':cate_id','api/:version.Column/getColumnsByCate',[],['cate_id'=>'\d+']);
//            // 获取顶级分类
//            Route::get('top_cate','api/:version.Column/getTopCate');
//            // 获取推荐专栏  --- 暂不清楚
//            Route::get('resc','api/:version.Column/getRescColumn');
//            // 获取上下篇专栏
//            Route::get('topBottom/:id','api/:version.Column/getTopAndBottom',[],['id'=>'\d+']);
//            // 获取搜索专栏
//            Route::get('search','api/:version.Column/search');
//            // 获取分类下的子分类 -- 暂不清楚
//            Route::get('list/:cate_id','api/:version.Column/getColumnList',[],['cate_id'=>'\d+']);
//        });
//
//        // 专栏分类接口
//        Route::group('columnCate', function () {
//            // 获取专栏头部推荐
//            Route::get('indexResc','api/:version.Column/getIndexColumn');
//            // 获取专栏详情
//            Route::get('detail/:id','api/:version.Column/detail', [], ['id'=>'\d+']);
//            // 获取分类下的专栏
//            Route::get(':cate_id','api/:version.Column/getColumnsByCate',[],['cate_id'=>'\d+']);
//            // 获取顶级分类
//            Route::get('top_cate','api/:version.Column/getTopCate');
//            // 获取推荐专栏  --- 暂不清楚
//            Route::get('resc','api/:version.Column/getRescColumn');
//            // 获取上下篇专栏
//            Route::get('topBottom/:id','api/:version.Column/getTopAndBottom',[],['id'=>'\d+']);
//            // 获取搜索专栏
//            Route::get('search','api/:version.Column/search');
//            // 获取分类下的子分类 -- 暂不清楚
//            Route::get('list/:cate_id','api/:version.Column/getColumnList',[],['cate_id'=>'\d+']);
//        });
//
//
//
//    });
//})->middleware(['ReflexValidate']);
////
////
////Route::get('api/:version/index/banner','api/:version.Index/indexBanner');
////Route::get('api/:version/index/rescProduct','api/:version.Index/rescProduct');
////Route::get('api/:version/index/newsProduct','api/:version.Index/newsProduct');
////Route::get('api/:version/index/topCate','api/:version.Index/topCate');
////Route::get('api/:version/index/rescTheme','api/:version.Index/themesResc');
//
//
////Route::get('api/:version/column/indexResc','api/:version.Column/getIndexColumn');
////Route::get('api/:version/column/detail/:id','api/:version.Column/detail',[],['id'=>'\d+']);
////Route::get('api/:version/column/:cate_id','api/:version.Column/getColumnsByCate',[],['cate_id'=>'\d+']);
////Route::get('api/:version/column/top_cate','api/:version.Column/getTopCate');
////Route::get('api/:version/column/resc','api/:version.Column/getRescColumn');
////Route::get('api/:version/column/topBottom/:id','api/:version.Column/getTopAndBottom',[],['id'=>'\d+']);
////Route::get('api/:version/column/search','api/:version.Column/search');
////Route::get('api/:version/column/list/:cate_id','api/:version.Column/getColumnList',[],['cate_id'=>'\d+']);
////
//
//
//Route::get('api/:version/columnCate/:id','api/:version.ColumnCate/getColumnCateById',[],['id'=>'\d+']);
//Route::get('api/:version/columnCate/sonCate','api/:version.ColumnCate/getSonById');
//Route::get('api/:version/columnCate/cate','api/:version.ColumnCate/getCate');
//Route::get('api/:version/columnCate/cateList','api/:version.ColumnCate/getColumnList');
//Route::get('api/:version/columnCate/crumb','api/:version.ColumnCate/getCrumbCate');
//
//
//
//Route::get('api/:version/product/:id/detail','api/:version.Product/getOne',[],['id'=>'\d+']);
//Route::get('api/:version/product/:cate_id','api/:version.Product/lst',[],['cate_id'=>'\d+']);
//Route::get('api/:version/product/list/:cateid','api/:version.Product/getProductByCate',[],['cate_id'=>'\d+']);
//Route::get('api/:version/product/rescbycate','api/:version.Product/getRescProductByCate');
//Route::get('api/:version/product/resc','api/:version.Product/getRescProduct');
//Route::get('api/:version/product/label','api/:version.Product/getProductByLabel');
//
//
//// 礼物
//Route::get('api/:version/giftCate/resc','api/:version.GiftCate/getRescGiftCate');
//
//
//
//// 公共
//Route::get('api/:version/cate/second','api/BaseController/cate');
//Route::get('api/:version/cate/all','api/:version.Cate/getAllCate');
//Route::get('api/:version/cate/topCate','api/:version.Cate/getTopCate');
//Route::get('api/:version/cate/sonAllCate/:cate_id','api/:version.Cate/getSonById',[],['cate_id'=>'\d+']);
//Route::get('api/:version/cate/sonCate/:id','api/:version.Cate/getSonCate',[],['id'=>'\d+']);
//Route::get('api/:version/cate/parents/:id','api/:version.Cate/getParentCate',[],['id'=>'\d+']);
//
//
//// 搜索
//Route::get('api/:version/product/search', 'api/:version.Product/search');
//
//
//// 主题 改动
//Route::get('api/:version/theme/:id/detail', 'api/:version.Theme/getThemeDetail',[],['id'=>'\d+']);
//Route::get('api/:version/theme/list/:cate_id', 'api/:version.ThemeCategory/getProductByTheme',[],['cate_id'=>'\d+']);
//Route::get('api/:version/themeCate/:cate_id', 'api/:version.ThemeCategory/getCate',[],['cate_id'=>'\d+']);
//Route::get('api/:version/themeCate/crumb', 'api/:version.ThemeCategory/getParentCate',[],['id'=>'\d+']);
////Route::get('api/:version/themeCate/product/:cate_id', 'api/:version.ThemeCategory/getProductByTheme',[],['cate_id'=>'\d+']);
//
//
//// 新闻
//Route::get('api/:version/news/all', 'api/:version.News/getAllNews');
//Route::get('api/:version/news/:id', 'api/:version.News/detail',[],['id'=>'\d+']);
//Route::get('api/:version/news/topBottom/:id','api/:version.News/getTopAndBottom',[],['id'=>'\d+']);
//
//
//// 栏目
//Route::get('api/:version/category/list','api/:version.Category/getCate');
//// 栏目下的所有产品 参数cate_id
//Route::get('api/:version/product/column','api/:version.Product/getProductByCategory');
//
//
//
//// 每月特辑
//Route::get('api/:version/album/:id/detail', 'api/:version.Album/getThemeDetail',[],['id'=>'\d+']);
//Route::get('api/:version/album/list/:cate_id', 'api/:version.Album/getThemeByCate',[],['cate_id'=>'\d+']);
//
//
//// 礼品
//Route::get('api/:version/gift/cate','api/:version.Gift/getCate');
//Route::get('api/:version/gift/sonCate','api/:version.Gift/getSonCate');
//Route::get('api/:version/gift/parents/:id','api/:version.Gift/getParentCate',[],['id'=>'\d+']);
//Route::get('api/:version/gift/season','api/:version.Gift/getSeasonResc');
//Route::get('api/:version/gift/banner','api/:version.Gift/getGiftBanner');
//Route::get('api/:version/gift/list','api/:version.Gift/getProductByGiftCate');
//Route::get('api/:version/gift/filter','api/:version.Product/getProductByPrice');
//Route::get('api/:version/gift/productByCate','api/:version.Gift/getProductByGiftSonCate');
//
//
//// 专区推荐
//Route::get('api/:version/pets/resc/:cate_id', 'api/:version.Category/getCategoryData',[],['cate_id'=>'\d+']);
//
//
//return [];