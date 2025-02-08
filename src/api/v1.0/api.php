<?php
require_once("../../../logger/config.php");
require_once("../../../logger/logger.php");

session_start();
header("Content-Type: application/json; charset=UTF-8;");

abstract class API
{
  abstract protected function get_result();

  protected $log;
  protected $db_path;

  function __construct()
  {
    $this->log = Logger::getInstance();
  }

  public function exec()
  {
    $this->check_token();
    $this->read_setteing();

    $json = $this->get_result();
    $json['status'] = 'OK';
    echo json_encode($json);
  }

  protected function error($msg)
  {
    echo json_encode(array(
      'status' => 'NG',
      'msg'    => $msg,
    ));
    exit;
  }

  protected function check_token()
  {
    $token = '';
    if (isset($_GET['token']))
      $token = $_GET['token'];
    else if (isset($_POST['token']))
      $token = $_POST['token'];
    else
      $this->error('prohibid');

    $this->log->debug('token:' . $token);
    $this->log->debug('word_book_session_key:' . $_SESSION['word_book_session_key']);

    if ($_SESSION['word_book_session_key'] !== $token)
      $this->error('token is not valid');
  }

  protected function read_setteing()
  {
    $filename  = "./setting.json";
    $text_json = file_get_contents($filename);
    $j         = json_decode($text_json, true);
    if (!file_exists($j["db_path"])) {
      $this->error("database file doesn't exists at " . $j["db_path"]);
    }
    $this->log->debug('db_path: ' . $j["db_path"]);
    $this->db_path = $j["db_path"];
  }
}
