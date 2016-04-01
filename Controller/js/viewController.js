// modify the default sumbit function
// $("#upload-button").on("click", function() {
//     event.preventDefault();
//
//     // get the file from input file tag
//     var file_data = $("#fileToUpload").prop("files")[0];
//     var form_data = new FormData();
//     form_data.append("file", file_data)
//     phpRequest('uploadCSV', form_data);
// });
accountListController();

function accountListController() {
    var result = phpRequest('getAccountNamesForList','');
    // alert(result);
    displayAccounts(result);
}

function uploadController() {
    event.preventDefault();

    // get the file from input file tag
    var file_data = $("#fileToUpload").prop("files")[0];
    var form_data = new FormData();
    form_data.append("file", file_data)
    var result = phpRequest('uploadCSV', form_data);
    // alert(result);
    accountListController();
}

function transactionsController(accountClicked) {
    var arguments = '&accName=' + accountClicked.id + '&sortType=' + 'date';
    var result = phpRequest('getTrans', arguments);
    alert(result);
    createTab(result);
}

function sortTransactions(sortType){
    var selectedTabs = document.getElementsByClassName('selected');
    if(selectedTabs[0] !== undefined && selectedTabs[0] != undefined) {
        var accountName = selectedTabs[0].id.substring(4);
        if(isValidSortType(sortType)){
            var arguments = '&accName=' + accountName + '&sortType=' + sortType;
            var result = phpRequest('getTrans', arguments);
            createTab(result);
        }
    }
     
}

//helper function to check if sort type is valid
function isValidSortType(sortType) {
    if(sortType === 'date' || sortType === 'category' || sortType === 'amount') {
        return true;
    }
    return false;
}

// ajax func to handel all call to php
function phpRequest(funcName, data) {
    var arguments = "";
    if (typeof data == 'string' ) {
        arguments = data;

    }
    var result;
    // alert(funcName);
    return $.ajax({
        type:'post',
        // url: '../Controller/php/userController.php?funcToCall=' + funcName + arguments,
        url: '../Controller/php/userController.php?funcToCall=' + funcName + arguments,
        data: data,
        // THIS MUST BE DONE FOR FILE UPLOADING
        contentType: false,
        processData: false,
        async: false,
        success: function(phpResponse){
            //TODO add if statements to determine which js to call(ex, pupolate
            //  account list or populate transactions or graph)
            // alert(phpResponse);
            // return phpResponse;


            // createTab(phpResponse);
        }
    }).responseText;
}
