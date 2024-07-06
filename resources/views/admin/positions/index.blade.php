<x-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col">

                <a href="{{ route('admin.positions.create') }}" class="btn btn-primary mb-3">Tambah
                    Jabatan</a>
                <div class="card">
                    <div class="card-header">
                       <h3 class="card-title"> Daftar Jabatan</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-card">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Jabatan</th>
                                        <th>Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($positions as $position)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $position->name }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-soft-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ri-more-fill"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                                        <li><a class="dropdown-item"
                                                            href="{{ route('admin.positions.edit', $position->id) }}">Edit</a>
                                                    </li>
                                                        <li>
                                                            <form
                                                            action="{{ route('admin.positions.destroy', $position->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Are you sure you want to delete this position?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item">Delete</button>
                                                        </form>
                                                        </li>
                                                      </ul>
                                                </div>


                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3">No positions found.</td>
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
