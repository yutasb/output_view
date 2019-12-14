<?php

require('function.php');

debug('ログアウト');
session_destroy();
header("Location:top.php");
