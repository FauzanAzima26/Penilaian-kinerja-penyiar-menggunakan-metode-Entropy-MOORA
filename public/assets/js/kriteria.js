$("#tabel-kriteria").DataTable({
    ajax: {
        url: "/kriteria/data",
        dataSrc: "data",
        error: function (xhr, error, thrown) {
            console.log("AJAX Error:", xhr.responseText);
        },
    },
    columns: [
        { data: "id" },
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
