@extends('layouts.dashboard')

@section('content')
<div class="content-wrapper">
    <!-- Dashboard Antrian Starts-->
    <div class="row quick-action-toolbar">
        <div class="col-md-12 grid-margin">
            <div class="card">
                <div class="card-header d-block d-md-flex">
                    <h5 class="mb-0">Antrian Hari Ini</h5>
                    {{-- <p class="ml-auto mb-0">How are your active users trending overtime?<i class="icon-bulb"></i></p> --}}
                </div>
                <div class="col-md-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <div class="row report-inner-cards-wrapper">
                                <div class=" col-md -6 col-xl report-inner-card">
                                    <div class="inner-card-text">
                                    <span class="report-title">TOTAL</span>
                                    <h4 id="totalAntrianPerTanggal">-</h4>
                                    </div>
                                    <div class="inner-card-icon bg-success">
                                        <i class="fa-solid fa-users-line"></i>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl report-inner-card">
                                    <div class="inner-card-text">
                                    <span class="report-title">SEKARANG</span>
                                    <h4 id="antrianSekarang">-</h4>
                                    </div>
                                    <div class="inner-card-icon bg-danger">
                                        <i class="fa-solid fa-user-check"></i>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl report-inner-card">
                                    <div class="inner-card-text">
                                    <span class="report-title">SELANJUTNYA</span>
                                    <h4 id="antrianSelanjutnya">-</h4>
                                    </div>
                                    <div class="inner-card-icon bg-warning">
                                        <i class="fa-solid fa-people-arrows"></i>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl report-inner-card">
                                    <div class="inner-card-text">
                                    <span class="report-title">BELUM DIPANGGIL</span>
                                    <h4 id="jumlahAntrianBelumDipanggil">-</h4>
                                    </div>
                                    <div class="inner-card-icon bg-primary">
                                        <i class="fa-solid fa-person-walking-arrow-right"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-sm-flex align-items-center mb-4">
                        <h4 class="card-title mb-sm-0">Data Antrian</h4>
                        <button id="refreshButton" class="btn btn-info btn-rounded btn-icon align-items-end ml-auto">
                            <i class="icon-reload"></i>
                        </button>
                    </div>
                    <div class="table-responsive border rounded p-1">
                        <table class="table" id="antrianTable">
                            <thead>
                                <tr>
                                    <th class="font-weight-bold" hidden>ID</th>
                                    <th class="font-weight-bold">Tanggal</th>
                                    <th class="font-weight-bold">Nomor</th>
                                    <th class="font-weight-bold">Status</th>
                                    <th class="font-weight-bold">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Data Antrian Akan Tampil disini --}}
                            </tbody>
                        </table>
                        {{-- {{ $antrians->links() }} --}}
                    </div>
                    <div class="d-flex mt-4 flex-wrap">
                        <nav class="ml-auto">
                            <ul class="pagination separated pagination-info" id="pagination"></ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css-external')
    <!-- DataTables -->
    <link href="{{ asset('asset/vendors/DataTables/datatables.min.css') }}" rel="stylesheet">
@endpush

@push('javascript-external')
    <!-- DataTables:js -->
    <script src="{{ asset('asset/vendors/DataTables/datatables.min.js') }}"></script>
@endpush

@push('javascript-internal')
    <script>
        $(document).ready(function () {
            var dataTableInitialized = false;
            var antrianTable;

            // Fungsi untuk panggilan awal untuk memuat DataTable
            function initializeDataTable() {
                if (!dataTableInitialized) {
                    antrianTable = $('#antrianTable').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            "url": "/get-antrian",
                            "type": "GET",
                        },
                        "columns": [
                            {"data": "id", "visible": false},
                            {"data": "tanggal"},
                            {"data": "formatted_nomor"},
                            {
                                "data": "status",
                                "render": function (data, type, row) {
                                    return data === 0 ? 'Menunggu' : 'Sudah Dipanggil';
                                }
                            },
                            {"data": "action"}
                        ],

                        "drawCallback": function(settings) {
                            // Hapus event handler sebelum menambahkannya kembali
                            $('#antrianTable').off('click', '.panggil-btn');

                            // Tambahkan event handler untuk tombol panggil di setiap baris
                            $('#antrianTable').on('click', '.panggil-btn', function () {
                                var noId = $(this).data('no-id');
                                var noAntrian = $(this).data('no-antrian');
                                panggilAntrian(noId, noAntrian);
                            });
                        }
                    });

                    // Set the flag to indicate DataTable is initialized
                    dataTableInitialized = true;
                } else {
                    // Reload the DataTable when it's already initialized
                    antrianTable.clear().draw(); // Clear existing data before reloading
                    antrianTable.ajax.reload();
                }
            }

            // Fungsi untuk pemanggilan data awal
            function fetchData() {
                var tanggalSekarang = Date.now();
                var tanggal = new Date(tanggalSekarang).toISOString().split('T')[0];

                $.ajax({
                    url: '/pendaftaran/' + tanggal,
                    method: 'GET',
                    success: function (data) {
                        handleDataResponse(data);
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX request failed: ' + status + ', ' + error);
                        setTimeout(fetchData, 1000);
                    }
                });
            }

            // Fungsi untuk menangani respons data dari server
            function handleDataResponse(data) {
                if (typeof data === 'object' && 'totalAntrian' in data && 'antrianBelumDipanggil' in data) {
                    $('#totalAntrianPerTanggal').text(data.totalAntrian);

                    // Fetch the next antrian number
                    getAntrianSelanjutnya(data.antrianSekarang);

                    if (data.antrianBelumDipanggil !== undefined && data.antrianBelumDipanggil !== null) {
                        $('#jumlahAntrianBelumDipanggil').text(data.antrianBelumDipanggil);
                    } else {
                        $('#jumlahAntrianBelumDipanggil').text('N/A');
                    }

                    // Fetch data again after a delay
                    setTimeout(fetchData, 1000);
                } else {
                    console.error('Invalid data structure received:', data);
                    // Fetch data again after an error with a delay
                    setTimeout(fetchData, 1000);
                }
            }

            function getAntrianSelanjutnya(nomorAntrianSelanjutnya) {
                $.ajax({
                    type: 'GET',
                    url: '/get-antrian-selanjutnya/',
                    success: function(response) {
                        var nomorAntrianSelanjutnya = response.nomorAntrianSelanjutnya;

                        if (nomorAntrianSelanjutnya !== undefined && nomorAntrianSelanjutnya !== null) {
                            $('#antrianSelanjutnya').text(nomorAntrianSelanjutnya);
                        } else {
                            console.error('Invalid nomorAntrianSelanjutnya:', nomorAntrianSelanjutnya);
                        }
                    },
                    error: function(error) {
                        console.error('AJAX request failed:', error);
                    }
                });
            }

            // Fungsi untuk memanggil antrian
            function panggilAntrian(noId, noAntrian, action) {
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var noAntrian = noAntrian;

                $.ajax({
                    type: 'POST',
                    url: '/panggil-antrian/' + noId + '/' + noAntrian,
                    data: {
                        _token: csrfToken,
                        action: action
                    },
                    success: function(response) {
                        var nomorAntrianSekarang = noAntrian;
                        var formattednomorAntrianSekarang = 'A-' + padNumber(noAntrian, 3);

                        $('#antrianSekarang').text(formattednomorAntrianSekarang);
                        $('#antrianTable').DataTable().ajax.reload();
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            }

            // Fungsi untuk menambahkan nol di depan angka jika perlu
            function padNumber(number, length) {
                var str = '' + number;
                while (str.length < length) {
                    str = '0' + str;
                }
                return str;
            }

            $(document).ready(function() {
                // Assuming "panggil-btn" is for "Panggil" and "panggil-ulang-btn" is for "Panggil Ulang"
                $('#antrianTable').on('click', '.panggil-btn', function() {
                    var noId = $(this).data('no-id');
                    var noAntrian = $(this).data('no-antrian');
                    panggilAntrian(noId, noAntrian, 'panggil');
                });

                $('#antrianTable').on('click', '.panggil-ulang-btn', function() {
                    var noId = $(this).data('no-id');
                    var noAntrian = $(this).data('no-antrian');
                    panggilAntrian(noId, noAntrian, 'panggil-ulang');
                });
            });

            $('#refreshButton').on('click', function() {
                initializeDataTable();
            });

            // Panggil fungsi untuk inisialisasi DataTable pada saat halaman dimuat
            $(document).ready(function() {
                initializeDataTable();
                fetchData();
            });
        });
    </script>
@endpush
