$(document).ready(function () {
    $.ajax({
        url: "/data-perankingan",
        method: "GET",
        dataType: "json",
        success: function (response) {
            let tbody = $("#tabel-hasil tbody");
            tbody.empty(); // Kosongkan isi sebelumnya

            $.each(response.data, function (i, item) {
                tbody.append(`
                    <tr>
                        <td>${i + 1}</td>
                        <td>${item.nama}</td>
                        <td>${item.nilai_akhir}</td>
                        <td>${item.ranking}</td>
                    </tr>
                `);
            });
        },
        error: function (xhr, status, error) {
            console.error("Gagal mengambil data: ", error);
        },
    });
});
