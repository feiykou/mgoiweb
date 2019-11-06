{__NOLAYOUT__}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <title>跳转提示</title>
</head>
<body>
<script type="text/javascript" src="/static/admin/lib/jquery/jquery.min.js"></script>
<script type="text/javascript" src="/static/admin/lib/layer/2.4/layer.js"></script>
    <script type="text/javascript">
        (function(){
            layer.msg("<?php echo(strip_tags($msg));?>", {icon: 6,time:1000}, function () {
                window.location = "<?php echo($url);?>";
            });
            // var wait = document.getElementById('wait'),
            //     href = document.getElementById('href').href;
            // var interval = setInterval(function(){
            //     var time = --wait.innerHTML;
            //     if(time <= 0) {
            //         location.href = href;
            //         clearInterval(interval);
            //     };
            // }, 1000);
        })();
    </script>
</body>
</html>
