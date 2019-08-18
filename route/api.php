<?php

Route::get('api/:version/index/banner','api/:version.Index/indexBanner');
Route::get('api/:version/index/rescProduct','api/:version.Index/rescProduct');
Route::get('api/:version/index/newsProduct','api/:version.Index/newsProduct');
Route::get('api/:version/index/topCate','api/:version.Index/topCate');
Route::get('api/:version/index/column','api/:version.Index/getIndexColumn');

Route::get('api/:version/column/detail/:id','api/:version.Column/detail');
Route::get('api/:version/column/:cate_id','api/:version.Column/getColumnByCateId',[],['cate_id'=>'\d+']);
Route::get('api/:version/column/top_cate','api/:version.Column/getTopCate');
Route::get('api/:version/columnCate/:id','api/:version.ColumnCate/getColumnCateById');

Route::get('api/:version/product/:cate_id','api/:version.Product/lst',[],['cate_id'=>'\d+']);

// 公共
Route::get('api/:version/cate/second','api/BaseController/cate');
Route::get('api/:version/cate/all','api/:version.Cate/getAllCate');
Route::get('api/:version/cate/topCate','api/:version.Cate/getTopCate');
Route::get('api/:version/cate/sonCate/:cate_id','api/:version.Cate/getSonById',[],['cate_id'=>'\d+']);

// 搜索
Route::get('api/:version/product/search', 'api/:version.Product/search');

// 主题
Route::get('api/:version/theme/:id', 'api/:version.Theme/getThemeDetail',[],['id'=>'\d+']);
Route::get('api/:version/theme/list/:cate_id', 'api/:version.Theme/getThemeByCate',[],['cate_id'=>'\d+']);
Route::get('api/:version/theme/topCate', 'api/:version.ThemeCategory/getTopCate',[],['cate_id'=>'\d+']);



return [];