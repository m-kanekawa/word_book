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

    // DB access
    $result = array();
    $db = new SQLite3($this->db_path);
    $sql = <<<EOM
SELECT * 
FROM word 
WHERE nl=:w 
OR pre_ik=:w 
OR pre_ik like :w1 
OR pre_ik like :w2 
OR pre_ik like :w3 
OR pre_he=:w 
OR pre_he like :w1 
OR pre_he like :w2 
OR pre_he like :w3 
OR pp=:w 
OR pp like :w1 
OR pp like :w2 
OR pp like :w3 
OR past_ik=:w 
OR past_ik like :w1 
OR past_ik like :w2 
OR past_ik like :w3 
OR past_we=:w 
OR past_we like :w1 
OR past_we like :w2 
OR past_we like :w3 
OR pl=:w 
OR pl like :w1 
OR pl like :w2 
OR pl like :w3 
OR e=:w 
OR e like :w1 
OR e like :w2 
OR e like :w3 
OR tje=:w 
OR tje like :w1
OR tje like :w2
OR tje like :w3
EOM;

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':w', $word);
    $stmt->bindValue(':w1', $word . ',%');
    $stmt->bindValue(':w2', '%,' . $word . ',%');
    $stmt->bindValue(':w3', '%,' . $word);
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
