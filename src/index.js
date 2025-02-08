$(function () {
  $(document).on("click", "#clear_textarea", function () {
    $("#sample_textarea").val("");
    $("#sample_textarea").show();
    $("#sample_text").hide();
  });

  $("#sample_textarea").on("paste", function (e) {
    var paste = e.originalEvent.clipboardData.getData("text");
    const txt = paste.replace(/\n/g, "<br>");
    $("#sample_textarea").hide();
    $("#sample_text").show();
    $("#sample_text").append(txt);
  });
});
