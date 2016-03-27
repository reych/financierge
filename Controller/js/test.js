function ajaxTest(funcName) {
    $.ajax({
        type:'post',
        url: '../Controller/php/ajaxTest.php',
        data: {funcToCall: funcName},
        success: function(dtx){
            alert(dtx);
        }
    });
}
