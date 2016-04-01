/* Write transactions code here */
var currAccount = '';

function closeTab(close_element){
    var tabContainer = document.getElementById('transaction-tabs');
    var contentContainer = document.getElementById('transaction-tabcontents');
    var tab = close_element.parentNode;
    var accountName = tab.id.substring(3);
    var contentID = "content"+accountName;
    var contentDiv = document.getElementById(contentID);

    tabContainer.removeChild(tab);
    contentContainer.removeChild(contentDiv);

    //display next tab if exists
    // var nextTabs = tabContainer.childNodes;
    // var str = "";
    // if(nextTabs.length > 5){
    //     displayData(nextTabs[5]);
    //     // alert(nextTabs[5].id);
    // }
    // for(i=0; i<nextTabs.length; i++){
    //     str+=nextTabs[i].id + "\n";
    // }
}
/* */
function createTab(phpResponse) {
    var tabContainer = document.getElementById('transaction-tabs');

    var contentContainer = document.getElementById('transaction-tabcontents');

    //parse response
    var array = phpResponse.split('\n');
    var accountName = array[0];
    var transactions_list = phpResponse.substring(accountName.length);
    currAccount = accountName;
    //set content id
     var contentID = 'content-'+accountName;

     var tabs = tabContainer.childNodes;
     for(i=0; i<tabs.length; i++) {
         if(tabs[i].id == "tab-"+accountName){
             var tempTab = document.getElementById(tabs[i].id);
             displayData(tempTab);
             return;
         }
     }


    //create tab
    var tab = document.createElement('LI');
    tab.id="tab-"+accountName;

    tab.setAttribute("onclick", 'displayData(this);');


    var link = document.createElement('A');
    var labelName = document.createTextNode(accountName);
    link.appendChild(labelName);

    var linkClose = document.createElement('A');
    var labelCloseName = document.createTextNode('x');
    linkClose.appendChild(labelCloseName);
    linkClose.setAttribute('style', 'border-left: none; border-top-right-radius: 3px; border-top-left-radius: 0;');
    linkClose.setAttribute('onclick','closeTab(this);');

    // link.appendChild(linkClose);

    tab.appendChild(link);
    tab.appendChild(linkClose);

    tabContainer.appendChild(tab); //append to tabContainer



    //create content
    var content = document.createElement('DIV');
    content.id = 'content-'+accountName;
    //content.id = 'view3';
    content.setAttribute('style', 'display: block;');
    contentContainer.appendChild(content);
    var stuff = document.createTextNode("Stuff");
    content.appendChild(stuff);
    displayTransactions(contentID, transactions_list);

    displayData(tab);
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
    createTableHeaders(table, 'Date_Principle_Amount_Category_');
    //add onclick listeners to headers
    var tHead = table.firstChild;
    var tRow = tHead.firstChild;
    var headers = tRow.childNodes;
    headers[0].setAttribute('onclick', 'sortTransactions(\'date\')');
    headers[1].setAttribute('onclick', 'sortTransactions(\'principle\')');
    headers[2].setAttribute('onclick', 'sortTransactions(\'amount\')');
    headers[3].setAttribute('onclick', 'sortTransactions(\'category\')');
    //add styles
    for(i=0; i<4; i++) {
    	headers[i].className = 'clickable';
    }


    //insert transaction data into table
    for(i=0; i<transactionsArray.length; i++) {
        var transactionDataArray = transactionsArray[i].split('_');
        var tableRow = document.createElement('TR');
        //every odd row color darker
        if(i%2 == 0){
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
		var header = document.createElement("TH");
		var content = document.createTextNode(tableHeaders[i]);
		header.appendChild(content);
		var alignAttr = document.createAttribute("align");
		alignAttr.value = "left";
		header.setAttributeNode(alignAttr);
		tableRow.appendChild(header);
	}
	//add row to watchlist content
	tHead.appendChild(tableRow);
	table.appendChild(tHead);
}
