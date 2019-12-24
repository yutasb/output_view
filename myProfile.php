<?php

require('function.php');
require('menu_bar.php');
$dbFormData = getUser($_SESSION['user_id']);
debug('取得したユーザー:' . print_r($dbFormData, true));

if (!empty($_POST)) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $tel = $_POST['tel'];
    $likeView = $_POST['likeView'];
    $myself = $_POST['myself'];
    $pic = (!empty($_FILES['pic']['name'])) ? uploadImg($_FILES['pic'], 'pic') : '';
    $pic = (empty($pic) && !empty($dbFormData['pic'])) ? $dbFormData['pic'] : $pic;

    if ($dbFormData['username'] !== $username) {
        validMaxpass($username, 'username');
    }
    if ($dbFormData['email'] !== $email) {
        validEmail($email, 'email');
        if (empty($err_msg)) {
            validEmailAlready($email);
        }
        validMust($email, 'email');
    }

    if (empty($err_msg)) {
        debug('バリデーションOK（プロフ編集）');

        try {
            $dbh = dbConnect();
            $sql = "UPDATE users SET username=:u_name, email=:email, tel=:tel, pic=:pic,likeView=:likeView, myself=:myself WHERE id=:u_id";
            $data = array(':u_name' => $username, ':email' => $email, ':tel' => $tel, ':pic' => $pic, ':likeView' => $likeView, ':myself' => $myself, ':u_id' => $dbFormData['id']);
            $stmt = queryPost($dbh, $sql, $data);
            if ($stmt) {
                header("Location:mypage.php");
            }
        } catch (Exception $e) {
            error_log('エラー発生:' . $e->getMessage());
            $err_msg['common'] = MSG07;
        }
    }
}
?>
<?php
$siteTitle = 'プロフィール編集　';
require('head.php');
?>

<body>
    <div class="contents">
        <h1>プロフィール編集</h1>
        <form method='post' enctype="multipart/form-data">
            <?php
            if (!empty($err_msg['common'])) echo $err_msg['common'];
            ?>

            <div class='input_form'>
                プロフィール画像<br>
                <input type='file' name='pic' class='profImgPost'><br>
                <img class='profImg' src=" <?php echo getFormData('pic'); ?>" style="<?php if (empty(getFormData('pic'))) echo 'display:none;' ?>">
            </div>
            <div class='input_form'>
                <?php if (!empty($err_msg['username'])) echo $err_msg['username']; ?>
                ユーザー名<br><input type='text' name='username' value="<?php echo getFormData('username'); ?>">
            </div>
            <div class='input_form'>
                <?php if (!empty($err_msg['email'])) echo $err_msg['email']; ?>
                メールアドレス<br><input type='text' name='email' value="<?php echo getFormData('email'); ?>">
            </div>
            <div class='input_form'>
                <?php if (!empty($err_msg['tel'])) echo $err_msg['tel']; ?>
                電話番号<br><input type='text' name='tel' value="<?php echo getFormData('tel'); ?>">
            </div>
            <div class='input_form'>
                <?php if (!empty($err_msg['likeView'])) echo $err_msg['likeView']; ?>
                お気に入りの絶景<br><input type='text' name='likeView' value="<?php echo getFormData('likeView'); ?>">
            </div>
            <div class='input_form'>
                <?php if (!empty($err_msg['myself'])) echo $err_msg['myself']; ?>
                自己紹介<br><textarea cols='85' rows='12' type='text' name='myself'><?php echo getFormData('myself'); ?></textarea>
            </div>
            <br>
            <input type='submit' value='変更する'>
        </form>
        <a href='logout.php'>Logout</a>　|　<a href='delete.php'>退会する</a>
    </div>
</body>

</html>