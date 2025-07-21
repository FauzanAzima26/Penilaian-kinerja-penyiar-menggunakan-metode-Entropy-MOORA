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
                        @foreach($penyiar as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <button class="btn btn-success mb-3" id="btn-tambah" data-bs-toggle="modal" data-bs-target="#modalPenilaian">
                    <i class="fa fa-plus"></i> Tambah Penilaian
                </button>

                <table id="tabel-penilaian" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Kriteria</th>
                            <th>Nilai</th>
                            <th>Evaluator</th>
                            <th>Waktu</th>
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
            { data: 'nilai' },
            { data: 'evaluator' },
            { data: 'created_at' }
        ]
    });

    $('#dropdown-penyiar').on('change', function () {
        penyiarId = $(this).val();
        table.ajax.reload();
    });

    $('#btn-tambah').on('click', function () {
        penyiarId = $('#dropdown-penyiar').val();
        if (!penyiarId) {
            alert('Pilih penyiar terlebih dahulu!');
            $('#modalPenilaian').modal('hide');
            return;
        }

        let formIsi = `<input type="hidden" name="id_penyiar" value="${penyiarId}">`;

        kriteria.forEach(k => {
            formIsi += `
                <div class="mb-3">
                    <label class="form-label">${k.nama}</label>
                    <input type="number" name="nilai[${k.id}]" class="form-control" min="0" step="1" required>
                </div>
            `;
        });

        $('#form-isi-kriteria').html(formIsi);
    });

    $('#form-penilaian').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route("penilaian.store") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function () {
                $('#modalPenilaian').modal('hide');
                $('#form-penilaian')[0].reset();
                table.ajax.reload();

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Penilaian berhasil disimpan'
                });
            },
            error: function (err) {
                console.error(err.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat menyimpan'
                });
            }
        });
    });
});
</script>
@endpush
