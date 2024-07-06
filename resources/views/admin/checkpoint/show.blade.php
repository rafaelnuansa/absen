<x-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><b>Detail Checkpoint</b></h3>
                    </div>
                    <div class="card-body">
                        <!-- Informasi Checkpoint -->
                        <div class="mb-3">
                            <label class="form-label">Kode:</label>
                            <p>{{ $checkpoint->code }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lokasi:</label>
                            <p>{{ $checkpoint->building->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi:</label>
                            <p>{{ $checkpoint->description }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">QR Code:</label>
                            <a href="{{ asset($checkpoint->qrcode) }}"
                                alt="QR Code {{ $checkpoint->code }}" class="btn btn-primary"><i class="ri-qr-code-line"></i></a>
                        </div>
                        <div>
                            <a href="{{ route('admin.checkpoint.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
