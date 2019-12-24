<?php

require('function.php');

if (!empty($_POST)) {
    global $err_msg;
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $pass_re = $_POST['pass_re'];

    validMust($email, 'email');
    validMust($pass, 'pass');
    validMust($pass_re, 'pass_re');
    debug('入力確認OK');

    if (empty($err_msg)) {
        validEmail($email, 'email');
        validEmailAlready($email);
        validMaxpass($pass, 'pass');
        validMinpass($pass, 'pass');
        validWordtype($pass, 'pass');
        debug('バリデーション01');

        if (empty($err_msg)) {
            validPassMatch($pass, $pass_re, 'pass_re');
            debug('バリデーション02');

            if (empty($err_msg)) {
                try {
                    $dbh = dbConnect();
                    $sql = 'INSERT INTO `users` (email,password,create_date,login_time) VALUES (:email, :pass,:create_date,:login_time)';
                    $data = array(':email' => $email, ':pass' => password_hash($pass, PASSWORD_DEFAULT), ':create_date' => date('Y-m-d H:i:s'), ':login_time' => date('Y-m-d H:i:s'));
                    $stmt = queryPost($dbh, $sql, $data);
                    debug('クエリOK');

                    if ($stmt) {
                        header("Location:mypage.php");
                    }
                } catch (Exception $e) {
                    error_log('エラー発生:' . $e->getMessage());
                    $err_msg['common'] = MSG07;
                }


                header("Location:mypage.php"); //マイページへ
            }
        }
    }
}


?>

<!DOCTYPE html>
<html lang="ja">

<?php
$siteTitle = 'アカウント登録　';
require('head.php');
?>

<body>
    <div class='content-box'>
        <div class="mycontents">
            <h1>アカウント登録</h1>
            <form method='post' action=''>
                <?php if (!empty($err_msg['common'])) echo $err_msg['common']; ?><br>
                <div class="input_form">

                    メールアドレス　<?php if (!empty($err_msg['email'])) echo $err_msg['email']; ?><br><input type='text' name='email' value="<?php if (!empty($_POST['email'])) echo $_POST['email']; ?>">
                </div>
                <div class="input_form">

                    パスワード　<?php if (!empty($err_msg['pass'])) echo $err_msg['pass']; ?><br><input type='password' name='pass' value="<?php if (!empty($_POST['pass'])) echo $_POST['pass']; ?>">
                </div>
                <div class="input_form">

                    パスワード（再入力）　<?php if (!empty($err_msg['pass_re'])) echo $err_msg['pass_re']; ?><br><input type='password' name='pass_re' value='<?php if (!empty($_POST['pass_re'])) echo $_POST['pass_re']; ?>'>
                </div>
                <br>
                <input type='submit' value='登録'>
            </form>
        </div>
    </div>

</body>

</html>