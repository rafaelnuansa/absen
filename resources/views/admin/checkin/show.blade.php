<x-layout>

    <section class="content-header">
        <h1>Detail<small> Checkin / Checkpoint</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Beranda</a></li>
            <li><a href="{{ route('admin.checkin.index') }}">Data Checkpoint</a></li>
            <li class="active">Detail</li>
        </ol>
    </section>


    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><b>Detail Checkpoint</b></h3>
                        <div class="box-tools pull-right">
                            <a href="{{ route('admin.checkin.index') }}" class="btn btn-default btn-flat">Kembali</a>
                        </div>
                    </div>

                    <div class="box-body">
                        <h4>Nama : <span class="employees_name">{{ $employee->employees_name }}</span></h4>
                        <h4>Jabatan : {{ $employee->position->position_name }}</h4>
                        <hr>
                        <form action="{{ route('admin.checkin.show', $employee->id) }}" method="GET">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select class="form-control month" name="month" required="">
                                            @for ($month = 1; $month <= 12; $month++)
                                                <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}"
                                                    {{ $month == now()->month ? 'selected' : '' }}>
                                                    {{ \Carbon\Carbon::create()->month($month)->format('F') }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select class="form-control year" name="year" required="">
                                            @php
                                                $currentYear = now()->year;
                                                $startYear = $currentYear - 5;
                                                $endYear = $currentYear + 5;
                                            @endphp
                                            @for ($year = $startYear; $year <= $endYear; $year++)
                                                <option value="{{ $year }}"
                                                    {{ $year == $currentYear ? 'selected' : '' }}>{{ $year }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="btn-group pull-right">
                                        <button type="submit" class="btn btn-primary btn-sortir">Tampilkan</button>
                                        <!-- Tombol Submit -->
                                        <button type="button" class="btn btn-warning">Ekspor/Cetak</button>
                                        <button type="button" class="btn btn-warning dropdown-toggle"
                                            data-toggle="dropdown" aria-expanded="false">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#" class="btn-print" data-id="pdf">PDF</a></li>
                                            <li><a href="#" class="btn-print" data-id="excel">EXCEL</a></li>
                                            <li><a href="#" class="btn-print" data-id="print">PRINT</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <div id="swdatatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">

                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-hover dataTable no-footer"
                                            id="swdatatable" role="grid" aria-describedby="swdatatable_info">
                                            <thead>

                                                <tr>
                                                    <td>No</td>
                                                    <td>Tanggal</td>
                                                    <td><i class="fa fa-image"></i></td>
                                                    <td>Jam</td>
                                                    <td>Informasi</td>
                                                    <td>Aksi</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($checkinsByDate as $date => $checkins)
                                                    @php
                                                        $carbonDate = Carbon\Carbon::parse($date);
                                                    @endphp
                                                    <tr @if ($carbonDate->isSunday()) class="bg-red" @endif>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $carbonDate->isoFormat('dddd, D MMMM YYYY') }}</td>
                                                            @if ($checkins->isEmpty())
                                                        <td colspan="4" align="center">Tidak ada data</td>
                                                    @else
                                                        @foreach ($checkins as $checkin)
                                                            <td>
                                                                <i class="fa fa-image"></i>
                                                            </td>
                                                            <td>{{ $checkin->time }}</td>
                                                            <td>{{ $checkin->information }}

                                                            </td>

                                                            <td>Aksi</td>
                                                        @endforeach
                                                @endif

                                                </tr>
                                                @endforeach
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout>
