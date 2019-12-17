<!-- 自分の投稿のみを表示するページ -->
<?php
require('function.php');
require('menu_bar.php');

$u_id = $_SESSION['user_id'];
$myPostData = getMyPost($u_id);


?>

<?php
$siteTitle = 'My page ';
require('head.php');
?>

<body>
    <div class='contents'>
        <?php
        if (!empty($myPostData)) :
            foreach ($myPostData as $key => $val) :
                ?>
                <a href="viewRegister.php<?php echo '?p_id=' . $val['id']; ?>">
                    <img src="<?php echo showImg($val['pic1']); ?> ">
                    <p><?php echo ($val['post_title']); ?></p>
                </a>


        <?php
            endforeach;
        endif;
        ?>

    </div>
</body>

</html>