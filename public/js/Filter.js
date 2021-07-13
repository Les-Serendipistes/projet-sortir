function refreshContenu() {
    $("#main-table").load(window.location.href + " #refresh-table");
}

$("#button-list").on("click", refreshContenu);