<?php

require('function.php');


if (!empty($_POST)) {
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $save_pass = (!empty($_POST['save_pass'])) ? true : false;

    try {
        $dbh = dbConnect();
        $sql = "SELECT password,id FROM users WHERE email=:email AND delete_flg=0";
        $data = array(':email' => $email);
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($result) && password_verify($pass, array_shift($result))) {
            debug('パスワードがマッチ');
            //ログイン有効期限（1時間（60秒×60））
            $sesLimit = 60 * 60;
            //最終ログインを現在日時に
            $_SESSION['login_data'] = time();
            if ($save_pass) {
                debug('ログイン保持チェックあり');
                $_SESSION['login_limit'] = $sesLimit * 24 * 30;
            } else {
                debug('ログイン保持チェックなし');
                $_SESSION['login_limit'] = $sesLimit;
            }
            $_SESSION['user_id'] = $result['id'];
            header('Location:mypage.php');
            debug('セッション変数の中身：' . print_r($_SESSION, true));
        } else {
            debug('パスワードがアンマッチ');
            $err_msg['common'] = MSG09;
        }
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = MSG07;
    }
}

?>
<?php
$siteTitle = 'ログイン　';
require('head.php')
?>

<body>

    <div class='contents'>
        <h1>ログイン</h1>
        <form method='post' action=''>
            <?php if (!empty($err_msg['common'])) echo $err_msg['common']; ?><br>
            <div class='input_form'>
                <?php if (!empty($err_msg['email'])) echo $err_msg['email']; ?><br>
                メールアドレス<input type='text' name='email' value="<?php if (!empty($_POST['email'])) echo $_POST['email']; ?>">
            </div>
            <div class="input_form">
                <?php if (!empty($err_msg['pass'])) echo $err_msg['pass']; ?><br>
                パスワード<input type='password' name='pass' value="<?php if (!empty($_POST['pass'])) echo $_POST['pass']; ?>">
            </div>
            <label>
                <input type='checkbox' name='save_pass'>次回ログインを省略する<br>
            </label>


            <input type='submit' value='ログイン'>
        </form>
    </div>
</body>

</html>