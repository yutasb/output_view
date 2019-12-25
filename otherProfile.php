<?php
require('function.php');
require('menu_bar.php');

$u_id = (!empty($_GET['u_id'])) ? $_GET['u_id'] : '';
$myPostData = getMyPost($u_id);
$checkOther = checkOtherProfile($u_id);
$p_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '';



if (!empty($_POST['toMsg'])) {
    try {
        $dbh = dbConnect();
        $sql = 'INSERT INTO board(follower_user,follow_user,create_date) VALUES(:wer_uid,:w_uid,:date)';
        $data = array(':wer_uid' => $u_id, ':w_uid' => $_SESSION['user_id'], ':date' => date('Y-m-d H:i:s'));
        $stmt = queryPost($dbh, $sql, $data);
        if ($stmt) {
            debug('クエリOK（メッセージ）');
            header('Location:msg.php?m_id=' . $dbh->lastInsertId());
        }
    } catch (Exception $e) {
        debug('（クエリ失敗（メッセージ）');
        error_log('エラー発生:' . $e->getMessage());
    }
}

?>
<?php
$siteTitle = 'プロフィール';
require('head.php');
?>

<body>
    <div class='contents'>
        <form method='post'>
            <img src="<?php echo showImg($checkOther['pic']); ?>" class='profImg'><br><br>
            　<?php echo $checkOther['username']; ?><br><br>
            お気に入りの絶景：<?php echo $checkOther['likeView']; ?><br><br>
            <?php echo nl2br($checkOther['myself']); ?><br><br>

        </form>

        <?php
        if (!empty($myPostData)) :
            foreach ($myPostData as $key => $val) :
                debug($val['id']);
                ?>
                <a href="viewDetail.php?p_id=<?php echo $val['id']; ?>">
                    <img src=" <?php echo showImg($val['pic1']); ?> ">
                    <p><?php echo ($val['post_title']); ?></p>
                </a>


        <?php
            endforeach;
        endif;
        ?>
    </div>
</body>

</html>