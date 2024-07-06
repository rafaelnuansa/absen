<x-layout>

    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card card-animate">
                    <div class="card-header">
                        <h3 class="card-title"><b>Tambah Data Karyawan</b></h3>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.employees.store') }}" method="POST" enctype="multipart/form-data"
                            class="row g-3 needs-validation" novalidate>
                            @csrf
                            <div class="col-md-6">
                                <label class="form-label">NIK</label>
                                <input type="text" class="form-control" name="code" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nama</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control" name="email" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Jabatan</label>
                                <select class="form-select" name="position_id" required>
                                    @foreach ($positions as $position)
                                        <option value="{{ $position->id }}">{{ $position->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Penempatan</label>
                                <select class="form-select" name="building_id" id="building" required>
                                    @foreach ($buildings as $building)
                                        <option value="{{ $building->id }}">{{ $building->name }} |
                                            {{ $building->address }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Foto</label>
                                <input type="file" id="imgInp" class="form-control" name="avatar"
                                    accept="image/jpeg, image/jpg, image/gif, image/png" capture required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Status Aktif</label>
                                <select class="form-select" name="is_active" required>
                                    <option value="1" selected>Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                            </div>
                            
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</button>
                                <a class="btn btn-danger" href="{{ route('admin.employees.index') }}"><i
                                        class="fa fa-remove"></i> Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
