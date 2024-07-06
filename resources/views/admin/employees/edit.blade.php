<x-layout>

    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card card-animate">
                    <div class="card-header">
                        <h3 class="card-title"><b>Edit Data Karyawan</b></h3>
                    </div>

                    <div class="card-body">
                        <form enctype="multipart/form-data" action="{{ route('admin.employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data"
                            class="row g-3 needs-validation" novalidate>
                            @csrf
                            @method('PUT')
                            <div class="col-md-6">
                                <label class="form-label">NIK</label>
                                <input type="text" class="form-control" name="code" value="{{ $employee->code }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nama</label>
                                <input type="text" class="form-control" name="name" value="{{ $employee->name }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control" name="email" value="{{ $employee->email }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Jabatan</label>
                                <select class="form-select" name="position_id" required>
                                    @foreach ($positions as $position)
                                        <option value="{{ $position->id }}" @if($position->id == $employee->position_id) selected @endif>{{ $position->name }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-md-6">
                                <label class="form-label">Penempatan</label>
                                <select class="form-select" name="building_id" id="building" required>
                                    @foreach ($buildings as $building)
                                        <option value="{{ $building->id }}" @if($building->id == $employee->building_id) selected @endif>{{ $building->name }} | {{ $building->address }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Foto</label>
                                <input type="file" id="imgInp" class="form-control" name="avatar" accept="image/jpeg, image/jpg, image/gif, image/png" capture>
                                @if ($employee->avatar)
                                    <img class="img-thumbnail" src="{{ asset('storage/avatars/' . $employee->avatar) }}" alt="Employee avatar" class="img-responsive" style="max-height: 200px; margin-top: 10px;">
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status Aktif</label>
                                <select class="form-select" name="is_active" required>
                                    <option value="1" @if($employee->is_active == 1) selected @endif>Aktif</option>
                                    <option value="0" @if($employee->is_active == 0) selected @endif>Tidak Aktif</option>
                                </select>
                            </div>
                            
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</button>
                                <a class="btn btn-danger" href="{{ route('admin.employees.index') }}"><i class="fa fa-remove"></i> Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
