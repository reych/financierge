// modify the default sumbit function
$("#upload-button").on("click", function() {
    event.preventDefault();

    // get the file from input file tag
    var file_data = $("#fileToUpload").prop("files")[0];
    var form_data = new FormData();
    form_data.append("file", file_data)
    phpRequest('uploadCSV', form_data);
});



// ajax func to handel all call to php
function phpRequest(funcName, formData) {
    var arguments = "";
    if (formData instanceof HTMLElement) {
        arguments = '&accName=' + formData.id;
        // alert(arguments);
    }
    $.ajax({
        type:'post',
        // url: '../Controller/php/userController.php?funcToCall=' + funcName + arguments,
        url: '../Controller/php/upload.php?funcToCall=' + funcName + arguments,
        data: formData,
        // THIS MUST BE DONE FOR FILE UPLOADING
        contentType: false,
        processData: false,
        success: function(phpResponse){
            //TODO add if statements to determine which js to call(ex, pupolate
            //  account list or populate transactions or graph)
            // if (funcName === 'uploadCSV') {
            //     alert(phpResponse);
            // } else {
            createTab(phpResponse);
            // }
            // displayTransactions('transactions', phpResponse);
            // alert(phpResponse);
        }
    });
}
