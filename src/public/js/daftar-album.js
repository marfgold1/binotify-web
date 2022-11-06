// variable global
var page = 1;
var countPage = 1;
// event listener
document.getElementById("start").addEventListener("load", loadDoc("tombol0"));

//FUNGSI
function loadDoc(id) {
    //fungsi ajax
    var xmlhttp = new XMLHttpRequest();
    var table = '<table class="album-tracks"><thead class="tracks-heading"><tr><th style="font:italic">#</th><th>Album</th><th>Year</th><th>Genre</th></tr></thead><tbody class="tracks">';
    if (id != "tombol0") {
        page = document.getElementById(id).innerText;
        page = parseInt(page);
    }
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var list = JSON.parse(this.responseText);
            countPage = list.lastPage;
            console.log(list);
            var i = (list.page - 1) * list.limit + 1;
            list.models.forEach(function (item) {
                table += "<tr class='track'>";
                table += "<td><a href='/album/" + item.album_id + "'><div class='track-text'>" + i + "</div></a></td>";
                table += "<td><a href='/album/" + item.album_id + "'><div class='track-title'><img src='/public/image/" + item.image_path + "'><div class='name-album'><span class='title'>" + item.judul + "</span><span class='artist'>" + item.penyanyi + "</span></div></div></a></td>";
                table += "<td><a href='/album/" + item.album_id + "'><div class='track-text'>" + item.tanggal_terbit.substr(0, 4) + "</div></a></td>";
                table += "<td><a href='/album/" + item.album_id + "'><div class='track-text'>" + item.penyanyi + "</div></a></td>";
                table += "</tr>";
                i++;
            }
            );
            table += '</tbody></table>';
            document.getElementById("tabel").innerHTML = table;
            pagination();

        }
    };
    xmlhttp.open("GET", "/album/data?page=" + page, true);
    xmlhttp.send();
}

function pagination() {
    //fungsi untuk membuat pagination
    var pagination = "";
    if (countPage > 5) {
        if (page > 3 && page < countPage - 2) {
            for (var i = page - 2; i <= page + 2; i++) {
                if (i == page) {
                    pagination += '<button class="page-num active" id="tombol' + i + '" onclick="loadDoc(\'tombol' + i + '\')">' + i + "</button>";
                }
                else {
                    pagination += '<button class="page-num" id="tombol' + i + '" onclick="loadDoc(\'tombol' + i + '\')">' + i + "</button>";
                }
            }
        }
        else if (page < countPage - 2) {
            for (var i = 1; i <= 5; i++) {
                if (i == page) {
                    pagination += '<button class="page-num active" id="tombol' + i + '" onclick="loadDoc(\'tombol' + i + '\')">' + i + "</button>";
                }
                else {
                    pagination += '<button class="page-num" id="tombol' + i + '" onclick="loadDoc(\'tombol' + i + '\')">' + i + "</button>";
                }
            }
        }

        else {
            for (var i = countPage - 4; i <= countPage; i++) {
                if (i == page) {
                    pagination += '<button class="page-num active" id="tombol' + i + '" onclick="loadDoc(\'tombol' + i + '\')">' + i + "</button>";
                }
                else {
                    pagination += '<button class="page-num" id="tombol' + i + '" onclick="loadDoc(\'tombol' + i + '\')">' + i + "</button>";
                }
            }
        }


    }
    else if (countPage <= 5 && countPage > 1) {
        for (var i = 1; i <= countPage; i++) {
            if (i == page) {
                pagination += '<button class="page-num active" id="tombol' + i + '" onclick="loadDoc(\'tombol' + i + '\')">' + i + "</button>";
            }
            else {
                pagination += '<button class="page-num" id="tombol' + i + '" onclick="loadDoc(\'tombol' + i + '\')">' + i + "</button>";
            }
        }
    }
    document.getElementById("pagIn").innerHTML = pagination;
}

