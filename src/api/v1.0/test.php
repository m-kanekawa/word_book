<?php
require_once("../../logger/config.php");
require_once("../../logger/logger.php");

$log = Logger::getInstance();
$log->debug('test.php');

echo json_encode(array(
  'status' => 'OK',
));
