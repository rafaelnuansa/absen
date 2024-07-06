<x-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="mb-3">
                    <a href="{{ route('admin.shifts.create') }}" class="btn btn-primary">Tambah Shift</a>
                </div>
                <div class="card card-solid">
                    <div class="card-header with-border">
                        <h3 class="card-title">Daftar Shift</h3>

                    </div>

                    <div class="card-body">
                        <div class="table-responsive table-card">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Shift</th>
                                        <th>Waktu Masuk</th>
                                        <th>Waktu Keluar</th>
                                        <th>Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($shifts as $shift)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $shift->name }}</td>
                                            <td>{{ $shift->time_in }}</td>
                                            <td>{{ $shift->time_out }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Aksi
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <li><a class="dropdown-item" href="{{ route('admin.shifts.edit', $shift->id) }}">Edit</a></li>
                                                        <li>
                                                            <form action="{{ route('admin.shifts.destroy', $shift->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this shift?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item">Hapus</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>

                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">No shifts found.</td>
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
