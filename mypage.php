<!-- 投稿一覧ページ -->
<?php
require('function.php');
require('menu_bar.php');
$u_id = $_SESSION['user_id'];
$category = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';
$dbPostData = getMyPostList($category);
$dbCategoryData = getCategory();
$p_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '';
$checkView = checkViewPost($p_id);

//検索なし用
$dbgetpost = getPost();




?>

<?php
$siteTitle = 'My page ';
require('head.php');
?>

<body>
    <div class="side">
        <form method='get' name=''>

            カテゴリ　<select name='c_id'>
                <option value='0' <?php if (getFormData('c_id', true) == 0) {
                                        echo 'selected';
                                    } ?>>選択してください</option>

                <?php foreach ($dbCategoryData as $key => $val) { ?>
                    <option value="<?php echo $val['id'] ?>" <?php if (getFormData('c_id', true) == $val['id']) {
                                                                        echo 'selected';
                                                                    } ?>>
                        <?php echo $val['name']; ?>
                    </option>
                <?php
                }
                ?>
            </select><br><br>
            <input type='submit' value='検索' class='searchSubmit'>
        </form>
    </div>
    <hr>
    <div class='mypageContents'>
        <?php
        foreach ($dbPostData as $key => $val) {
            debug($val['id'] . $val['uid'] . $val['post_title'] . $val['type_id'])
            ?>

            <a href="viewDetail.php<?php echo (!empty(appendGetParam())) ? appendGetParam() . '&p_id=' . $val['id'] : '?p_id=' . $val['id']; ?>">
                <img src="<?php echo showImg($val['pic1']); ?> ">
                <p><a href="otherProfile.php<?php echo '?u_id=' . $val['uid']; ?>"><img src="<?php echo showImg($val['pic']); ?>" class='postIcon'></a>　<?php echo ($val['post_title']); ?></p><br>
            </a>



        <?php
        }

        ?>

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
        }
    </script>
</body>

</html>