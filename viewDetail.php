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
        <!-- <i class はiconawesomeでのハートの表記コード -->
        <i class="fa fa-heart icn-like js-click-like <?php if (isLike($_SESSION['user_id'], $checkView['id'])) {
                                                            echo 'active';
                                                        } ?>" aria-hidden="true" data-viewid="<?php echo $checkView['id']; ?>"></i><br>

        <?php echo $checkView['post_title']; ?><br>
        <?php echo $checkView['type']; ?><br>
        <?php echo $checkView['when_see']; ?><br>
        <?php echo $checkView['where_see']; ?><br>
        <img src="<?php echo showImg($checkView['pic1']); ?>" id='js-switch-img-main'><br>
        <img src="<?php echo showImg($checkView['pic1']); ?>" class='js-switch-img-sub'>
        <img src="<?php echo showImg($checkView['pic2']); ?>" class='js-switch-img-sub'>
        <img src="<?php echo showImg($checkView['pic3']); ?>" class='js-switch-img-sub'><br>
        <?php echo $checkView['comment']; ?><br><br>
        <a href="mypage.php<?php echo appendGetParam((array('p_id'))); ?>">戻る</a>

    </div>

    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script>
        var $like,
            likeViewId;
        $like = $('.js-click-like') || null;
        //data属性をjsで取得する場合には、.dataを使う。
        likeViewId = $like.data('viewid') || null;
        if (likeViewId !== undefined && likeViewId !== null) {
            $like.on('click', function() {
                var $this = $(this);
                $.ajax({
                    type: 'POST',
                    url: 'ajaxLike.php',
                    data: {
                        viewid: likeViewId
                    }
                }).done(function(data) {
                    console.log('Ajax Success');
                    $this.toggleClass('active');
                }).fail(function(msg) {
                    console.log('Ajax Error');
                });
            });
        };

        var $switchImgSubs = $('.js-switch-img-sub'),
            $switchImgMain = $('#js-switch-img-main');
        $switchImgSubs.on('click', function(e) {
            $switchImgMain.attr('src', $(this).attr('src'));
        });
    </script>

</body>

</html>