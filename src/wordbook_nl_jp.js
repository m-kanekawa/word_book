const URL_BASE = "http://localhost:10089/api/v1.0/nl_jp/";
// const TYPE_JP_EN = {
//   動: "verb",
//   名: "noun",
//   形: "adjective",
//   副: "adverb",
//   接: "conjunction",
//   前: "preposition",
//   代: "pronoun",
//   間: "interjection",
//   冠: "article",
//   数: "numeral",
// };
var _current_result = [];
var _option = "";
var _token_id = "";

$(function () {
  /* Shortcut Keys */
  $(document).on("keydown", function (event) {
    var code = event.keyCode ? event.keyCode : event.which;

    // CTRL+F : find
    if (code == 70 && (event.ctrlKey || event.metaKey)) {
      event.preventDefault();
      on_search();
      return false;
    }

    // CTRL+R : register
    if (code == 82 && (event.ctrlKey || event.metaKey)) {
      event.preventDefault();
      on_register();
      return false;
    }
  });

  // Button Event
  $(document).on("click", "#dict_search", function () {
    on_search();
  });
  $(document).on("click", "#dict_show_register", function () {
    on_register();
  });
  $(document).on("click", "#dict_register", function () {
    send_register();
  });
  $(document).on("click", ".close", function () {
    $("#wb_modal").removeClass("active");
  });
  $(document).on("click", ".wb_modal", function (e) {
    if (!$(e.target).closest(".modal-content").length) {
      $("#wb_modal").removeClass("active");
    }
  });

  // Change Type
  $(".type_v").change(function () {
    show_v();
  });
  $(".type_n").change(function () {
    show_n();
  });
  $(".type_a").change(function () {
    show_a();
  });
  $(".type_o").change(function () {
    show_o();
  });

  // Init
  show_v();
});

function on_search() {
  send_search(function (word, result) {
    var txt = "";
    for (o of result) {
      txt = txt + o["nl"] + "[" + o["type"] + "] " + o["jp"] + "<br>";
    }
    if (txt != "") {
      if (_option == "tooltip") {
        add_tooltip(txt);
      } else if (_option == "ruby") {
        add_ruby(txt);
      }
    } else {
      hilight();
    }
  });
}
function on_register() {
  send_search(function (word, result) {
    clear_input();
    $("input[name=word]").val(word);

    create_patination(result);

    if (result.length > 0) {
      _current_result = result;
      change_page(1);
    } else {
      _current_result = [];
      show_v();
    }
    $("#wb_modal").addClass("active");
  });
}

function clear_input() {
  $("input[name=id]").val(0);
  $("input[name=type]").val(["動"]);
  $("input[name=v_h_z]").prop("checked", false);
  $("input[name=nl]").val("");
  $("input[name=jp]").val("");
  $("input[name=sample]").val("");
  $("input[name=n_de]").prop("checked", false);
  $("input[name=n_het]").prop("checked", false);
  $("input[name=n_pl]").val("");
  $("input[name=n_tje]").val("");
  $("input[name=v_pre_ik]").val("");
  $("input[name=v_pre_he]").val("");
  $("input[name=v_pp]").val("");
  $("input[name=v_past_ik]").val("");
  $("input[name=v_past_we]").val("");
  $("input[name=a_e]").val("");
  $("input[name=a_er]").val("");
  $("input[name=a_st]").val("");
}
function hide_all_input() {
  $("#wb_modal .v").hide();
  $("#wb_modal .n").hide();
  $("#wb_modal .a").hide();
}
function show_v() {
  hide_all_input();
  $("#wb_modal .v").show();
  $("input[name=nl]").val("");
}
function show_n() {
  hide_all_input();
  $("#wb_modal .n").show();
  $("input[name=nl]").val("");
}
function show_a() {
  hide_all_input();
  $("#wb_modal .a").show();
  $("input[name=nl]").val("");
}
function show_o() {
  hide_all_input();
  var word = $("input[name=word]").val();
  $("input[name=nl]").val(word);
}

function get_select_text() {
  var sel = window.getSelection();
  var txt = sel.rangeCount ? sel.toString() : "";
  return txt;
}

function clear_pagination() {
  $("#pagination").empty();
}
function clear_page_select() {
  $("#pagination")
    .children("li")
    .each(function (i) {
      $(this).removeClass("active");
    });
}
function create_patination(result) {
  clear_pagination();

  if (result.length > 0) {
    var li = $(
      "<li class='active' id='page_1'><button class='page-link' onclick='change_page(1)'>" +
        result[0]["type"] +
        "</button></li>"
    );
    $("#pagination").append(li);

    for (i = 2; i <= result.length; i++) {
      var li = $(
        "<li id='page_" +
          i +
          "'><button class='page-link' onclick='change_page(" +
          i +
          ")'>" +
          result[i - 1]["type"] +
          "</button></li>"
      );
      $("#pagination").append(li);
    }
    var li = $("<li id='page_0'><button class='page-link' onclick='new_page()'>New</button></li>");
    $("#pagination").append(li);
  } else {
    var li = $(
      "<li class='active' id='page_0'><button class='page-link' onclick='new_page()'>New</button></li>"
    );
    $("#pagination").append(li);
  }
}
function change_page(n) {
  var r = _current_result[n - 1];
  console.log("change_page", n, r);

  clear_page_select();
  $("#page_" + n).addClass("active");

  if (r["type"] == "名") {
    var de = r["de_het"].indexOf("de") >= 0;
    var het = r["de_het"].indexOf("het") >= 0;
    $("input[name=n_de]").prop("checked", de);
    $("input[name=n_het]").prop("checked", het);
    $("input[name=n_pl]").val(r["pl"]);
    $("input[name=n_tje]").val(r["tje"]);
    show_n();
  } else if (r["type"] == "動") {
    $("input[name=v_pre_ik]").val(r["pre_ik"]);
    $("input[name=v_pre_he]").val(r["pre_he"]);
    $("input[name=v_pp]").val(r["pp"]);
    $("input[name=v_past_ik]").val(r["past_ik"]);
    $("input[name=v_past_we]").val(r["past_we"]);
    show_v();
  } else if (r["type"] == "形") {
    $("input[name=a_e]").val(r["e"]);
    $("input[name=a_er]").val(r["er"]);
    $("input[name=a_st]").val(r["st"]);
    show_a();
  } else {
    show_o();
  }

  $("input[name=id]").val([r["id"]]);
  $("input[name=type]").val([r["type"]]);
  $("input[name=nl]").val(r["nl"]);
  $("input[name=jp]").val(r["jp"]);
  $("input[name=sample]").val(r["sample"]);
}
function new_page() {
  console.log("new_page");
  clear_page_select();
  clear_input();
  $("#page_0").addClass("active");
  show_v();
}

function hilight() {
  var sel = window.getSelection();
  if (!sel.rangeCount) return;

  var range = sel.getRangeAt(0);
  var newNode = document.createElement("span");
  newNode.setAttribute("class", "hilight");
  newNode.innerHTML = sel.toString();

  range.deleteContents();
  range.insertNode(newNode);
  sel.removeAllRanges();
}

function add_tooltip(text) {
  var sel = window.getSelection();
  if (!sel.rangeCount) return;

  var range = sel.getRangeAt(0);
  var newNode1 = document.createElement("span");
  var newNode2 = document.createElement("span");

  newNode1.setAttribute("class", "text-tooltip");
  newNode2.setAttribute("class", "tooltip-content");
  newNode1.innerHTML = sel.toString();
  newNode2.innerHTML = text;
  newNode1.append(newNode2);

  range.deleteContents();
  range.insertNode(newNode1);
  sel.removeAllRanges();
}

function add_ruby(text) {
  var sel = window.getSelection();
  if (!sel.rangeCount) return;

  var range = sel.getRangeAt(0);
  var ruby = document.createElement("ruby");
  var rb = document.createElement("rb");
  var rt = document.createElement("rt");

  rb.innerHTML = sel.toString();
  rt.innerHTML = text;
  ruby.appendChild(rb);
  ruby.appendChild(rt);

  range.deleteContents();
  range.insertNode(ruby);
  sel.removeAllRanges();
}

function send_search(callback) {
  var word = get_select_text();
  if (word == "") return;

  console.log("send_search", word);

  var data = {
    token: $(_token_id).val(),
    word: word,
  };
  console.log("data", data);

  $.ajax({
    url: URL_BASE + "search.php",
    type: "GET",
    data: data,
    dataType: "json",
  })
    .done(function (response) {
      console.log("done", response);
      callback(word, response["result"]);
    })
    .fail(function (xhr) {
      console.log("fail", xhr);
    });
}

function send_register() {
  $("#wb_modal").removeClass("active");

  if ($("input[name=n_de]").prop("checked") && $("input[name=n_het]").prop("checked"))
    var de_het = "de/het";
  else if ($("input[name=n_de]").prop("checked")) var de_het = "de";
  else if ($("input[name=n_het]").prop("checked")) var de_het = "het";
  else var de_het = "";

  var type = $("input[name=type]:checked").val();

  var data = {
    token: $(_token_id).val(),
    id: $("input[name=id]").val(),
    type: type,
    nl: $("input[name=nl]").val(),
    jp: $("input[name=jp]").val(),
    sample: $("input[name=sample]").val(),
    pre_ik: $("input[name=v_pre_ik]").val(),
    pre_he: $("input[name=v_pre_he]").val(),
    h_z: type == "動" ? $("input[name=v_h_z]:checked").val() : "",
    pp: $("input[name=v_pp]").val(),
    past_ik: $("input[name=v_past_ik]").val(),
    past_we: $("input[name=v_past_we]").val(),
    de_het: de_het,
    pl: $("input[name=n_pl]").val(),
    tje: $("input[name=n_tje]").val(),
    e: $("input[name=a_e]").val(),
    er: $("input[name=a_er]").val(),
    st: $("input[name=a_st]").val(),
  };
  console.log("data", data);

  $.ajax({
    url: URL_BASE + "register.php",
    type: "POST",
    data: data,
    dataType: "json",
  })
    .done(function (response) {
      console.log("done", response);
      alert(response["msg"]);
    })
    .fail(function (xhr) {
      console.log("fail", xhr);
    });
}

/**
 * Initialize Word Book
 * @param  {string} token_id - id for <input type=hidden> which keep a value of token
 * @return {string} option   - "tooltip" or "ruby"
 *                             How to show the meaning of the word
 */
function init_word_book(token_id, option) {
  console.log("init_dict", token_id, option);
  _token_id = "#" + token_id;
  _option = option;

  var elem = `
<div class="wb_modal" id="wb_modal">
  <div class="modal-wrapper">
    <div class="modal-content">
      <input type="hidden" name="id" value="">
      <div class="modal-header">
        <h5 class="modal_title">Register</h5>
      </div>
      <div class="modal-body">
        <div class="row">
          <label class='wide'>clicked word : </label>
          <input type="text" name="word" value="" readonly>
        </div>
        <div class="row">
          <label class='wide'>word : </label>
          <input type="text" name="nl" placeholder="word (Original form)" value="">
        </div>
        <div class="row">
          <label class='wide'>japanese : </label>
          <input type="text" name="jp" placeholder="japanese" value="">
        </div>
        <div class="row">
          <label class='wide'>sample : </label>
          <input type="text" name="sample" placeholder="sample" value="">
        </div>

        <nav>
          <ul class="pagination" id="pagination"></ul>
        </nav>

        <div class="group">
          <input type="radio" name="type" class="type_v" value="動">
          <label>動</label>

          <input type="radio" name="type" class="type_n" value="名">
          <label>名</label>

          <input type="radio" name="type" class="type_a" value="形">
          <label>形</label>

          <input type="radio" name="type" class="type_o" value="副">
          <label>副</label>

          <input type="radio" name="type" class="type_o" value="接">
          <label>"接</label>

          <input type="radio" name="type" class="type_o" value="前">
          <label>前</label>

          <input type="radio" name="type" class="type_o" value="代">
          <label>代</label>

          <input type="radio" name="type" class="type_o" value="間">
          <label>間</label>

          <input type="radio" name="type" class="type_o" value="冠">
          <label>冠</label>

          <input type="radio" name="type" class="type_o" value="数">
          <label>数</label>
        </div>

        <div class="group n">
          <input type="checkbox" name="n_de" value="de">
          <label>de</label>

          <input type="checkbox" name="n_het" value="het">
          <label>het</label>
        </div>

        <div class="group v">
          <div class="row">
            <label class='wide'>present (ik) : </label>
            <input type="text" name="v_pre_ik" placeholder="<use , for more than 1 word>" value="">
          </div>
          <div class="row">
            <label class='wide'>present (he) : </label>
            <input type="text" name="v_pre_he" placeholder="<use , for more than 1 word>" value="">
          </div>
        </div>

        <div class="group v">
          <input type="radio" name="v_h_z" value="H">
          <label>hebben</label>

          <input type="radio" name="v_h_z" value="Z">
          <label>zijn</label>
        </div>

        <div class="group v">
          <div class="row">
            <label class='wide'>present perfect : </label>
            <input type="text" name="v_pp" placeholder="<use , for more than 1 word>" value="">
          </div>
          <div class="row">
            <label class='wide'>past (ik) : </label>
            <input type="text" name="v_past_ik" placeholder="<use , for more than 1 word>" value="">
          </div>
          <div class="row">
            <label class='wide'>past (we) : </label>
            <input type="text" name="v_past_we" placeholder="<use , for more than 1 word>" value="">
          </div>
        </div>

        <div class="group n">
          <div class="row">
            <label class='wide'>plural : </label>
            <input type="text" name="n_pl" placeholder="<use , for more than 1 word>" value="">
          </div>
          <div class="row">
            <label class='wide'>tje : </label>
            <input type="text" name="n_tje" placeholder="<use , for more than 1 word>" value="">
          </div>
        </div>

        <div class="group a">
          <div class="row">
            <label class='wide'>e : </label>
            <input type="text" name="a_e" placeholder="<use , for more than 1 word>" value="">
          </div>
          <div class="row">
            <label class='wide'>comparative : </label>
            <input type="text" name="a_er" placeholder="<use , for more than 1 word>" value="">
          </div>
          <div class="row">
            <label class='wide'>superlative : </label>
            <input type="text" name="a_st" placeholder="<use , for more than 1 word>" value="">
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button class="close">Cancel</button>
        <button class="submit" id="dict_register">Save changes</button>
      </div>
    </div>
  </div>
</div>
`;
  $("body").append(elem);
}
