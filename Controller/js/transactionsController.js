/* Write transactions code here */

/* Display the list of transactions in the container with containerID
 * transaction_list: date_category_amount_principle
 */
function displayTransactions(containerID, transactions_list) {
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
            tableRow.setAttributeNode('style', 'background-color: #C0C0C0;')
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
    var container = document.getElementById(containerID);
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
