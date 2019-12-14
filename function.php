 <?php

    ini_set('log_errors', 'on');
    ini_set('error_log', 'php.log');

    //デバッグ用
    $debug_flg = true;
    function debug($str)
    {
        global $debug_flg;
        if (!empty($debug_flg)) {
            error_log('デバッグ:' . $str);
        }
    }

    session_start();
    session_regenerate_id();

    //定数管理
    define('MSG01', '入力必須です');
    define('MSG02', 'Emailの形式で入力してください');
    define('MSG03', 'このメールアドレスはすでに登録されています');
    define('MSG04', '255文字以内で入力してください');
    define('MSG05', '6文字以上で入力してください');
    define('MSG06', '半角英数字のみご利用いただけます');
    define('MSG07', 'エラーが発生しました。しばらく経ってからやり直してください');
    define('MSG08', 'パスワード（再入力）が一致していません');
    define('MSG09', 'メールアドレスまたはパスワードが違います');


    //バリデーション
    $err_msg = array();

    //未入力
    function validMust($str, $key)
    {
        if ($str === '') {
            global $err_msg;
            $err_msg[$key] = MSG01;
        }
    }

    //Emailの形式不備
    function validEmail($str, $key)
    {
        if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)) {
            global $err_msg;
            $err_msg[$key] = MSG02;
        }
    }

    function validEmailAlready($email)
    {
        global $err_msg;
        try {
            $dbh = dbConnect();
            $sql = "SELECT count(*) FROM users WHERE email=:email AND delete_flg=0";
            $data = array(':email' => $email);
            $stmt = queryPost($dbh, $sql, $data);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!empty(array_shift($result))) {
                $err_msg['email'] = MSG03;
            }
        } catch (Exception $e) {
            error_log('エラー発生' . $e->getMessage());
            $err_msg['common'] = MSG07;
        }
    }

    //最大文字数
    function validMaxpass($str, $key, $max = 255)
    {
        global $err_msg;
        if (mb_strlen($str) > $max) {
            $err_msg[$key] = MSG04;
        }
    }
    //パスワードの最小文字数
    function validMinpass($str, $key, $min = 6)
    {
        global $err_msg;
        if (mb_strlen($str) < $min) {
            $err_msg[$key] = MSG05;
        }
    }
    //半角英数字チェック
    function validWordtype($str, $key)
    {
        if (!preg_match("/^[a-zA-Z0-9]+$/", $str)) {
            global $err_msg;
            $err_msg[$key] = MSG06;
        }
    }

    //パスワード一致確認
    function validPassMatch($str, $str2, $key)
    {
        if ($str !== $str2) {
            global $err_msg;
            $err_msg[$key] = MSG08;
        }
    }


    function dbConnect()
    {
        $dsn = 'mysql:dbname=superp_view;host=localhost;charset=utf8';
        $user = 'root';
        $password = 'root';
        $options = array(
            // SQL実行失敗時にはエラーコードのみ設定
            PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
            // デフォルトフェッチモードを連想配列形式に設定
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // バッファードクエリを使う(一度に結果セットをすべて取得し、サーバー負荷を軽減)
            // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        );
        // PDOオブジェクト生成（DBへ接続）
        $dbh = new PDO($dsn, $user, $password, $options);
        return $dbh;
    }


    function queryPost($dbh, $sql, $data)
    {
        $stmt = $dbh->prepare($sql);
        if ($stmt->execute($data)) {
            debug('クエリ成功02');
            return $stmt;
        } else {
            debug('クエリ失敗02');
            $err_msg['common'] = MSG07;
            return 0;
        }
    }

    function getUser($u_id)
    {
        debug('ユーザー情報取得');
        try {
            $dbh = dbConnect();
            $sql = "SELECT * FROM users WHERE id=:u_id AND delete_flg=0";
            $data = array(':u_id' => $u_id);
            $stmt = queryPost($dbh, $sql, $data);

            if ($stmt) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        } catch (Exception $e) {
            error_log('エラー発生:' . $e->getMessage());
        }
    }

    function getFormData($str, $flg = false)
    {
        if ($flg) {
            $method = $_GET;
        } else {
            $method = $_POST;
        }
        global $dbFormData;
        global $err_msg;

        if (!empty($dbFormData)) {
            if (!empty($err_msg[$str])) {
                if (isset($method[$str])) {
                    return $method[$str];
                } else {
                    return $dbFormData[$str];
                }
            } else {
                if (isset($method[$str]) && $method[$str] !== $dbFormData[$str]) {
                    return $method[$str];
                } else {
                    return $dbFormData[$str];
                }
            }
        } else {
            if (isset($method[$str])) {
                return $method[$str];
            }
        }
    }

    function uploadImg($file, $key)
    {
        debug('画像アップロード処理');
        if (isset($file['error']) && is_int($file['error'])) {
            try {
                switch ($file['error']) {
                    case UPLOAD_ERR_OK;
                        break;
                    case UPLOAD_ERR_NO_FILE;
                        throw new RuntimeException('ファイルが選択させていません');
                    default;
                        throw new RuntimeException('その他のエラーが発生しました');
                }
                $type = @exif_imagetype($file['tmp_name']);
                if (!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) {
                    throw new RuntimeException(('画像形式が未対応です'));
                }
                $path = 'img/' . sha1_file($file['tmp_name']) . image_type_to_extension($type);
                if (!move_uploaded_file($file['tmp_name'], $path)) {
                    throw new RuntimeException('ファイル保存時にエラーが発生しました');
                }
                chmod($path, 0644);

                return $path;
            } catch (RuntimeException $e) {
                global $err_msg;
                $err_msg[$key] = $e->getMessage();
            }
        }
    }
