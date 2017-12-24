

function cekaa(){
     $.ajax({
        url: "http://localhost/person/ajax_notif",
        cache: false,
        success: function(data){
            $("#notif").val(data.jml_notif);
        }
    });
     
      
    var waktu = setTimeout("cekaa()",3000);
}

$(document).ready(function(){
    cekaa();
});


