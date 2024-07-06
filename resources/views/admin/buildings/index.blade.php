<x-layout>

    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="mb-3">
                    <a href="{{ route('admin.buildings.create') }}" class="btn btn-primary">Tambah
                        Lokasi</a>
                </div>
                <div class="card card-animate">
                    <div class="card-header with-border">
                        <h3 class="card-title">Lokasi</h3>
                    </div>

                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th class="text-center">Jumlah Karyawan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($buildings as $building)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $building->code }}</td>
                                        <td>{{ $building->name }}</td>
                                        <td>{{ $building->address }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-dark"> {{ $building->employees->count() }}</span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li><a class="dropdown-item" href="{{ route('admin.buildings.edit', $building->id) }}">Edit</a></li>
                                                    <li>
                                                        <form action="{{ route('admin.buildings.destroy', $building->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this building?')">Delete</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>


                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">No buildings found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
