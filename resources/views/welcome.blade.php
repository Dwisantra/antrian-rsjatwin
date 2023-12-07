@extends('layouts.app')

@section('content')
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth">
                <div class="row flex-grow">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left p-5">
                            <h2><i class="icon-people text-success me-3 fs-3 mb-4"></i> Nomor Antrian</h2>
                            <center>
                                <div class="border border-success rounded-2 py-2 mt-5 mb-5">
                                    <h3>Antrian Pendaftaran</h3>
                                    <strong><h1 id="displayNomorAntrian" class="display-1 fw-bold text-danger text-center lh-1 pb-2 my-5"></h1></strong>
                                </div>
                            </center>
                            <div>
                                <button class="btn btn-danger btn-block rounded-pill fs-5 px-5 py-4 mb-2" id="generateAntrian">Ambil Antrian</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
@endsection

@push('javascript-internal')
    <script>
        $(document).ready(function() {
            // Buat Antrian
            $('#generateAntrian').on('click', function() {
                $.get('/generate-antrian', function(response) {
                    fetchAndDisplayNomorAntrian(response);
                    printNomorAntrian(response);
                });
            });

            function fetchAndDisplayNomorAntrian() {
                $.get('/get-nomor-antrian', function(response) {
                    // Check if the response contains the generated queue number
                    if ('nomor_antrian' in response) {
                        // Display the generated queue number in a specific element
                        var displayAmbilAntrian = response.nomor_antrian;
                        var formattedNomorAntrian = 'A-' + padNumber(displayAmbilAntrian, 3);

                        $('#displayNomorAntrian').text(formattedNomorAntrian);
                    } else {
                        console.error('Invalid response:', response);
                    }
                });
            }

            function getBelumPanggil(callback) {
                $.ajax({
                    type: 'GET',
                    url: '/pendaftaran/{tanggal?}',
                    success: function (response) {
                        var nomorAntrianBelumPanggil = response.antrianBelumDipanggil;

                        // Check if the necessary data is present in the response
                        if (nomorAntrianBelumPanggil !== undefined && nomorAntrianBelumPanggil !== null) {
                            callback(nomorAntrianBelumPanggil);
                        } else {
                            console.error('Invalid nomorAntrianBelumPanggil:', nomorAntrianBelumPanggil);
                        }
                    },
                    error: function (error) {
                        console.error('AJAX request failed:', error);
                    }
                });
            }

            function printNomorAntrian(response) {
                // Check if the response contains the generated queue number
                if (response && 'nomor_antrian' in response) {
                    // Create a new window for printing
                    var printWindow = window.open('', '_blank');

                    // Set the content of the new window
                    printWindow.document.write('<html><head><title>RS Jatiwinangu</title></head><body><center>');
                    printWindow.document.write('<h2>ANTRIAN PENDAFTARAN</h2>');
                    printWindow.document.write('<p>Jl. Dr. Angka No.80, Purwokerto, Banyumas</p>');
                    printWindow.document.write('<p>-----------------------------------------------------</p>');
                    printWindow.document.write('<p>Nomor Antrian: <br><strong style="font-size: 6rem;"> A-' + padNumber(response.nomor_antrian, 3) + '</strong></p>');

                    getBelumPanggil(function (nomorAntrianBelumPanggil) {
                        // Include other ticket details as needed
                        printWindow.document.write('<p>Jumlah antrian menunggu dipanggil <strong>' + nomorAntrianBelumPanggil + '</strong></p>');
                        printWindow.document.write('<p>-----------------------------------------------------</p>');
                        printWindow.document.write('<p>Waktu Cetak: <strong>' + getCurrentTime() + '</strong></p>');
                        printWindow.document.write('<p>-----------------------------------------------------</p>');
                        printWindow.document.write('</center></body></html>');

                        // Print the content and close the window
                        printWindow.print();
                        printWindow.close();
                    });
                } else {
                    console.error('Invalid response:', response);
                }
            }

            // Function to pad the number with zeros
            function padNumber(number, length) {
                var str = '' + number;
                while (str.length < length) {
                    str = '0' + str;
                }
                return str;
            }

            // fungsi untuk mengambil tanggal dan jam
            function getCurrentTime() {
                var dayNames = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
                var monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                var now = new Date();
                var nameDays = dayNames[now.getDay()];
                var days = now.getDay();
                var months = monthNames[now.getMonth()];
                var years = now.getFullYear();
                var hours = now.getHours();
                var minutes = now.getMinutes();
                var seconds = now.getSeconds();
                return nameDays + ', ' + days + ' ' + months + ' ' + years + ' ' + hours + ':' + minutes + ':' + seconds;
            }

            $(document).ready(function() {
                fetchAndDisplayNomorAntrian();
            });
        });
    </script>
@endpush

@push('javascript-external')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
@endpush
