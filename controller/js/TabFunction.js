/* Making the tabs work */

var tabContainer = document.getElementById('transaction-tabs');
var contentContainer = document.getElementById('transaction-tabcontents');

//Selects tab and shows its content, hiding the other tabs and contents.
//Input: tab element
function displayData(tab_element) {
    var tabID = tab_element.id;
    var contentID = 'content'+tabID.substring(3);
    //Select current tab and deselect others
    var tabs = tabContainer.childNodes;
    for(i=0; i<tabs.length; i++) {
        if(tabs[i].id == tabID){
            tabs[i].className = "selected";
        } else {
            tabs[i].className = "";
        }
    }

    //Show current tab content and hide others
    var contents = contentContainer.childNodes;
    for(i=0; i<contents.length; i++) {
        if(contents[i].id == contentID) {
            contents[i].className = "visible";
        } else {
            contents[i].className = "hidden";
        }
    }

}
