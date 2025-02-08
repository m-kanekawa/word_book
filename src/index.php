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
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
    crossorigin="anonymous">
  <link rel="stylesheet" href="wordbook.css">

</head>

<body>
  <div class="container">
    <h1>Demo for "Create Your Own Word Book"</h1>

    <div class="row">
      <div class="col">
        <div class="card">
          <h5 class="card-header">Some Dutch Text</h5>
          <div class="card-body">
            <table class="table">
              <tr>
                <td>nouns</td>
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
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card">
          <h5 class="card-header">Paste Some Dutch Text to Below
            <button type="button" class="btn btn-primary" id="clear_textarea">Clear</button>
          </h5>
          <div class="card-body">
            <textarea class="form-control" rows="8" id="sample_textarea"></textarea>
            <div id="sample_text"></div>
          </div>
        </div>
      </div>
    </div>

    <h3>Look up for the meaning of a word</h3>
    <ol>
      <li>Select a dutch word from this page</li>
      <li>Enter Ctrl+F or click the button below</li>
    </ol>

    <div class="m-3">
      <button type="button" class="btn btn-primary" id="dict_search">Ctrl+F : Find a word</button>
    </div>

    <p>
      If the word exists in your Word Book, you'll see
      <span class="text-tooltip">
        someword
        <span class="tooltip-content">meaning</span>
      </span>
      .
    </p>

    <p>
      Otherwise, you'll see
      <span class="hilight">someword</span> .
    </p>

    <h3>Register a word into Word Book</h3>
    <ol>
      <li>Select a dutch word from this page</li>
      <li>Enter Ctrl+R or click the button below</li>
    </ol>

    <div class="m-3">
      <button type="button" class="btn btn-primary" id="dict_show_register">Ctrl+R : Register a word</button>
    </div>

    <input type="hidden" id="word_book_token" value="<?= $token ?>">
  </div>

  <script
    src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
    crossorigin="anonymous">
  </script>
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous">
  </script>
  <script src="wordbook_nl_jp.js"></script>
  <script src="index.js"></script>
  <script>
    init_word_book('word_book_token', 'tooltip');
  </script>
</body>

</html>