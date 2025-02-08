<?php
include('../api.php');

/*
In
{
  "token": "02aa5b8caa29c721c64a997d7563f077e3b10032",
  "word" : "apart"
}

Out
{
    "word": "eer",
    "result": [
        {
            "id"      : 534,
            "type"    : "名",
            "de_het"  : "de",
            "nl"      : "eer",
            "pre_ik"  : "-",
            "pre_he"  : "-",
            "h_z"     : "-",
            "pp"      : "-",
            "past_ik" : "-",
            "past_we" : "-",
            "e"       : "-",
            "pl"      : "-",
            "tje"     : null,
            "jp"      : "名誉",
            "sample"  : null
        },
    ],
    "status": "OK"
}
*/
class Search extends API
{
  function __construct()
  {
    parent::__construct();
    $this->log->debug('Search');
  }

  function get_result()
  {
    // Read params
    $word  = strtolower($_GET['word']);
    $this->log->debug('$word: ' . $word);

    // DB access
    $result = array();
    $db = new SQLite3($this->db_path);
    $sql = 'SELECT * FROM word WHERE nl=:w OR pre_ik=:w OR pre_he=:w OR pp=:w OR past_ik=:w OR past_we=:w OR pl=:w OR e=:w OR tje=:w';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':w', $word);
    $a = $stmt->execute();
    while ($row = $a->fetchArray()) {
      $result[] = $row;
    }

    return array(
      'word'   => $word,
      'result' => $result,
    );
  }
}

$o = new Search();
$o->exec();
