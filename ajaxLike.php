<?php
require('function.php');


$p_id = $_POST['viewid'];
try {
    $dbh = dbConnect();
    $sql = "SELECT * FROM view_like WHERE view_id=:p_id AND user_id=:u_id";
    $data = array(':u_id' => $_SESSION['user_id'], ':p_id' => $p_id);
    $stmt = queryPost($dbh, $sql, $data);
    $resultCount = $stmt->rowCount();
    if (!empty($resultCount)) {
        $sql = "DELETE FROM view_like WHERE view_id=:p_id AND user_id=:u_id";
        $data = array(':u_id' => $_SESSION['user_id'], ':p_id' => $p_id);
        $stmt = queryPost($dbh, $sql, $data);
    } else {
        $sql = "INSERT INTO view_like(view_id,user_id,create_date) VALUES (:p_id,:u_id,:date)";
        $data = array(':u_id' => $_SESSION['user_id'], ':p_id' => $p_id, ':date' => date('Y-m-d H:i:s'));
        $stmt = queryPost($dbh, $sql, $data);
    }
} catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
}
