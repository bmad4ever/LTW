$().ready(loadDocument);

function loadDocument() {
window.setInterval(refresh, 1000);
}

//update comments and images
function refresh() {
document.getElementById("date_display").innerHTML = (new Date()).toLocaleString();
}
