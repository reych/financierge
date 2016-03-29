// modify the default sumbit function
$("#upload-button").on("click", function() {
    event.preventDefault();

    // get the file from input file tag
    var file_data = $("#fileToUpload").prop("files")[0];
    var form_data = new FormData();
    form_data.append("file", file_data)
    phpRequest('uploadCSV', form_data);
});


function phpRequest(funcName, formData) {
    $.ajax({
        type:'post',
        url: '../Controller/php/ajaxTest.php?funcToCall=' + funcName,
        data: formData,
        // THIS MUST BE DONE FOR FILE UPLOADING
        contentType: false,
        processData: false,
        success: function(phpResponse){
            //TODO add if statements to determine which js to call(ex, pupolate
            //  account list or populate transactions or graph)
            alert(phpResponse);
        }
    });
}
