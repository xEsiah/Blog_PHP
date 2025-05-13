<?php
session_start();
session_unset();
session_destroy();

header(header: 'Location: http://localhost/Projet_PHP_BLOG/public/index.php');
exit;