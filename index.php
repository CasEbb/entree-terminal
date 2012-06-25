<?php
if(isset($_GET['view'])) {
  require('bootstrap.php');
  // controller handelt verder alles af
  require('modules/' . $_GET['view'] . '/controller.php');
  require('modules/' . $_GET['view'] . '/view.php');
  // javascript
  echo "<script type=\"text/javascript\">\n";
  require('modules/' . $_GET['view'] . '/module.js');
  echo "</script>";
  // geluidjes
  if($handle = opendir('modules/'.$_GET['view'])) {
    while (false !== ($entry = readdir($handle))) {
      if (substr($entry, -4) == '.wav') {
        echo "<audio src=\"" . asset_path($entry) . "\" id=\"" . substr($entry, 0, -4) . "player\"></audio>";
      }
    }
  }
  closedir($handle);
} else {
?>
<!doctype html>
<html>
  <head> 
    <title>Streeplijst</title> 
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <link rel="stylesheet" href="assets/stylesheets/application.css" />
    <script src="assets/javascripts/jquery.js"></script>
    <script src="assets/javascripts/application.js"></script>
  </head> 
  <body>
    <div id="bar">
      <div id="logo">
        <img src="assets/images/logo.png" width="221" height="64" alt="Huize Entree" id="reload">
      </div>
      <div id="menu">
        <ul>
<?php
if($handle = opendir('modules')) {
  while (false !== ($entry = readdir($handle))) {
    if ($entry != "." && $entry != "..") {
      echo "          <li id=\"menu-$entry\" data-page=\"$entry\"><img src=\"modules/$entry/module.png\" width=\"64\" height=\"64\"></li>\n";
    }
  }
  closedir($handle);
}
?>
        </ul>
      </div>
    </div>
    <div id="content">
    </div>
    <div id="offline">
      Offline modus
    </div>
    <div id="clock"></div>
  </body>
</html>
<?php
}

// EOF
