<?php
// configuratie opties
require 'config.php';
// api interface
require 'lib/entree_api.php';

// mysql verbinding maken
mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD);
mysql_select_db(MYSQL_DATABASE);

$api = new EntreeAPI(BACKEND);
?>