<?php
session_start();
header('X-Frame-Options: DENY');
if (!isset($_SESSION['word_book_session_key'])) {
  $token = sha1(uniqid(rand(), true));
  $_SESSION['word_book_session_key'] = $token;
} else {
  $token = $_SESSION['word_book_session_key'];
}
?>


<!DOCTYPE html>
<html>

<head>
  <title>Demo</title>
  <link rel="stylesheet" href="reset.css">
  <link rel="stylesheet" href="index.css">
  <link rel="stylesheet" href="wordbook.css">
</head>

<body>
  <div id="index">
    <h1>Demo for "Create Your Own Word Book"</h1>

    <h3>Some Dutch Words</h3>
    <table>
      <tr>
        <td width='100px'>nouns</td>
        <td>bestaan aanbieding aanbiedingen boekje </td>
      </tr>
      <tr>
        <td>adjectives</td>
        <td>apart aparte blind</td>
      </tr>
      <tr>
        <td>verbs</td>
        <td>doe doet doen gedaan deed deden</td>
      </tr>
      <tr>
        <td>others</td>
        <td>eer verdomme veertig niks zzz</td>
      </tr>
    </table>


    <h3>Paste Dutch Text to Below</h3>
    <div>
      <textarea rows="8" id="sample_textarea"></textarea>
      <div id="sample_text"></div>
    </div>
    <button id="clear_textarea">Clear</button>


    <h3>Look up for the meaning of a word</h3>
    <ol>
      <li>Select a dutch word from this page</li>
      <li>Enter Ctrl+F or click the button below</li>
    </ol>

    <button id="dict_search">Ctrl+F : Find a word</button>

    <p>
      If the word exists in your Word Book, you'll see
      <span class="text-tooltip">
        someword
        <span class="tooltip-content">meaning</span>
      </span>.
    </p>

    <p>
      Otherwise, you'll see
      <span class="hilight">someword</span>.
    </p>


    <h3>Register a word into Word Book</h3>
    <ol>
      <li>Select a dutch word from this page</li>
      <li>Enter Ctrl+R or click the button below</li>
    </ol>

    <button id="dict_show_register">Ctrl+R : Register a word</button>
  </div>

  <input type="hidden" id="word_book_token" value="<?= $token ?>">
  <script
    src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
    crossorigin="anonymous">
  </script>
  <script src="wordbook_nl_jp.js"></script>
  <script src="index.js"></script>
  <script>
    init_word_book('word_book_token', 'tooltip');
  </script>
</body>

</html>