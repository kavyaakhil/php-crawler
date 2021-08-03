$(document).ready( function() {

  var form = $("#crawlerForm");
  form.on("submit", function(event) {
    // show loading spinner
    $('#formSpinner').removeClass('d-none');
    $('#formSpinner').addClass('d-block');

    var formData = {
      web_url: $("#urlInput").val(),
    };
    $.ajax({
       url: form.attr("action"),
       data: formData,
       type: "POST",
      }).done(function (data) {
        $('#formSpinner').removeClass('d-block');
        $('#formSpinner').addClass('d-none');
        $("#result").html('');
        var data = JSON.parse(data);
        var message = $("<div></div>").addClass("alert");

        if(data.status === "success") {
          var resultdiv = "<div class='row'><ul class='list-group'>";
          resultdiv += "<li class='list-group-item'>Number of unique images: "+ data.number_of_unique_imgs + "</li>";
          resultdiv += "<li class='list-group-item'>Number of unique internal links: "+ data.number_of_unique_internal_links + "</li>";
          resultdiv += "<li class='list-group-item'>Number of unique external links: "+ data.number_of_unique_external_links + "</li>";
          resultdiv += "<li class='list-group-item'>Avg page load: "+ data.avg_page_load_time + "</li>";
          resultdiv += "<li class='list-group-item'>Avg word count: "+ data.avg_word_count + "</li>";
          resultdiv += "<li class='list-group-item'>Avg title length: "+ data.avg_title_length + "</li></ul><br><br>";

          resultdiv += '<table class="table"><thead><tr><th scope="col">Url</th><th scope="col">Status code</th></tr></thead>';
          resultdiv += '<tbody>';
          $.each(data, function(k, v) {
            if(k == 'results') {
              $.each(v, function(key, value) {
                resultdiv += '<tr><td>'+ value.url +'</td><td>'+ value.status_code +'</td></tr>';
              });
            }
          });
          resultdiv += '</tbody></table>';
          resultdiv += "</div>";

          $("#result").append(resultdiv);
        }
        else if(data.status === "error") {
            message.append('<div class="error text-danger">'+data.message+'</div>');
            message.appendTo(form);
            window.setTimeout(function(){ message.fadeOut("slow") }, 3000 );
        } 
  });
  event.preventDefault();
  });
});