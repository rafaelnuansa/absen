<x-layout>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><b>Edit Checkpoint</b></h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.checkpoint.update', $checkpoint->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="code" class="form-label">Kode</label>
                                <input type="text" name="code" class="form-control" id="code" value="{{ $checkpoint->code }}">
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Checkpoint</label>
                                <input type="text" name="name" class="form-control" id="name" value="{{ $checkpoint->name }}">
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Lokasi</label>
                                
                            <select name="building_id" id="building_id" class="form-control">
                                <option value="">Select Location</option>
                                @foreach($buildings as $building)
                                    <option value="{{ $building->id }}" {{ $building->id == $checkpoint->building_id ? 'selected' : '' }}>{{ $building->name }}</option>
                                @endforeach
                            </select>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" class="form-control" id="description">{{ $checkpoint->description }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Update
                            </button>

                            <a href="{{ route('admin.checkpoint.index') }}" class="btn btn-light">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
