<x-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Edit Shift</h3>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.shifts.update', $shift->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Shift</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ $shift->name }}" placeholder="Masukkan nama shift" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="time_in" class="form-label">Waktu Masuk</label>
                                <input type="time" name="time_in" id="time_in" class="form-control @error('time_in') is-invalid @enderror" value="{{ $shift->time_in }}" required>
                                @error('time_in')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="time_out" class="form-label">Waktu Keluar</label>
                                <input type="time" name="time_out" id="time_out" class="form-control @error('time_out') is-invalid @enderror" value="{{ $shift->time_out }}" required>
                                @error('time_out')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('admin.shifts.index') }}" class="btn btn-default">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
