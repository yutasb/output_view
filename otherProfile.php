<?php
require('function.php');
require('menu_bar.php');

$u_id = (!empty($_GET['u_id'])) ? $_GET['u_id'] : '';
$myPostData = getMyPost($u_id);


$checkOther = checkOtherProfile($u_id);
?>
<?php
$siteTitle = 'プロフィール';
require('head.php');
?>

<body>
    <div class='contents'>
        <img src="<?php echo showImg($checkOther['pic']); ?>" class='profImg'><br>
        　ユーザー名：<?php echo $checkOther['username']; ?><br>
        お気に入りの絶景：<?php echo $checkOther['likeView']; ?><br>
        ひとこと：<?php echo $checkOther['myself']; ?><br><br>

        <?php
        if (!empty($myPostData)) :
            foreach ($myPostData as $key => $val) :
                ?>

                <img src="<?php echo showImg($val['pic1']); ?> ">
                <p><?php echo ($val['post_title']); ?></p>



        <?php
            endforeach;
        endif;
        ?>
    </div>
</body>

</html>