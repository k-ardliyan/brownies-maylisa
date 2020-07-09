function myFunction() {
    $.ajax({
        url: "ajax.php?read=1",
        type: "POST",
        processData:false,
        success: function(data){
            $("#count").remove();
        },
    });
}