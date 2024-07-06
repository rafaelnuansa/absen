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

    <video id="preview"></video>
                    <div class="form-group basic">
                        <button class="btn btn-danger btn-lg btn-block" onclick="captureQR()">
                            <i class="fa fa-qrcode mr-1"></i> SCAN QR CODE
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="{{ asset('instascan.min.js')}}"></script>
    <script type="text/javascript">
      let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
      scanner.addListener('scan', function (content) {
        console.log(content);
      });
      Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
          scanner.start(cameras[0]);
        } else {
          console.error('No cameras found.');
        }
      }).catch(function (e) {
        console.error(e);
      });
    </script>
</x-employee.layout>
