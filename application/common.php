<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use app\lib\exception\ParameterException;

/**
 * @param $code
 * @param $errorCode
 * @param $data
 * @param $msg
 * @return \think\response\Json
 */

function writeJson($code, $data, $msg = 'ok', $errorCode = 0)
{
    $data = [
        'error_code' => $errorCode,
        'result' => $data,
        'msg' => $msg
    ];
    return json($data, $code);
}

/**
 * 获取栏目名
 */
function getColumn($template_id){
    $str = '';
    switch ($template_id){
        case 1:
            $str = "index";
            break;
        case 2:
            $str = "product";
            break;
        case 3:
            $str = "contact";
            break;
        case 4:
            $str = "news";
            break;
        case 5:
            $str = "about";
            break;
        case 6:
            $str = "column";
            break;
        default:
            $str = "index";
    }
    return $str;
}


/**
 * 状态
 * @param $status
 * @return string
 */
function status($status){
    if($status == 1){
        $str = "<span class='label label-success radius'>正常</span>";
    }else if($status == 0){
        $str = "<span class='label label-danger radius'>待审</span>";
    }else{
        $str = "<span class='label label-danger radius'>删除</span>";
    }
    return $str;
}

function attr($attr,$attr_name='暂无'){
    $str = '';
    switch ($attr){
        case 1:
            $str = "<span class='label label-success radius'>$attr_name</span>";
            break;
        case 2:
            $str = "<span class='label label-danger radius'>$attr_name</span>";
            break;
        default:
            $str = "<span class='label label-default radius'>$attr_name</span>";
    }
    return $str;
}

/**
 * 无限级分类
 * @param $arr
 * @param int $pid
 * @param int $level
 * @param string $html
 * @return array
 */
function sortData($arr,$pid=0,$level=0,$html="----"){
    $newArr = [];
    foreach ($arr as $k=>$v){
        if($v['pid']==$pid){
            $v['level'] = $level;
            $v['html'] = $html;
            $newArr[] = $v;
            $newArr = array_merge($newArr,sortData($arr,$v['id'],$v['level']+1));
        }
    }
    return $newArr;
};

/**
 * 检测某key是否存在数组中
 */
function keyInArray($data,$key){
    $_data = [];
    foreach ($data as $k => $v){
        $_data[] = $k;
    }
    if(in_array($key,$_data)){
        return true;
    }else{
        return false;
    }
};

/**
 * 分页封装
 */
function pagination($obj){
    if(!$obj){
        return '';
    }
    return $obj->render();
}

// 获取对应模板的view
function getTemplateView($template_id=0){
    $templateArr = config('template.template_type');
    foreach ($templateArr as $k=>$v){
        if($v['key'] == $template_id){
            return $v['view'];
        }
    }
}

function getCategoryIdByName($name=''){
    $id = db('category')->where([
        'name' => $name,
        'status' => 1
    ])->value('id');
    if(!$id){
        $id = '/';
    }
    return $id;
}

function getNavClass($template_id=0){
    $templateArr = config('template.template_type');
    foreach ($templateArr as $k=>$v){
        if($v['key'] === $template_id){
            return $v['selClass'];
        }
    }
}

/**
 * 根据url链接字符串拆分成数组
 * $str: url链接字符串
 */
function getUrlArr($dataArr=[],$key){
    if(isset($key) && keyInArray($dataArr,$key)){
        foreach ($dataArr as $data){
            $data[$key] = explode(';',trim($data[$key],';'));
        }
    }
}


/**
 * @return array
 * @throws ParameterException
 */
function paginate()
{
    $count = intval(Request::get('count'));
    $start = intval(Request::get('page'));

    $count = $count >= 15 ? 15 : $count;

    $start = $start * $count;

    if ($start < 0 || $count < 0) throw new ParameterException();

    return [$start, $count];
}


/**
 * @param string $url get请求地址
 * @param int $httpCode 返回状态码
 * @return mixed
 */
function curl_get($url, &$httpCode = 0)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // 不做证书校验，部署在linux环境下请改为true
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $file_contents = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $file_contents;
}