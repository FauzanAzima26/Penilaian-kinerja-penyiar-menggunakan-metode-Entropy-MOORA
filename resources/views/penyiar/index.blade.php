@extends('template.main')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Data Penyiar</h6>
                    <button id="add" type="button" class="btn btn-success m-2" data-bs-toggle="modal" data-bs-target="#modalPenyiar">
                        <i class="fa fa-plus"></i>
                    </button>
                    <table id="tabel-penyiar" class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th style="width: 20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    @include('penyiar/modalPenyiar')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="assets/js/penyiar.js"></script>
@endpush
