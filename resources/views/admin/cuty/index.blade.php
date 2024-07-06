    <x-layout>
        <section class="content-header">
            <h1>Data<small> Permohonan Cuti</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Beranda</a></li>
                <li class="active">Cuti</li>
            </ol>
        </section>

        <section class="content">

            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Daftar Pengajuan Cuti</h3>
                        </div>

                        <div class="box-body">
                            <form action="{{ route('admin.cuty.index') }}" method="GET" class="form p-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="search">Cari Nama Karyawan</label>
                                            <input type="text" name="search" id="search" class="form-control" placeholder="Cari nama karyawan" value="{{ old('search') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="start_date">Tanggal Mulai</label>
                                            <input type="date" name="start_date" id="start_date" class="form-control" placeholder="Tanggal Mulai" value="{{ old('start_date') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="end_date">Tanggal Selesai</label>
                                            <input type="date" name="end_date" id="end_date" class="form-control" placeholder="Tanggal Selesai" value="{{ old('end_date') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="">Semua Status</option>
                                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Disetujui</option>
                                                <option value="2" {{ old('status') == '2' ? 'selected' : '' }}>Tidak Disetujui</option>
                                                <option value="3" {{ old('status') == '3' ? 'selected' : '' }}>Menunggu</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                           <div class="btn-group">
                                            <button type="submit" id="filter_btn" class="btn btn-primary">Filter</button>
                                            <a href="{{ route('admin.cuty.index')}}" class="btn btn-primary">Refresh</a>
                                           </div>
                                        </div>
                                    </div>
                                </div>
                            </form>




                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Karyawan</th>
                                            <th>Tanggal Mulai</th>
                                            <th>Tanggal Selesai</th>
                                            <th>Total Hari</th>
                                            <th>Keterangan</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cuties as $cuty)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $cuty->employee->employees_name }}</td>
                                                <td>{{ $cuty->cuty_start }}</td>
                                                <td>{{ $cuty->cuty_end }}</td>
                                                <td>{{ $cuty->cuty_total }}</td>
                                                <td>{{ $cuty->cuty_description }}</td>

                                                <td>
                                                    @php
                                                        $status = '';
                                                        $badge_color = '';

                                                        switch ($cuty->cuty_status) {
                                                            case 1:
                                                                $status = 'Disetujui';
                                                                $badge_color = 'success';
                                                                break;
                                                            case 2:
                                                                $status = 'Tidak Disetujui';
                                                                $badge_color = 'danger';
                                                                break;
                                                            case 3:
                                                                $status = 'Menunggu';
                                                                $badge_color = 'warning';
                                                                break;
                                                            default:
                                                                $status = 'Belum Diketahui';
                                                                $badge_color = 'default';
                                                                break;
                                                        }
                                                    @endphp

                                                    <span
                                                        class="badge bg-{{ $badge_color }}">{{ $status }}</span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-primary btn-xs">Change Status</button>
                                                    <button class="btn btn-info btn-xs">Print</button>
                                                </td>


                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $cuties->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </x-layout>
