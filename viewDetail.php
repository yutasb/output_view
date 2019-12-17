<!-- 投稿の中身を見るページ -->
<?php

require('function.php');
require('menu_bar.php');

$p_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '';
$checkView = checkViewPost($p_id);



?>
<?php
$siteTitle = (!$edit_flg) ? '投稿' : '編集';
require('head.php');
?>

<body>
    <div class="contents">
        <?php echo $checkView['post_title']; ?>
        <?php echo $checkView['type']; ?>
        <?php echo $checkView['when_see']; ?>
        <?php echo $checkView['where_see']; ?>
        <img src="<?php echo showImg($checkView['pic1']); ?>">
        <img src="<?php echo showImg($checkView['pic2']); ?>">
        <img src="<?php echo showImg($checkView['pic3']); ?>">
        <?php echo $checkView['comment']; ?>
    </div>
</body>

</html>