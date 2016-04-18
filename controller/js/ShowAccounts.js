// accepts parameter accountList which is a string of account names
// crates a table containing these account names in the user UI
function displayAccounts(accountList) {
    var container = document.getElementById('account-list');
    clearChildren(container);
    //split data
    var accountsArray = accountList.split('\n');
    //create table
    var table = document.createElement('TABLE');
    createTableHeaders(table, 'Accounts_Balance');

    //insert transaction data into table
    for(i=0; i<accountsArray.length; i++) {
        var tableRow = document.createElement('TR');
        tableRow.className = 'account-data';

        var tableData = document.createElement('TD');
        tableData.setAttribute('onclick', 'transactionsController(this);');

        var value = document.createTextNode(accountsArray[i]);
        tableData.id = accountsArray[i].trim();

        tableData.appendChild(value);
        tableRow.appendChild(tableData);
        table.appendChild(tableRow);
    }

    //add table to container
    container.appendChild(table);

}

function displayTestaaaa() {
    alert("displayTest");
}
