<!-- 投稿一覧ページ -->
<?php
require('function.php');
require('menu_bar.php');
$u_id = $_SESSION['user_id'];
$category = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';
$dbPostData = getMyPostList($category);
$dbCategoryData = getCategory();

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
            <p>カテゴリー</p>
            <select name='c_id'>
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
            </select>
            <input type='submit' value='検索'>
        </form>
    </div>
    <div class='contents'>
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
</body>

</html>