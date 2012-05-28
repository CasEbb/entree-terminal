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
    
    if($response === false) {
      // uit de api cache!
      $query = mysql_query("SELECT `response` FROM `api_inbox` WHERE `controller`='" . $controller . "' AND `action`='" . $action . "' AND `format`='" . $format . "'");
      $response = mysql_result($query, 0);
    } else {
      // response opslaan/updaten
      mysql_query("REPLACE `api_inbox` (`controller`, `action`, `format`) VALUES ('" . $controller . "', '" . $action . "', '" . $format . "')");
    }
    
    return $response;
  }
  
  function post($data, $controller, $action = '', $format = 'json') {
    if($action === '') {
      curl_setopt($this->ch, CURLOPT_URL, $this->endpoint . '/api/' . $controller . '.' . $format);
    } else {
      curl_setopt($this->ch, CURLOPT_URL, $this->endpoint . '/api/' . $controller . '/' . $action . '.' . $format);
    }
    
    curl_setopt($this->ch, CURLOPT_POST, 1);
    curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
    $response = curl_exec($this->ch);
    
    if($response == false) {
      // cache de opdracht!
      mysql_query("INSERT INTO `api_outbux` (`controller`, `action`, `format`, `date`) VALUES ('" . $controller . "', '" . $action . "', '" . $format . "', '" . serialize($data) . "')");
    }
    
    return $response;
  }
  
  function get_json($controller, $action = '') {
    return json_decode($this->get($controller, $action, 'json'));
  }
  
}

// EOF
