<x-layout>

    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Absensi Karyawan</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                            <!-- Filter Search -->
                            <div class="row mb-5">
                                <div class="col-md-7">
                                    <form action="{{ route('admin.employees.index') }}" method="GET">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="search"  value="{{ request()->input('search') }}" placeholder="Cari berdasarkan kode atau nama...">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-5">
                                    <div class="row px-2">
                                        <a href="{{ route('admin.presences.all')}}" class="btn btn-primary">Absensi Semua Data Karyawan</a>
                                    </div>
                                </div>
                            </div>

                           
                            <!-- End Filter Search -->
                        <div class="table-responsive table-card">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Jabatan</th>
                                        <th>Lokasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($employees as $employee)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $employee->code }}</td>
                                            <td>{{ $employee->name }}</td>
                                            <td>{{ $employee->email }}</td>
                                            <td>{{ $employee->position->name }}</td>
                                            <td>{{ $employee->building->name }}</td>
                                            <td>
                                                <a href="{{ route('admin.presences.show', $employee->id) }}"
                                                    class="btn btn-sm btn-primary fw-bold">Detail</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="8">Tidak ada data karyawan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="card-footer ">
                        {{ $employees->links() }}
                    </div>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
    </div>
</x-layout>
