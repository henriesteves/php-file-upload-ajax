$(document).ready(function() {
  var sendFiles = $('#sendFiles');
  var progressBar = $('#progress-bar');
  var status = $('#status');
  var msg = $('#msg');
  var file1 = $("#file-1")[0].files[0];

  sendFiles.click(function (e) {
    e.preventDefault();

    var form_data = new FormData();
    form_data.append('file', document.getElementById('file-1').files[0]);

    $.ajax({
      type: 'POST',
      url: 'file-parser.php',
      data: form_data,
      // cache: false,
      contentType: false,
      processData: false,
      enctype: 'multipart/form-data',
      xhr: function() {

        var  xhr = $.ajaxSettings.xhr();

        xhr.upload.onprogress = function(ev) {
          if (ev.lengthComputable) {
            var percentComplete = parseInt((ev.loaded / ev.total) * 100);
            progressBar.val(percentComplete);
          }
        };

        return xhr;
      },
      success:function(data, status, xhr){
        msg.html(data);
        console.log(status)
        console.log(xhr)
      },
      error: function(xhr, status, error) {
        // ...
      }
    })
  });
});