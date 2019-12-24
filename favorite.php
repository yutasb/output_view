<?php
require('function.php');
require('menu_bar.php');

$u_id = $_SESSION['user_id'];
$myLikePost = getMyLike($u_id);

?>

<?php
$siteTitle = 'お気に入り';
require('head.php');
?>

<body>
    <div class="contents">
        <?php if (!empty($myLikePost)) :
            foreach ($myLikePost as $key => $val) :
                ?>
                <a href="viewDetail.php<?php echo '?p_id=' . $val['id']; ?>">
                    <img src="<?php echo ($val['pic1']); ?>"><br>
                    <p><?php echo ($val['post_title']); ?></p>
                </a>
        <?php
            endforeach;
        endif;
        ?>
    </div>
</body>