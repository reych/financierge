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
    var accountName = "";
    if (formData instanceof HTMLElement) {
        accountName = formData.id;
    }
    $.ajax({
        type:'post',
        // url: '../Controller/php/userController.php?funcToCall=' + funcName,
        url: '../Controller/php/upload.php?funcToCall=' + funcName + '&accName=' + accountName,
        data: formData,
        // THIS MUST BE DONE FOR FILE UPLOADING
        contentType: false,
        processData: false,
        success: function(phpResponse){
            //TODO add if statements to determine which js to call(ex, pupolate
            //  account list or populate transactions or graph)
            // createTab(phpResponse);
            displayTransactions('transactions', phpResponse);
            // alert(phpResponse);
        }
    });
}
