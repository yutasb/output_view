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
                <img src="<?php echo showImg($val['pic1']); ?> ">
                <p><?php echo ($val['post_title']); ?></p>
    </div>

<?php
    endforeach;
endif;
?>

</body>

</html>