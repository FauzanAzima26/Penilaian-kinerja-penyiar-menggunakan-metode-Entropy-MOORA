$("#tabel-kriteria").DataTable({
    ajax: {
        url: "/kriteria/data",
        dataSrc: "data",
        error: function (xhr, error, thrown) {
            console.log("AJAX Error:", xhr.responseText);
        },
    },
    columns: [
        {
            data: null,
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            },
        },
        { data: "nama" },
        { data: "tipe" },
        {
            data: null,
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
                return `
                        <a href="/kriteria/${row.id}/edit" class="btn btn-sm btn-warning">Edit</a>
                        <button class="btn btn-sm btn-danger" onclick="hapusData(${row.id})">Hapus</button>
                    `;
            },
        },
    ],
});

$("#add").on("click", function () {
    $("#modalKriteria").modal("show");
});

$("#form-kriteria").on("submit", function (e) {
    e.preventDefault();

    $.ajax({
        url: "/kriteria", // route ke controller yang menangani simpan
        method: "POST",
        data: {
            nama: $("#nama").val(),
            tipe: $("#tipe").val(),
            _token: $('meta[name="csrf-token"]').attr("content"), // Laravel CSRF Token
        },
        success: function (response) {
            $("#modalKriteria").modal("hide");
            $("#form-kriteria")[0].reset();

            Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: "Data berhasil disimpan.",
                confirmButtonText: "OK",
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            Swal.fire({
                icon: "error",
                title: "Gagal",
                text: "Gagal menyimpan data. Silakan cek kembali!",
            });
        },
    });
});
