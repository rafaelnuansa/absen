<x-layout>


    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><b>New Checkpoint</b></h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.checkpoint.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="code" class="form-label">Kode:</label>
                                    <input type="text" name="code" id="code" class="form-control"
                                        placeholder="Kode untuk QR" required>
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name:</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Masukkan Nama Checkpoint" required>
                                </div>
                                <div class="mb-3">
                                    <label for="building_id" class="form-label">Location:</label>
                                    <select name="building_id" id="building_id" class="form-control" required>
                                        <option value="">Select Building</option>
                                        @foreach($buildings as $building)
                                            <option value="{{ $building->id }}">{{ $building->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Deskripsi:</label>
                                    <textarea name="description" id="description" class="form-control"
                                        placeholder="Masukkan deskripsi"></textarea>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-success">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout>
