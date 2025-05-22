document.getElementById('search').addEventListener('input', function() {
    var input, filter, table, tbody, tr, td, i, txtValue;
    input = document.getElementById("search");
    filter = input.value.toUpperCase();
    table = document.querySelector(".table tbody");
    tr = table.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
        var display = false;
        for (var j = 0; j < 10; j++) {
            td = tr[i].getElementsByTagName("td")[j];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    display = true;
                }
            }
        }
        if (display) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
    }
});
