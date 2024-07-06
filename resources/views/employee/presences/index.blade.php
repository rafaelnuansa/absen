@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function capturePresence() {
            const webcamElement = document.getElementById('webcam');
            const snapSoundElement = document.getElementById('snapSound');
            const latLongElement = document.getElementById('latLong');
            const clockElement = document.getElementById('clock');

            // Play snap sound
            snapSoundElement.play();

            // Capture image from webcam
            Webcam.snap(function(dataUri) {
                // Convert data URI to Blob
                const blob = dataURItoBlob(dataUri);

                // Convert Blob to base64
                const reader = new FileReader();
                reader.onloadend = function() {
                    const base64String = reader.result.split(',')[1];

                    // Send base64 string to server using Axios
                    axios.post('{{ route('employee.presence.store') }}', {
                            latitude_longitude: latLongElement.textContent,
                            resultCapture: base64String,
                        })
                        .then(function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Absen Berhasil',
                                text: response.data.message
                            }).then((result) => {
                                // Redirect to specified page if successful
                                if (response.data.redirect) {
                                    window.location.href = response.data.redirect;
                                }
                            });
                        })
                        .catch(function(error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Absen Gagal',
                                text: error.response.data.error
                            });
                        });
                };
                reader.readAsDataURL(blob);
            });
        }

            const webcamElement = document.getElementById('webcam');
            const snapSoundElement = document.getElementById('snapSound');
            const captureButton = document.getElementById('capture');
            const latLongElement = document.getElementById('latLong');
            const clockElement = document.getElementById('clock');

            let cameras = []; // Initialize cameras array outside the enumerateDevices function
            navigator.mediaDevices.enumerateDevices()
                .then(function(devices) {
                    devices.forEach(function(device) {
                        if (device.kind === "videoinput") {
                            cameras.push(device.deviceId);
                        }
                    });
                    // Set the webcam source to the first available camera
                    if (cameras.length > 0) {
                        Webcam.set({
                            width: 590,
                            height: 460,
                            image_format: 'jpeg',
                            jpeg_quality: 90,
                            sourceId: cameras[0] // Set the sourceId to the first camera device ID
                        });

                        Webcam.attach('#webcam');
                    } else {
                        // Show SweetAlert error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Tidak ada perangkat kamera',
                            text: 'Tidak ada perangkat kamera yang ditemukan!'
                        });
                    }
                })
                .catch(function(err) {
                    // Show SweetAlert error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Mendapatkan Perangkat Media',
                        text: 'Terjadi kesalahan saat mencoba mendapatkan perangkat media: ' + err
                            .message
                    });
                });

            function dataURItoBlob(dataURI) {
                // Split data URI to get content type and base64 encoded data
                const [type, base64] = dataURI.split(';base64,');
                // Decode base64 encoded data
                const byteString = atob(base64);
                // Convert byte string to ArrayBuffer
                const buffer = new ArrayBuffer(byteString.length);
                // Create a Uint8Array view of ArrayBuffer
                const arrayBufferView = new Uint8Array(buffer);
                // Fill ArrayBuffer view with byte data
                for (let i = 0; i < byteString.length; i++) {
                    arrayBufferView[i] = byteString.charCodeAt(i);
                }
                // Create Blob object from ArrayBuffer
                return new Blob([arrayBufferView], {
                    type: type.split(':')[1]
                });
            }

            // Function to take snapshot
            function takeSnapshot() {
                // Play snap sound
                snapSoundElement.play();
                // Capture image from webcam
                Webcam.snap(function(dataUri) {
                    // Convert data URI to Blob
                    const blob = dataURItoBlob(dataUri);
                    // Convert Blob to base64
                    const reader = new FileReader();
                    reader.onloadend = function() {
                        const base64String = reader.result.split(',')[1];
                        // Kirim base64 string ke server menggunakan Axios
                        axios.post('{{ route('employee.presence.store') }}', {
                                latitude_longitude: latLongElement.textContent,
                                resultCapture: base64String,
                            })
                            .then(function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Absen Berhasil',
                                    text: response.data.message
                                }).then((result) => {
                                    // Redirect ke halaman yang ditentukan jika berhasil
                                    if (response.data.redirect) {
                                        window.location.href = response.data.redirect;
                                    }
                                });
                            })
                            .catch(function(error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Absen Gagal',
                                    text: error.response.data.error
                                });
                            });
                    };
                    reader.readAsDataURL(blob);
                });
            }
            function updateClock() {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                clockElement.textContent = `${hours}:${minutes}:${seconds}`;
            }
            // Function to update live latitude-longitude coordinates
            function updateLatLong() {
                navigator.geolocation.getCurrentPosition(position => {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;
                    latLongElement.textContent = `${latitude}, ${longitude}`;
                }, error => {
                    console.error('Error getting geolocation: ', error);
                    Swal.fire({
                        icon: 'warning',
                        title: 'Gagal mendapatkan geolokasi',
                        text: 'Tidak dapat menentukan lokasi Anda. Pastikan untuk mengaktifkan layanan lokasi pada perangkat Anda.'
                    });
                });

            }
            // Update clock and coordinates every second
            setInterval(updateClock, 1000);
            updateLatLong();
            // Event listener for capturing image
            captureButton.addEventListener('click', takeSnapshot);
        });
    </script>
@endpush

<x-employee.layout>
    <div class="content-inner pt-0">
        <div>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <p><strong>Coordinate</strong></p>
                                        <p><strong>{{ $employee->name }}</strong></p>
                                    </div>
                                    <div class="col-6">
                                        <p class="text-end" id="latLong">-</p>
                                        <p class="text-end" id="clock">-</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-10 mx-auto">
                                        <div id="camera-container" class="mx-auto webcam-capture">
                                            <div class="webcam-capture" id="webcam" class="mb-5 w-100" playsinline
                                                style="border-radius:30px"></div>
                                            <audio id="snapSound" src="{{ asset('audio/snap.mp3') }}"
                                                preload="auto"></audio>
                                        </div>
                                        <div class="text-center">
                                            @if (!$presence)
                                                <button class="btn btn-primary mb-2 w-100" id="capture"
                                                    onclick="capturePresence()">
                                                    <i class="ri-camera-3-line me-2"></i> Absen Masuk
                                                </button>
                                            @elseif ($presence && $presence->time_in && !$presence->time_out)
                                                <button class="btn btn-primary mb-2 w-100" id="capture"
                                                    onclick="capturePresence()">
                                                    <i class="ri-camera-3-line me-2"></i> Absen Pulang
                                                </button>
                                            @elseif ($presence && $presence->time_in && $presence->time_out)
                                                <button class="btn btn-primary mb-2 w-100" id="capture" disabled>
                                                    <i class="ri-camera-3-line me-2"></i> Absen Selesai
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-employee.layout>
