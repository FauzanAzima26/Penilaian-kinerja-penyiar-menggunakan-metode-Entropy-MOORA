@extends('template.main')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <div class="mb-3">
                    <label for="dropdown-penyiar">Pilih Penyiar</label>
                    <select id="dropdown-penyiar" class="form-control">
                        <option value="">-- Pilih Penyiar --</option>
                        @foreach ($penyiar as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <table id="tabel-penilaian" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Kriteria</th>
                            <th>Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                @include('penilaian.modalPenilaian')
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
let kriteria = @json($kriteria);

$(document).ready(function () {
    let penyiarId = '';

    const table = $('#tabel-penilaian').DataTable({
        ajax: {
            url: '{{ route("penilaian.getData") }}',
            data: function (d) {
                d.id_penyiar = penyiarId;
            }
        },
        columns: [
            { data: 'kriteria' },
            {
                data: 'nilai',
                render: function (data) {
                    return parseFloat(data).toFixed(2);
                }
            },
            {
                data: null,
                render: function (data, type, row) {
                    return `<button class="btn btn-sm btn-primary btn-edit" data-id="${row.id_kriteria}">Edit</button>`;
                }
            }
        ]
    });

    $('#dropdown-penyiar').on('change', function () {
        penyiarId = $(this).val();
        table.ajax.reload();
    });

    $('#tabel-penilaian tbody').on('click', '.btn-edit', function () {
        const rowData = table.row($(this).parents('tr')).data();

        Swal.fire({
            title: `Edit Nilai - ${rowData.kriteria}`,
            input: 'number',
            inputValue: rowData.nilai,
            inputAttributes: {
                min: 0,
                step: 0.01
            },
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            preConfirm: (value) => {
                if (value === '' || isNaN(value)) {
                    Swal.showValidationMessage('Nilai tidak valid!');
                } else {
                    return value;
                }
            }
        }).then(result => {
            if (result.isConfirmed) {
                rowData.nilai = parseFloat(result.value);
                table.row($(this).parents('tr')).data(rowData).draw();

                $.ajax({
                    url: '{{ route("penilaian.updateNilai") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id_penyiar: penyiarId,
                        id_kriteria: rowData.id_kriteria,
                        nilai: rowData.nilai
                    },
                    success: () => {
                        Swal.fire('Berhasil', 'Nilai berhasil diubah', 'success');
                    },
                    error: () => {
                        Swal.fire('Gagal', 'Terjadi kesalahan saat mengubah nilai', 'error');
                    }
                });
            }
        });
    });
});

</script>
@endpush
