<?php
session_start();
session_unset();
session_destroy();

header(BASE_URL . '/index.php');
exit;