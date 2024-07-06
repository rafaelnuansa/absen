<x-layout>
    <div class="container-fluid">
        <a href="{{ route('admin.presences.index') }}" class="btn btn-dark mb-4"><i class="mdi mdi-arrow-left"></i>
            Kembali</a>
        <a href="{{ route('admin.presences.createNew', ['employeeId' => $employee->id]) }}" class="btn btn-dark mb-4">
            <i class="mdi mdi-plus"></i> Create
        </a>
        <div class="row">

            <div class="col-12">
                <div class="card">

                    <div class="card-body">
                        <form action="{{ route('admin.presences.show', $employee->id) }}" method="GET">
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
                                    <div class="btn-group  w-100">
                                        <button type="submit" class="btn btn-dark btn-sortir">Tampilkan</button>
                                        <!-- Tombol Submit -->
                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="{{ route('admin.presences.show', ['presence' => $employee->id, 'year' => request('year'), 'month' => request('month'), 'export' => 'excel']) }}"
                                                    class="dropdown-item">Export to Excel</a>
                                            </li>

                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card card-solid">
                    <div class="card-header">
                        <h3 class="card-title"><b>{{ $employee->name }}</b></h3>
                        <p class="text-muted">{{ $employee->position->name }}</p>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive table-card">

                            <table class="table table-hover dataTable no-footer" id="presences" role="grid">
                                <thead class="fw-bold">
                                    <tr>
                                        <td>No</td>
                                        <td>Tanggal</td>
                                        <td>Jam Masuk</td>
                                        <td>Jam Pulang</td>
                                        <td>Shift</td>
                                        <td>Status</td>
                                        <td>Aksi</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($absenByDate as $date => $absens)
                                        @php
                                            $carbonDate = \Carbon\Carbon::parse($date);
                                        @endphp
                                        <tr @if ($carbonDate->isSunday()) class="bg-danger text-white" @endif>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $carbonDate->isoFormat('dddd, D MMMM YYYY') }}</td>
                                            @if (empty($absens))
                                                <td colspan="9" align="center">Tidak ada data</td>
                                            @else
                                                @foreach ($absens as $absenData)
                                                    @php
                                                        $absen = $absenData['absensi'];
                                                    @endphp
                                                    <td>{{ $absen->time_in }}</td>
                                                    <td>{{ $absen->time_out ?? '-' }}</td>
                                                    <td>{{ $absen->shift ?? '-' }}</td>
                                                    <td>
                                                        <span class="badge bg-dark">{{ $absen->status }}</span>
                                                    </td>
                                                    <td class="text-right">
                                                        <a href="{{ route('admin.presences.detail', ['employeeId' => $absen->employee_id, 'presenceId' => $absen->id]) }}"
                                                            class="btn btn-primary btn-sm btn-modal enable-tooltip"
                                                            title="Lokasi">
                                                            Detail
                                                        </a>

                                                    </td>
                                                @endforeach
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-3">
                                    <p>Hadir : <span class="label label-success">{{ $hadirCount }}</span></p>
                                </div>
                                <div class="col-md-3">
                                    <p>Terlambat : <span class="label label-danger">{{ $terlambatCount }}</span></p>
                                </div>
                                <div class="col-md-3">
                                    <p>Sakit : <span class="label label-warning">{{ $sakitCount }}</span></p>
                                </div>
                                <div class="col-md-3">
                                    <p>Izin : <span class="label label-info">{{ $izinCount }}</span></p>
                                </div>
                                <div class="col-md-3">
                                    <p>Hari Kerja : <span
                                            class="label label-primary">{{ $actualWorkingDaysCount }}</span></p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layout>
