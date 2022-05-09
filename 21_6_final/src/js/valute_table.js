

function table_to_html(rates_to_js) {
    var rates_to_js1 = rates_to_js[0];
    var head = rates_to_js1[0].map(i => `<td>${i}</td>`).join("");
    var thead = `<thead><tr>${head}</tr></thead>`;
    table = '<table class="table_valute">' + thead;
    for (var i = 0; i < rates_to_js1[1].length; i++) { // начинаем со 2 массива (rates_to_js1[1])
        table += "<tr>";
        for (var j = 1; j < rates_to_js1.length; j++) {  //j=1 перебирает с 0 индекса со второго массива rates_to_js1[1][0]
            var val = rates_to_js1[j][i];
            var val_change = rates_to_js1[5][i]
            if (val == undefined || val == null) {
                val = "n/a"
            }
            else if (j <= 4) {
                table += '<td>' + val + '</td>'
            }
            else if (j == 5 && val_change < 0) {
                table += '<td id="table_td_minus">' + val + '</td>'
            }
            else if (j == 5 && val_change > 0) {
                table += '<td id="table_td_plus">' + val + '</td>'
            }
        }
        table += "</tr>";
    }

    /*     for (var i = 1; i < rates_to_js[0].length; i++) {
            table += "<tr>";
            for (var j = 0; j < rates_to_js.length; j++) { 
                var val = rates_to_js[j][i].join(" "); //j=1 перебирает с 1 индекса со второго массива
                if (val == undefined || val == null)
                    val = "n/a";
                table += "<td>" + val + "</td>";
            }
            table += "</tr>";
        } */

    table += "</table>";
    return table;
};
/* console.log(rates_to_js); */
/* if ($rates_valute_clear != "") {
    document.querySelector("#rates_table").innerHTML = "----------";
} */

if (rates_to_js != null) {
    if (rates_to_js[1] != "") {
        document.querySelector("#rates_table").innerHTML = rates_to_js[1];
        table = table_to_html(rates_to_js)
    }
    else if (rates_to_js[1] == "") {
        table = table_to_html(rates_to_js)
        document.querySelector("#rates_table").innerHTML = table
    };
};