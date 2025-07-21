@extends('template.main')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Data Kriteria</h6>
                <button type="button" class="btn btn-square btn-success m-2"><i class="fa fa-plus"></i></button>
                <table id="tabel-kriteria" class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Tipe</th>
                            <th style="width: 20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="assets/js/kriteria.js"></script>
@endpush
