<?php
class EntreeAPI {

  private $endpoint = '';
  private $ch;

  function __construct($endpoint) {
    $this->endpoint = $endpoint;
    $this->ch       = curl_init();
    curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, 2);
  }
  
  function __deconstruct() {
    curl_close($this->ch);
  }

  function get($controller, $action = '', $format = 'json') {
    if($action === '') {
      curl_setopt($this->ch, CURLOPT_URL, $this->endpoint . '/api/' . $controller . '.' . $format);
    } else {
      curl_setopt($this->ch, CURLOPT_URL, $this->endpoint . '/api/' . $controller . '/' . $action . '.' . $format);
    }
    
    $response = curl_exec($this->ch);
    $query = mysql_query("SELECT `response` FROM `apicache` WHERE `controller`='" . $controller . "' AND `action`='" . $action . "' AND `format`='" . $format . "'");
    
    if($response === false) {
      // uit de api cache!
      $query = mysql_query("SELECT `response` FROM `apicache` WHERE `controller`='" . $controller . "' AND `action`='" . $action . "' AND `format`='" . $format . "'");
      $response = mysql_result($query, 0);
    } else {
      // response opslaan/updaten
      if(mysql_num_rows($query) === 0) {
        mysql_query("INSERT INTO `apicache` (`controller`, `action`, `format`) VALUES ('" . $controller . "', '" . $action . "', '" . $format . "')");
      } else {
        mysql_query("UPDATE `apicache` SET `response`='" . $response . "' WHERE `controller`='" . $controller . "' AND `action`='" . $action . "' AND `format`='" . $format . "'");
      }
    }
    
    return $response;
  }
  
  function get_json($controller, $action = '') {
    return json_decode($this->get($controller, $action, 'json'));
  }
  
}
?>
