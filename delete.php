<?php
require('function.php');


if (!empty($_POST)) {
    debug('POST送信OK（退会）');
    try {
        $dbh = dbConnect();
        $sql1 = "UPDATE users SET delete_flg=1 WHERE id=:us_id";
        $sql2 = "UPDATE view_post SET delete_flg=1 WHERE user_id=:us_id";
        $data = array(':us_id' => $_SESSION['user_id']);
        $stmt1 = queryPost($dbh, $sql1, $data);
        $stmt2 = queryPost($dbh, $sql2, $data);
        debug('クエリ準備OK（退会）');
        if ($stmt1) {
            debug('クエリ成功（退会）');
            session_destroy();
            header('Location:top.php');
        } else {
            debug('クエリ失敗（退会）');
            $err_msg['common'] = MSG07;
        }
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = MSG07;
    }
}
?>
<?php
$siteTitle = '退会　';
require('head.php');
?>

<div class='contents'>
    <form method='post'>
        <h1>退会</h1>
        <?php
        if (!empty($err_msg['common'])) echo $err_msg['common'];
        ?>
        <input type='submit' value='退会する' name='submit'>
        <br>
        <a href='mypage.php'>&lt;　マイページへ</a>
    </form>
</div>

</html>