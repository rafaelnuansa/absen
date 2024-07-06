@push('scripts')
<script>
    const video = document.createElement('video');
    const videoContainer = document.getElementById('qris-video-container');

    let scanner = null;
    let scanning = false;

    async function startScan() {
        if (!scanner) {
            videoContainer.appendChild(video);
            scanner = new QrScanner(video, result => {
                console.log(result);
                // Kirim hasil pemindaian ke server
                sendScanResult(result);
            }, error => {
                console.log(error);
                // Tangani error jika diperlukan
            });

            scanner.highlightScanRegion = true; // Aktifkan highlight style
        }

        const startButton = document.getElementById('start-scan-button');
        startButton.disabled = true;

        await QrScanner.listCameras(true).then(cameras => {
            const cameraSelect = document.getElementById('camera-select');
            cameraSelect.innerHTML = '';
            cameras.forEach(camera => {
                const option = document.createElement('option');
                option.value = camera.id;
                option.text = camera.label || `Camera ${camera.id}`;
                cameraSelect.appendChild(option);
            });
        });

        selectCamera();
        scanner.start();
        scanning = true;

        startButton.disabled = false;
        updateButtonVisibility();
    }

    function selectCamera() {
        const cameraId = document.getElementById('camera-select').value;
        if (scanner) {
            scanner.setCamera(cameraId);
        }
    }

    function stopScan() {
        if (scanner) {
            scanner.stop();
            scanning = false;
            updateButtonVisibility();
        }
    }

    function updateButtonVisibility() {
        const startButton = document.getElementById('start-scan-button');
        const stopButton = document.getElementById('stop-scan-button');

        if (scanning) {
            startButton.style.display = 'none';
            stopButton.style.display = 'block';
        } else {
            startButton.style.display = 'block';
            stopButton.style.display = 'none';
        }
    }

    function sendScanResult(result) {
        // Buat objek JSON yang berisi hasil pemindaian
        const data = { checkpoint_code: result };

        // Kirim hasil pemindaian ke route employee.patrol.store menggunakan fetch API
        fetch('{{ route("employee.patrol.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Sertakan token CSRF jika menggunakan Laravel
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            // Tangani respons dari server
            if (response.ok) {
                // Respons OK, lakukan apa yang diperlukan
                console.log('Success:', response);
                // Tampilkan SweetAlert untuk notifikasi berhasil
                Swal.fire({
                    icon: 'success',
                    title: 'Scan berhasil',
                    text: 'Patroli berhasil dibuat.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            } else {
                // Tangani kesalahan jika diperlukan
                console.error('Error:', response);
                // Tampilkan SweetAlert untuk notifikasi kesalahan
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat membuat patroli.',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            // Tangani kesalahan jaringan atau kesalahan lainnya
            console.error('Error:', error);
            // Tampilkan SweetAlert untuk notifikasi kesalahan
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan saat membuat patroli.',
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        });
    }
</script>
@endpush


<x-employee.layout>
    <div class="content-inner pt-0">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <h2>{{ $checkpoint->location }}</h2>
                    <div class="row">
                        <div class="col-6">
                            <p><strong>Checkpoint</strong></p>
                            <p><strong>Status Patroli</strong></p>
                        </div>
                        <div class="col-6">
                            <p class="text-end">{{ $checkpoint->code }}</p>
                            <p class="text-end">
                                @if ($checkpoint->patrol)
                                    @if ($checkpoint->patrol->status === 'completed')
                                        <span class="text-success">Patroli Selesai</span>
                                    @elseif($checkpoint->patrol->status === 'pending')
                                        <span class="text-warning">Patroli Belum Selesai</span>
                                    @endif
                                @else
                                    <span class="text-muted">Belum ada patroli</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-10 mx-auto">
                            <div id="qris-video-container"></div>
                            <label for="camera-select" class="form-label">Pilih Kamera:</label>
                            <select id="camera-select" class="form-select" onchange="selectCamera()"></select>
                            <div class="text-center mt-3">
                                <button id="start-scan-button" onclick="startScan()" class="btn w-100 mt-2 btn-primary">
                                    <i class="ri-play-circle-line me-2"></i>Mulai scan
                                </button>
                                <button id="stop-scan-button" onclick="stopScan()" style="display: none;" class="btn w-100 mt-2 btn-primary">
                                    <i class="ri-stop-circle-line me-2"></i>Hentikan scan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <a href="{{ route('employee.patrol') }}" class="btn btn-secondary">
                <i class="ri-arrow-left-line me-2"></i>Kembali
            </a>
        </div>
    </div>
</x-employee.layout>
