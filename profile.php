<?php

require('function.php');
$dbFormData = getUser($_SESSION['user_id']);
debug('取得したユーザー:' . print_r($dbFormData, true));

if (!empty($_POST)) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $tel = $_POST['tel'];
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
            $sql = "UPDATE users SET username=:u_name, email=:email, tel=:tel, pic=:pic, myself=:myself WHERE id=:u_id";
            $data = array(':u_name' => $username, ':email' => $email, ':tel' => $tel, ':pic' => $pic, ':myself' => $myself, ':u_id' => $dbFormData['id']);
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
    <h1>プロフィール編集</h1>
    <form method='post' enctype="multipart/form-data">
        <?php
        if (!empty($err_msg['common'])) echo $err_msg['common'];
        ?>

        <div class='input_form'>
            プロフィール画像
            <input type='file' name='pic' class='profImgPost' style="<?php if (empty(getFormData('pic'))) echo 'display:none;' ?>"><br>
            <img class='profImg' src=" <?php echo getFormData('pic'); ?>" style="<?php if (empty(getFormData('pic'))) echo 'display:none;' ?>">
        </div>
        <div class='input_form'>
            <?php if (!empty($err_msg['username'])) echo $err_msg['username']; ?>
            ユーザー名<input type='text' name='username' value=" <?php echo getFormData('username'); ?>">
        </div>
        <div class='input_form'>
            <?php if (!empty($err_msg['email'])) echo $err_msg['email']; ?>
            メールアドレス<input type='text' name='email' value="<?php echo getFormData('email'); ?>">
        </div>
        <div class='input_form'>
            <?php if (!empty($err_msg['tel'])) echo $err_msg['tel']; ?>
            電話番号<input type='text' name='tel' value="<?php echo getFormData('tel'); ?>">
        </div>
        <div class='input_form'>
            <?php if (!empty($err_msg['myself'])) echo $err_msg['myself']; ?>
            自己紹介<textarea type='text' name='myself'><?php echo getFormData('myself'); ?></textarea>
        </div>
        <input type='submit' value='変更する'>
    </form>
</body>

</html>