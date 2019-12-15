<?php

require('function.php');
require('menu_bar.php');

$dbCategoryData = getCategory();
$dbFormData = (!empty($p_id)) ? getViewPost($_SESSION['user_id'], $p_id) : '';


if (!empty($_POST)) {

    $title = $_POST['title'];
    $view_type = $_POST['view_type'];
    $when_see = $_POST['when_see'];
    $where_see = $_POST['where_see'];
    $comment = $_POST['comment'];
    $pic1 = (!empty($_FILES['pic1']['name'])) ? uploadImg($_FILES['pic1'], 'pic1') : '';
    $pic1 = (empty($pic1) && !empty($dbFormData['pic1'])) ? $dbFormData['pic1'] : $pic1;
    $pic2 = (!empty($_FILES['pic2']['name'])) ? uploadImg($_FILES['pic2'], 'pic2') : '';
    $pic2 = (empty($pic2) && !empty($dbFormData['pic2'])) ? $dbFormData['pic2'] : $pic2;
    $pic3 = (!empty($_FILES['pic3']['name'])) ? uploadImg($_FILES['pic3'], 'pic3') : '';
    $pic3 = (empty($pic3) && !empty($dbFormData['pic3'])) ? $dbFormData['pic3'] : $pic3;

    if (empty($dbFormData)) {
        validMust($title, 'title');
        validMust($view_type, 'view_type');
        validMust($when_see, 'when_see');
        validMust($where_see, 'where_see');
        validMust($comment, 'comment');
        validMaxPass($title, 'title');
    } else {
        if ($dbFormData['title'] !== $title) {
            validMust($title, 'title');
            validMaxPass($title, 'title');
        }
        if ($dbFormData['view_type'] !== $view_type) {
            validselect($view_type, 'view_type');
        }
        if ($dbFormData['comment'] !== $comment) {
            validMaxpass($comment, 'comment');
        }
    }
    if (empty($err_msg)) {
        debug('バリデーションOK（投稿）');
        try {
            $dbh = dbConnect();
            $sql = "INSERT INTO view_post (post_title,type_id,when_see,where_see,comment,pic1,pic2,pic3,user_id,create_date) VALUES (:title,:type,:when_see,:where_see,:comment,:pic1,:pic2,:pic3,:u_id,:date)";
            $data = array(':title' => $title, ':type' => $view_type, ':when_see' => $when_see, ':where_see' => $where_see, ':comment' => $comment, ':pic1' => $pic1, ':pic2' => $pic2, ':pic3' => $pic3, ':u_id' => $_SESSION['user_id'], ':date' => date('Y-m-d H:i:s'));
            $stmt = queryPost($dbh, $sql, $data);

            if ($stmt) {
                header('Location:mypage.php');
            }
        } catch (Exception $e) {
            error_log('エラー発生:' . $e->getMessage());
            $err_msg['common'] = MSG07;
        }
    }
}

?>
<?php
$siteTitle = '投稿　';
require('head.php');
?>

<body>
    <div class="contents">
        <h1>POST</h1>
        <form method='post' enctype='multipart/form-data'>
            <?php
            if (!empty($err_msg['common'])) echo $err_msg['common'];
            ?>

            <div class='input_form'>
                <?php if (!empty($err_msg['title'])) echo $err_msg['title']; ?><br>
                タイトル<input type='text' name='title' value="<?php if (!empty($_POST['title'])) echo $_POST['title']; ?>">
            </div>
            <div class="input_form">
                <?php if (!empty($err_msg['view_type'])) echo $err_msg['view_type']; ?><br>
                カテゴリ
                <select name='view_type'>
                    <option value='0'>選択してください</option>
                    <?php foreach ($dbCategoryData as $key => $val) { ?>
                        <option value="<?php echo $val['id'] ?>" <?php if (getFormData('type_id') == $val['id']) {
                                                                            echo 'selected';
                                                                        } ?>>
                            <?php echo $val['name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="input_form">
                <?php if (!empty($err_msg['when_see'])) echo $err_msg['when_see']; ?><br>
                日付<input type='date' name='when_see' value="<?php if (!empty($_POST['when_see'])) echo $_POST['when_see']; ?>">
            </div>
            <div class="input_form">
                <?php if (!empty($err_msg['where_see'])) echo $err_msg['where_see']; ?><br>
                場所<input type='text' name='where_see' value="<?php if (!empty($_POST['where_see'])) echo $_POST['where_see']; ?>">
            </div>

            <div class="input_form">
                <?php if (!empty($err_msg['comment'])) echo $err_msg['comment']; ?><br>
                感想<textarea type='text' name='comment'><?php if (!empty($_POST['comment'])) echo $_POST['comment']; ?></textarea>
            </div>
            <div class='input_form'>
                写真1
                <input type='file' name='pic1' class='postImgPost'><br>
                <img class='postImg' src=" <?php echo getFormData('pic1'); ?>" style="<?php if (empty(getFormData('pic1'))) echo 'display:none;' ?>">
            </div>
            <div class='input_form'>
                写真2
                <input type='file' name='pic2' class='postImgPost'><br>
                <img class='postImg' src=" <?php echo getFormData('pic2'); ?>" style="<?php if (empty(getFormData('pic2'))) echo 'display:none;' ?>">
            </div>
            <div class='input_form'>
                写真3
                <input type='file' name='pic3' class='postImgPost'><br>
                <img class='postImg' src=" <?php echo getFormData('pic3'); ?>" style="<?php if (empty(getFormData('pic3'))) echo 'display:none;' ?>">
            </div>
            <input type='submit' value='投稿'>
        </form>
    </div>
</body>

</html>