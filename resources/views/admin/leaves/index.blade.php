<x-layout>


    <div class="container-fluid">

        <div class="row">
            <div class="col-xs-12">
                <div class="card card-animate">


                    <div class="card-body">
                        <form action="{{ route('admin.leaves.index') }}" method="GET" class="form p-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="search">Cari Nama Karyawan</label>
                                        <input type="text" name="search" id="search" class="form-control"
                                            placeholder="Cari nama karyawan" value="{{ request()->input('search') }}">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="start_date">Tanggal Mulai</label>
                                        <input type="date" name="start_date" id="start_date" class="form-control"
                                            placeholder="Tanggal Mulai" value="{{ request()->input('start_date') }}">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="end_date">Tanggal Selesai</label>
                                        <input type="date" name="end_date" id="end_date" class="form-control"
                                            placeholder="Tanggal Selesai"  value="{{ request()->input('end_date') }}">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="status-leave">Status</label>
                                        <select name="status" id="status-leave" class="form-control">
                                            <option value="">Semua Status</option>
                                            <option value="approved" {{ request()->input('status') == 'approved' ? 'selected' : '' }}>
                                                Disetujui</option>
                                            <option value="rejected" {{ request()->input('status') == 'rejected' ? 'selected' : '' }}>Tidak
                                                Disetujui</option>
                                            <option value="pending" {{ request()->input('status') == 'pending' ? 'selected' : '' }}>Menunggu
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="btn-group  w-100 mt-4">
                                            <button type="submit" id="filter_btn"
                                                class="btn btn-primary">Filter</button>
                                            <a href="{{ route('admin.leaves.index') }}"
                                                class="btn btn-primary">Refresh</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>




                    </div>
                </div>
                <div class="card card-animate">
                    <div class="card-header with-border">
                        <h3 class="card-title">Daftar Pengajuan Cuti</h3>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive table-card">
                            <table class="table  table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Karyawan</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
                                        <th>Total Hari</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leaves as $leave)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $leave->employee->name }}</td>
                                            <td>{{ $leave->start_date }}</td>
                                            <td>{{ $leave->end_date }}</td>
                                            <td>{{ $leave->total }}</td>
                                            <td>{{ $leave->created_at }}</td>

                                            <td>
                                                @php
                                                    $status = '';
                                                    $badge_color = '';

                                                    switch ($leave->status) {
                                                        case 'approved':
                                                            $status = 'approved';
                                                            $badge_color = 'primary';
                                                            break;
                                                        case 'rejected':
                                                            $status = 'rejected';
                                                            $badge_color = 'danger';
                                                            break;
                                                        case 'pending':
                                                            $status = 'pending';
                                                            $badge_color = 'dark';
                                                            break;
                                                        default:
                                                            $status = 'Belum Diketahui';
                                                            $badge_color = 'dark';
                                                            break;
                                                    }
                                                @endphp

                                                <span class="badge bg-{{ $badge_color }}">{{ $leave->status }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.leaves.show', $leave->id )}}" class="btn btn-primary btn-sm">Detail</a>
                                            </td>


                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $leaves->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
