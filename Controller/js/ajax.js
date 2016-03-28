function phpRequest(funcName) {
    $.ajax({
        type:'post',
        url: '../Controller/php/ajaxTest.php',
        data: {funcToCall: funcName},
        success: function(phpResponse){
            alert(phpResponse);
        }
    });
}
