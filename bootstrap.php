<?php
// configuratie opties
require 'config.php';
// api interface
require 'lib/entree_api.php';

// sessie opstarten
session_start();

// mysql verbinding maken
mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD);
mysql_select_db(MYSQL_DATABASE);

$api = new EntreeAPI(BACKEND);

function asset_path($filename) {
  return 'modules/' . $_GET['view'] . '/' . $filename;
}

// EOF
