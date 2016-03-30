/* Write transactions code here */

/* */
function createTab(phpResponse) {
    var tabContainer = document.getElementById('transaction-tabs');

    var contentContainer = document.getElementById('transaction-tabcontents');

    //parse response
    var array = phpResponse.split('\n');
    var accountName = array[0];
    var transactions_list = phpResponse.substring(accountName.length);

    //set content id
     //var contentID = 'content-'+accountName;
    //var contentID = 'view3';

    //create tab
    var tab = document.createElement('LI');
    tab.id="tab-"+accountName;
    var link = document.createElement('A');
    //link.setAttribute('href', '#'+contentID);
    // tab.className = 'selected';
    var labelName = document.createTextNode(accountName);
    link.appendChild(labelName);
    tab.appendChild(link);
    tabContainer.appendChild(tab); //append to tabContainer

    //create content
    var content = document.createElement('DIV');
    content.id = 'content-'+accountName;
    //content.id = 'view3';
    content.setAttribute('style', 'display: block;');
    contentContainer.appendChild(content);
    var stuff = document.createTextNode("Stuff");
    content.appendChild(stuff);
    //displayTransactions(contentID, transactions_list);


}

/* Display the list of transactions in the container with containerID
 * transaction_list: date_category_amount_principle
 */
function displayTransactions(containerID, transactions_list) {
    var container = document.getElementById(containerID);
    clearChildren(container);
    //split data
    var transactionsArray = transactions_list.split('\n');
    //create table
    var table = document.createElement('TABLE');
    createTableHeaders(table, 'Date_Principle_Amount_Category');

    //insert transaction data into table
    for(i=0; i<transactionsArray.length; i++) {
        var transactionDataArray = transactionsArray[i].split('_');
        var tableRow = document.createElement('TR');
        //every odd row color darker
        if(i%2 != 0){
            tableRow.setAttribute('style', 'background-color: #C0C0C0;');
        }

        for(j=0; j<transactionDataArray.length; j++) {
            var tableData = document.createElement('TD');
            var value = document.createTextNode(transactionDataArray[j]);
            tableData.appendChild(value);
            tableRow.appendChild(tableData);
        }

        table.appendChild(tableRow);
    }

    //add table to container
    container.appendChild(table);

}

/* Clear the container of its children */
function clearChildren(container) {
    while (container.firstChild) {
      	container.removeChild(container.firstChild);
  	}
}

/* Create headers for a table */
function createTableHeaders(table, headers_string){
	//create a new row
	var tableRow = document.createElement("TR");
	var tHead = document.createElement("THEAD");
    //Split headers_string into array
    var tableHeaders = headers_string.split('_')
	//create table header elements and content
	for(i = 0; i < tableHeaders.length; i++){
		var ticker = document.createElement("TH");
		var content = document.createTextNode(tableHeaders[i]);
		ticker.appendChild(content);
		var alignAttr = document.createAttribute("align");
		alignAttr.value = "left";
		ticker.setAttributeNode(alignAttr);
		tableRow.appendChild(ticker);
	}
	//add row to watchlist content
	tHead.appendChild(tableRow);
	table.appendChild(tHead);
}
