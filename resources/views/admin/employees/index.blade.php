<x-layout>

    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card-tools mb-3 pull-right"><a href="{{ route('admin.employees.create') }}"
                        class="btn btn-primary "><i class="fa fa-plus"></i> Tambah
                        Baru</a>
                </div>
                <div class="card card-animate ">
                    <div class="card-header">

                        <h3 class="card-title">Data Karyawan</h3>
                    </div>
                    <div class="card-body">

                        <div class="row mb-5">
                            <div class="col-md-12">
                                <form action="{{ route('admin.employees.index') }}" method="GET">
                                    <div class="row">
                                        <!-- Existing filter fields -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="search">Cari:</label>
                                                <input type="text" class="form-control" name="search" value="{{ request()->input('search') }}" placeholder="Cari berdasarkan kode atau nama...">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="is_active">Aktif:</label>
                                                <select name="is_active" id="is_active" class="form-control">
                                                    <option value="">Semua</option>
                                                    <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                                                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Nonaktif</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="position_id">Jabatan:</label>
                                                <select name="position_id" id="position_id" class="form-control">
                                                    <option value="">Semua</option>
                                                    @foreach ($positions as $position)
                                                        <option value="{{ $position->id }}" {{ request('position_id') == $position->id ? 'selected' : '' }}>
                                                            {{ $position->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="building_id">Lokasi:</label>
                                                <select name="building_id" id="building_id" class="form-control">
                                                    <option value="">Semua</option>
                                                    @foreach ($buildings as $building)
                                                        <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
                                                            {{ $building->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Filter button -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Filter</button>
                                               
                                                <a href="{{ route('admin.employees.export', request()->all()) }}" class="btn w-full btn-success">Export</a>
                                            </div>
                                        </div>
                                        <!-- Export button -->
                                     
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- End Filter Search -->
                        <!-- Informasi filter yang diterapkan -->
                        <div class="row mb-3">
                            <div class="col">
                                <span class="badge bg-primary">Cari:
                                    {{ request()->input('search') ? request()->input('search') : 'Semua' }}</span>
                                <span class="badge bg-primary">Aktif:
                                    {{ request()->input('is_active') ? (request()->input('is_active') == '1' ? 'Aktif' : 'Nonaktif') : 'Semua' }}</span>
                                <span class="badge bg-primary">Jabatan:
                                    {{ $positionFilter ? $positionFilter->name : 'Semua' }}</span>
                                <span class="badge bg-primary">Lokasi:
                                    {{ $buildingFilter ? $buildingFilter->name : 'Semua' }}</span>
                                <a href="{{ route('admin.employees.index') }}" class="badge bg-secondary">Clear
                                    Filters</a>
                            </div>
                        </div>


                        <!-- End Filter Search -->
                        <div class="table-responsive table-card">
                            <table id="employees" class="table table-nowrap" aria-describedby="_info">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Jabatan</th>
                                        <th>Lokasi</th>
                                        <th>Aktif</th>
                                        <th>Register Date</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($employees as $employee)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $employee->code }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($employee->avatar && file_exists(public_path('storage/avatars/' . $employee->avatar)))
                                                        <div class="flex-shrink-0">
                                                            <img src="{{ asset('storage/avatars/' . $employee->avatar) }}"
                                                                alt="{{ $employee->name }}"
                                                                class="avatar-xxs rounded-circle image_src object-fit-cover">
                                                        </div>
                                                    @else
                                                        <div class="flex-shrink-0">
                                                            <div
                                                                class="avatar-xxs rounded-circle image_src object-fit-cover bg-primary text-white text-center">
                                                                {{ strtoupper($employee->name[0]) }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="flex-grow-1 ms-2">{{ $employee->name }}</div>
                                                </div>
                                            </td>

                                            <td>{{ $employee->email }}</td>
                                            <td>{{ $employee->position->name }}</td>
                                            <td>{{ $employee->building->name }}</td>
                                            <td>{{ $employee->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                                            <td>{{ $employee->created_at->isoFormat('DD MMMM YYYY, HH:mm:ss') }}</td>

                                            <td>

                                                <div class="dropdown">
                                                    <button type="button"
                                                        class="btn btn-primary btn-sm dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ri-more-fill fs-16"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item"
                                                                href="{{ route('admin.employees.edit', $employee->id) }}">Edit</a>
                                                        </li>
                                                        <li>
                                                            <form
                                                                action="{{ route('admin.employees.destroy', $employee->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Are you sure you want to delete this employee?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item"
                                                                    title="Hapus">
                                                                    Hapus</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>


                    </div>

                    <div class="card-footer">
                        <div class="col-md-12 ">
                            {{ $employees->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
