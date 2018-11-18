var statusIntervalId = window.setInterval(update, 1000);

function update() {
    $.ajax({
        url: 'http://localhost/wordpress/lookupPrinterStatus.php',
        dataType: 'text',
        success: function(data) {
            if (parseInt(data) == 0) {
                $("#status").css({ color: "red" }).text("offline");
            } else {
                $("#status").css({ color: "green" }).text("online");
            }
        }
    }
}