<!-- 投稿の中身を見るページ -->
<?php

require('function.php');
require('menu_bar.php');
$p_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '';
$checkView = checkViewPost($p_id);
$checkIcon = checkIcon();






?>
<?php
$siteTitle = (!$edit_flg) ? '投稿' : '編集';
require('head.php');
?>

<body>
    <div class='detail-prof'>
        <!-- <i class はiconawesomeでのハートの表記コード -->
        <i class="fa fa-heart icn-like js-click-like <?php if (isLike($_SESSION['user_id'], $checkView['id'])) {
                                                            echo 'active';
                                                        } ?>" aria-hidden="true" data-viewid="<?php echo $checkView['id']; ?>"></i><br>

        <p class='detail_username'> ユーザーネーム：<?php echo $checkIcon['username']; ?></p><br>
        <a href="otherProfile.php<?php echo '?u_id=' . $checkView['user_id']; ?>"><img src="<?php echo showImg($checkIcon['pic']); ?>" class='detail_icon'></a>
    </div>
    <div class="contents">
        <span class='badge'><?php echo $checkView['type']; ?></span>
        <p class='detail_when'><?php echo $checkView['when_see']; ?>
            　　<?php echo $checkView['where_see']; ?></p><br>
        <div class="img">
            <img src="<?php echo showImg($checkView['pic1']); ?>" id='js-switch-img-main'>
            <div class='img-subs'>
                <img src="<?php echo showImg($checkView['pic1']); ?>" class='js-switch-img-sub'>
                <img src="<?php echo showImg($checkView['pic2']); ?>" class='js-switch-img-sub'>
                <img src="<?php echo showImg($checkView['pic3']); ?>" class='js-switch-img-sub'><br>
            </div>
        </div>
        <p class='detail_title'><?php echo $checkView['post_title']; ?></p><br>
        <p class='detail_comment'><?php echo $checkView['comment']; ?></p><br><br><br>
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