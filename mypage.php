<?php
require('function.php');
require('menu_bar.php');

$postData = getPost();


?>

<?php
$siteTitle = 'My page ';
require('head.php');
?>

<body>
    <div class='contents'>
        <?php
        if (!empty($postData)) :
            foreach ($postData as $key => $val) :
                ?>
                <a href="viewDetail.php<?php echo '?p_id=' . $val['id']; ?>">
                    <img src="<?php echo showImg($val['pic1']); ?> ">
                    <p><span><?php echo ($val['user_id']); ?></span><?php echo ($val['post_title']); ?></p>
                </a>


        <?php
            endforeach;
        endif;
        ?>

    </div>
</body>

</html>