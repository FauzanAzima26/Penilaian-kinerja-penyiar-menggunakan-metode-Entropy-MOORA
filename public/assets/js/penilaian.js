$(document).ready(function () {
    const kriteria = PENILAIAN_CONFIG.kriteria;
    let penyiarId = "";

    const table = $("#tabel-penilaian").DataTable({
        ajax: {
            url: PENILAIAN_CONFIG.routeData,
            data: function (d) {
                d.id_penyiar = penyiarId;
            },
        },
        columns: [
            { data: "kriteria" },
            {
                data: "nilai",
                render: function (data) {
                    return parseFloat(data).toLocaleString("en-US", {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 6,
                    });
                },
            },
            {
                data: null,
                render: function (data, type, row) {
                    return `<button class="btn btn-sm btn-primary btn-edit" data-id="${row.id_kriteria}"><i class="fa fa-pencil-square-o"></i></button>`;
                },
            },
        ],
    });

    $("#dropdown-penyiar").on("change", function () {
        penyiarId = $(this).val();
        table.ajax.reload();
    });

    $("#tabel-penilaian tbody").on("click", ".btn-edit", function () {
        if (!penyiarId) {
            Swal.fire(
                "Pilih Penyiar",
                "Silakan pilih penyiar terlebih dahulu sebelum mengedit nilai.",
                "warning"
            );
            return; // hentikan proses jika belum pilih penyiar
        }

        const rowData = table.row($(this).parents("tr")).data();

        Swal.fire({
            title: `Edit Nilai - ${rowData.kriteria}`,
            input: "number",
            inputValue: rowData.nilai,
            inputAttributes: {
                min: 0,
                step: 0.000001,
            },
            showCancelButton: true,
            confirmButtonText: "Simpan",
            cancelButtonText: "Batal",
            preConfirm: (value) => {
                if (value === "" || isNaN(value)) {
                    Swal.showValidationMessage("Nilai tidak valid!");
                } else {
                    return value;
                }
            },
        }).then((result) => {
            if (result.isConfirmed) {
                // update di tampilan
                rowData.nilai = parseFloat(result.value);
                table.row($(this).parents("tr")).data(rowData).draw();

                // update ke database
                $.ajax({
                    url: PENILAIAN_CONFIG.routeUpdate,
                    method: "POST",
                    data: {
                        _token: PENILAIAN_CONFIG.csrf,
                        id_penyiar: penyiarId,
                        id_kriteria: rowData.id_kriteria,
                        nilai: rowData.nilai,
                    },
                    success: () => {
                        Swal.fire(
                            "Berhasil",
                            "Nilai berhasil diubah",
                            "success"
                        );
                    },
                    error: () => {
                        Swal.fire(
                            "Gagal",
                            "Terjadi kesalahan saat mengubah nilai",
                            "error"
                        );
                    },
                });
            }
        });
    });
});
