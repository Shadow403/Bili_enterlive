<?php
    //不限制执行时间
    set_time_limit(0);
    //最大内存
    ini_set('memory_limit','4024M');
    //拿到UID
    $getUID = $_GET['uid'];
    //为空则退出
    if (empty($getUID)) {
        exit;
    }
    //字体文件（导入）
    $fronts = './config/fonts/msyh.ttf';
    //图片宽度
    $picwidth = 2100;
    //图片高度
    $pichigh = 1000;
    //生成图片
    $image = imagecreatetruecolor($picwidth, $pichigh);
    //填充图片颜色
    $background = imagecolorallocate($image, 0, 0, 0);
    imagefill($image, 0 ,0, $background);
    //创建画笔
    $pen = imagecolorallocate($image, 255, 255, 255);
    //主链接
    $mainURL = "https://api.danmaku.suki.club/api/search/user/channel?uid=$getUID";
    //获取内容
    $getmainData = file_get_contents($mainURL,True);
    //转换格式
    $getMainJson = json_decode($getmainData,true);
    //数据间距
    $width = 15;
    $high = 150;
    //遍历写入
    foreach ($getMainJson['data'] as $Data){
        $EnterLive = $Data['name']. '  ['.$Data['uId'].']  '.'  ('.$Data[('totalLiveSecond')].'s)  ';
        if ($high > 1000){
            $high = 150;
            $width = $width + 300;
        }
        
        imagettftext($image, 15, 0 , 15, 135, $pen, realpath($fronts), 'UID:'.$getUID);
        imagettftext($image, 10, 0 , $width, $high, $pen, realpath($fronts), $EnterLive);
        $higt = 0;
        $high = $high + 15;
    }
    
    //写入内容
    imagejpeg($image, './data/'.$getUID.'.jpg');

    $outImg = imagecreatefromjpeg('./data/'.$getUID.'.jpg');
    $inImg = imagecreatefrompng('developinfo.png');

    $outSize = getimagesize('developinfo.png');
    
    imagecopymerge($outImg, $inImg, 0, 0, 0, 0, $outSize[0], $outSize[1], 25);
    //请求格式
    header("Content-Type: image/jpeg");
    imagejpeg($outImg);
    imagedestroy($image);
?>
