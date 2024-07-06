<x-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-solid">
                    <div class="card-header with-border">
                        <h3 class="card-title">Tambah Shift</h3>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.shifts.store') }}" method="POST">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="name">Nama Shift</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Masukkan nama shift" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="time_in">Waktu Masuk</label>
                                <input type="time" name="time_in" id="time_in" class="form-control" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="time_out">Waktu Keluar</label>
                                <input type="time" name="time_out" id="time_out" class="form-control" required>
                            </div>

                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('admin.shifts.index') }}" class="btn btn-light">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
