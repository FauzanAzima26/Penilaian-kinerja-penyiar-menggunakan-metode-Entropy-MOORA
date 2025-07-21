let isEdit = false;
let editId = null;

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
        <button onclick="editData(${row.id})" class="btn btn-warning btn-sm">Edit</button>
        <button class="btn btn-sm btn-danger" onclick="hapusData(${row.id})">Hapus</button>
    `;
            },
        },
    ],
});

$("#add").on("click", function () {
    isEdit = false;
    $("#formKriteria")[0].reset();
    $("#kriteria_id").val("");
    $("#modalKriteria").modal("show");
});

function editData(id) {
    $.get(`/kriteria/${id}/edit`, function (data) {
        $("#kriteria_id").val(data.id);
        $("#nama").val(data.nama);
        $("#tipe").val(data.tipe);
        $("#modalKriteria").modal("show");
    });
}

$("#form-kriteria").on("submit", function (e) {
    e.preventDefault();

    let id = $("#kriteria_id").val();
    let url = id ? `/kriteria/${id}` : "/kriteria";
    let method = id ? "PUT" : "POST";

    $.ajax({
        url: url,
        method: "POST", // tetap POST
        data: {
            id: id,
            nama: $("#nama").val(),
            tipe: $("#tipe").val(),
            _token: $('meta[name="csrf-token"]').attr("content"),
            _method: method, // spoofing method Laravel
        },
        success: function (response) {
            $("#modalKriteria").modal("hide");
            $("#form-kriteria")[0].reset();
            $("#tabel-kriteria").DataTable().ajax.reload();

            Swal.fire({
                title: "Sukses!",
                text: response.message || "Data berhasil disimpan.",
                icon: "success",
                confirmButtonText: "Oke",
            }).then(() => {
                location.reload(); // Reload halaman setelah klik OK
            });
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            alert("Gagal menyimpan data.");
        },
    });
});

function hapusData(id) {
    Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Data akan dihapus secara permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/kriteria/${id}`,
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr("content"),
                    _method: "DELETE", // spoofing method Laravel
                },
                success: function (response) {
                    Swal.fire({
                        title: "Berhasil!",
                        text: response.message || "Data berhasil dihapus.",
                        icon: "success",
                        confirmButtonText: "Oke"
                    }).then(() => {
                        // Reload data table atau halaman
                        $("#tabel-kriteria").DataTable().ajax.reload();
                        // atau jika kamu ingin reload halaman penuh:
                        // location.reload();
                    });
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    Swal.fire("Gagal!", "Terjadi kesalahan saat menghapus data.", "error");
                }
            });
        }
    });
}
