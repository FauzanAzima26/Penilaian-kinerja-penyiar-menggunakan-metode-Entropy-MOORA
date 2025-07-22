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
    const PENILAIAN_CONFIG = {
        csrf: '{{ csrf_token() }}',
        kriteria: @json($kriteria),
        routeUpdate: '{{ route("penilaian.updateNilai") }}',
        routeData: '{{ route("penilaian.getData") }}'
    };
</script>

<script src="{{ asset('assets/js/penilaian.js') }}"></script>
@endpush

