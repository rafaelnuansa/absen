<x-employee.layout>
    <div class="section wallet-card-section pt-1">
        <div class="wallet-card">
            <div class="balance">
                <div class="left"><span class="title"> @php
                    $currentTime = date('H');
                    // dd($currentTime);
                    if ($currentTime >= 5 && $currentTime < 12) {
                        echo 'Selamat Pagi';
                    } elseif ($currentTime >= 12 && $currentTime < 16) {
                        echo 'Selamat Siang';
                    } elseif ($currentTime >= 16 && $currentTime < 18) {
                        echo 'Selamat Sore';
                    } else {
                        echo 'Selamat Malam';
                    }
                @endphp</span>
                    <h4>{{ auth()->guard('employee')->user()->employees_name }}</h4>
                </div>
                <div class="right"><span class="title">{{ now()->format('d M Y') }}</span>

                    <h4><span class="clock"></span></h4>
                </div>
            </div>
            <div class="text-center">
                <p>Lat-Long: <span class="latitude" id="latitude"></span></p>
            </div>
            <div class="wallet-footer text-center">
                <div class="webcam-capture-body text-center">
                    <div class="webcam-capture" style="width: 590px; height: 460px;">
                        <div></div><video autoplay="autoplay" style="width: 590px; height: 460px;"></video>
                    </div>
                    <div class="form-group basic"><button class="btn btn-danger btn-lg btn-block"
                            onclick="captureimage(0)">
                            <i class="fa fa-qrcode mr-1"></i> SCAN QR CODE</button></div>
                </div>
            </div>
        </div>
    </div>

   @push('scripts')
   <script type="text/javascript">
    var result;
    $(document).ready(function getLocation() {
        result = document.getElementById("latitude");
       //
        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        } else {
            swal({title: 'Oops!', text:'Maaf, browser Anda tidak mendukung geolokasi HTML5.', icon: 'error', timer: 3000,});
        }
    });

    // Define callback function for successful attempt
    function successCallback(position) {
       result.innerHTML =""+ position.coords.latitude + ","+position.coords.longitude + "";
    }

    // Define callback function for failed attempt
    function errorCallback(error) {
        if(error.code == 1) {
            swal({title: 'Oops!', text:'Anda telah memutuskan untuk tidak membagikan posisi Anda, tetapi tidak apa-apa. Kami tidak akan meminta Anda lagi.', icon: 'error', timer: 3000,});
        } else if(error.code == 2) {
            swal({title: 'Oops!', text:'Jaringan tidak aktif atau layanan penentuan posisi tidak dapat dijangkau.', icon: 'error', timer: 3000,});
        } else if(error.code == 3) {
            swal({title: 'Oops!', text:'Waktu percobaan habis sebelum bisa mendapatkan data lokasi.', icon: 'error', timer: 3000,});
        } else {
            swal({title: 'Oops!', text:'Waktu percobaan habis sebelum bisa mendapatkan data lokasi.', icon: 'error', timer: 3000,});
        }
    }
</script>
   @endpush
</x-employee.layout>
