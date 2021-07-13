function refreshContenu() {
    $("#main-table").load(window.location.href + " #refresh-table");
}

$("#submit").on("click", refreshContenu);