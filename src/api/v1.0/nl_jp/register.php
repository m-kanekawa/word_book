<?php
include('../api.php');

/*
In
{
  "token"   : "02aa5b8caa29c721c64a997d7563f077e3b10032",
  "id"      : "0",
  "type"    : "数",
  "nl"      : "zzz",
  "jp"      : "あああ",
  "sample"  : "いいい",
  "pre_ik"  : "",
  "pre_he"  : "",
  "h_z"     : "",
  "pp"      : "",
  "past_ik" : "",
  "past_we" : "",
  "de_het"  : "",
  "pl"      : "",
  "tje"     : "",
  "e"       : "",
  "er"      : "",
  "st"      : "",
  "ere"     : "",
  "ste"     : "",
}

Out
{
  "word"   : "zzz",
  "msg"    : "INSERT zzz",
  "status" : "OK"
}
*/
class Register extends API
{
  function __construct()
  {
    parent::__construct();
    $this->log->debug('Register');
  }


  private function encode($text)
  {
    $text = str_replace(' ', '', $text);
    $text = str_replace('、', ',', $text);
    return $text;
  }

  function get_result()
  {
    // Read params
    $id       = $_POST['id'];
    $type     = $_POST['type'];
    $nl       = $_POST['nl'];
    $jp       = $this->encode($_POST['jp']);
    $sample   = $_POST['sample'];
    $pre_ik   = $this->encode($_POST['pre_ik']);
    $pre_he   = $this->encode($_POST['pre_he']);
    $h_z      = $this->encode($_POST['h_z']);
    $pp       = $this->encode($_POST['pp']);
    $past_ik  = $this->encode($_POST['past_ik']);
    $past_we  = $this->encode($_POST['past_we']);
    $de_het   = $this->encode($_POST['de_het']);
    $e        = $this->encode($_POST['e']);
    $er       = $this->encode($_POST['er']);
    $ere      = $this->encode($_POST['ere']);
    $st       = $this->encode($_POST['st']);
    $ste      = $this->encode($_POST['ste']);
    $pl       = $this->encode($_POST['pl']);
    $tje      = $this->encode($_POST['tje']);

    $this->log->debug('$id: ' . $id);
    $this->log->debug('$type: ' . $type);
    $this->log->debug('$nl: ' . $nl);
    $this->log->debug('$jp: ' . $jp);
    $this->log->debug('$sample: ' . $sample);
    $this->log->debug('$pre_ik: ' . $pre_ik);
    $this->log->debug('$pre_he: ' . $pre_he);
    $this->log->debug('$h_z: ' . $h_z);
    $this->log->debug('$pp: ' . $pp);
    $this->log->debug('$past_ik: ' . $past_ik);
    $this->log->debug('$past_we: ' . $past_we);
    $this->log->debug('$e: ' . $e);
    $this->log->debug('$er: ' . $er);
    $this->log->debug('$ere: ' . $ere);
    $this->log->debug('$st: ' . $st);
    $this->log->debug('$ste: ' . $ste);
    $this->log->debug('$de_het: ' . $de_het);
    $this->log->debug('$pl: ' . $pl);
    $this->log->debug('$tje: ' . $tje);

    // DB access
    $db = new SQLite3($this->db_path);
    $msg = ($id == "0")
      ? 'INSERT ' . $nl
      : 'UPDATE ' . $nl;

    $stmt = ($id == "0")
      ? $db->prepare("INSERT INTO word(
      type, nl, jp, sample, pre_ik, pre_he, h_z, pp, past_ik, past_we, de_het, e, er, ere, st, ste, pl, tje
    ) VALUES (
      :type, :nl, :jp, :sample, :pre_ik, :pre_he, :h_z, :pp, :past_ik, :past_we, :de_het, :e, :er, :ere, :st, :ste, :pl, :tje
    )")
      : $db->prepare("UPDATE word
      SET
        type    = :type,
        nl      = :nl,
        jp      = :jp,
        sample  = :sample,
        pre_ik  = :pre_ik,
        pre_he  = :pre_he,
        h_z     = :h_z,
        pp      = :pp,
        past_ik = :past_ik,
        past_we = :past_we,
        de_het  = :de_het,
        e       = :e,
        er      = :er,
        ere     = :ere,
        st      = :st,
        ste     = :ste,
        pl      = :pl,
        tje     = :tje
      WHERE
        id      = :id
    ");

    $stmt->bindValue(':id',      $id, SQLITE3_INTEGER);
    $stmt->bindValue(':type',    $type, SQLITE3_TEXT);
    $stmt->bindValue(':nl',      $nl, SQLITE3_TEXT);
    $stmt->bindValue(':jp',      $jp, SQLITE3_TEXT);
    $stmt->bindValue(':sample',  $sample, SQLITE3_TEXT);
    $stmt->bindValue(':pre_ik', ($type == '動') ? $pre_ik : '-', SQLITE3_TEXT);
    $stmt->bindValue(':pre_he', ($type == '動') ? $pre_he : '-', SQLITE3_TEXT);
    $stmt->bindValue(':h_z', ($type == '動') ? $h_z : '-', SQLITE3_TEXT);
    $stmt->bindValue(':pp', ($type == '動') ? $pp : '-', SQLITE3_TEXT);
    $stmt->bindValue(':past_ik', ($type == '動') ? $past_ik : '-', SQLITE3_TEXT);
    $stmt->bindValue(':past_we', ($type == '動') ? $past_we : '-', SQLITE3_TEXT);
    $stmt->bindValue(':e', ($type == '形') ? $e : '-', SQLITE3_TEXT);
    $stmt->bindValue(':er', ($type == '形') ? $er : '-', SQLITE3_TEXT);
    $stmt->bindValue(':ere', ($type == '形') ? $ere : '-', SQLITE3_TEXT);
    $stmt->bindValue(':st', ($type == '形') ? $st : '-', SQLITE3_TEXT);
    $stmt->bindValue(':ste', ($type == '形') ? $ste : '-', SQLITE3_TEXT);
    $stmt->bindValue(':de_het', ($type == '名') ? $de_het : '-', SQLITE3_TEXT);
    $stmt->bindValue(':pl', ($type == '名') ? $pl : '-', SQLITE3_TEXT);
    $stmt->bindValue(':tje', ($type == '名') ? $tje : '-', SQLITE3_TEXT);
    $query = $stmt->execute();

    if (!$query) {
      $this->error('sql execute failed');
    }
    if ($db->changes() == 0) {
      $this->error("record haven't changed");
    }

    return array(
      'word' => $nl,
      'msg'  => $msg,
    );
  }
}

$o = new Register();
$o->exec();
