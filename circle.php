<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>圓面積</title>
</head>
<body>
    <?php
    
    define("PI", 3.14);// 定義圓周率為常數

    $radius = 10;// 設定半徑
    
    $area = PI * pow($radius, 2);// 計算圓面積
    
    echo "半徑為 {$radius} 的圓面積為: {$area}";// 顯示圓面積
    ?>
</body>
</html>