<x-layout>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Edit Jabatan</h5>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.positions.update', $position->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="position_name" class="form-label">Nama Jabatan</label>
                                <input type="text" name="name" id="position_name" class="form-control" value="{{ $position->name }}" required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('admin.positions.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
