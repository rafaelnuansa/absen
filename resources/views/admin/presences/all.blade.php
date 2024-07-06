<x-layout>
    <div>
        <div class="card">
            <div class="card-body">
                <h2 class="mb-3">Employee Attendance</h2>
                <form action="{{ route('admin.presences.all') }}" method="GET">
                    <div class="row mb-2">
                        <div class="col-lg-2 mb-2">
                            <div class="form-group">
                                <select class="form-control month" name="month" required="">
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}"
                                            {{ $month == $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2 mb-2">
                            <div class="form-group">
                                <select class="form-control year" name="year" required="">
                                    @php
                                        $currentYear = date('Y');
                                        $startYear = $currentYear - 5;
                                        $endYear = $currentYear + 5;
                                    @endphp
                                    @for ($y = $startYear; $y <= $endYear; $y++)
                                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2 mb-2">
                            <div class="form-group">
                                <select class="form-control" name="position_id">
                                    <option value="">Select Position</option>
                                    @foreach ($positions as $position)
                                        <option value="{{ $position->id }}"
                                            {{ request('position_id') == $position->id ? 'selected' : '' }}>
                                            {{ $position->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2 mb-2">
                            <div class="form-group">
                                <select class="form-control" name="building_id">
                                    <option value="">Select Location</option>
                                    @foreach ($buildings as $building)
                                        <option value="{{ $building->id }}"
                                            {{ request('building_id') == $building->id ? 'selected' : '' }}>
                                            {{ $building->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-dark btn-sortir">Show</button>
                            <a href="{{ route('admin.presences.all') }}" class="btn btn-secondary btn-sortir">Reset</a>
                            <!-- Export to Excel Dropdown -->
                            <div class="dropdown d-inline">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="exportDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Export to Excel
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                                    <li>
                                        <a href="{{ route('admin.presences.all', ['year' => $year, 'month' => $month, 'position_id' => request('position_id'), 'building_id' => request('building_id'), 'export' => 'excel']) }}"
                                            class="dropdown-item">Export to Excel</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <!-- Display applied filters -->
                            @if (request()->filled('month'))
                                <span class="badge bg-info">Month:
                                    {{ \Carbon\Carbon::create()->month($month)->format('F') }}</span>
                            @endif
                            @if (request()->filled('year'))
                                <span class="badge bg-info">Year: {{ $year }}</span>
                            @endif
                            @if (request()->filled('position_id'))
                                <span class="badge bg-info">Position:
                                    {{ $positions->where('id', request('position_id'))->first()->name }}</span>
                            @endif
                            @if (request()->filled('building_id'))
                                <span class="badge bg-info">Location:
                                    {{ $buildings->where('id', request('building_id'))->first()->name }}</span>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if ($isFilterApplied && $employees->count() > 0)
            <div class="card mt-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">

                            <thead>
                                <tr>
                                    <th scope="col" rowspan="2"
                                        style="text-align: center; vertical-align: middle;">No</th>
                                    <th scope="col" rowspan="2"
                                        style="text-align: center; vertical-align: middle;">Name</th>
                                    <th scope="col" rowspan="2"
                                        style="text-align: center; vertical-align: middle;">ID</th>
                                    @for ($day = 1; $day <= $daysInMonth; $day++)
                                        <th scope="col"
                                            class="text-center {{ \Carbon\Carbon::createFromDate($year, $month, $day)->isSunday() ? 'bg-danger text-white' : '' }}">
                                            {{ \Carbon\Carbon::createFromDate($year, $month, $day)->format('D') }}
                                        </th>
                                    @endfor
                                    <th scope="col" rowspan="2"
                                        style="text-align: center; vertical-align: middle;">Total</th>
                                </tr>
                                <tr>
                                    @for ($day = 1; $day <= $daysInMonth; $day++)
                                        <th scope="col"
                                            class="text-center {{ \Carbon\Carbon::createFromDate($year, $month, $day)->isSunday() ? 'bg-danger text-white' : '' }}">
                                            {{ \Carbon\Carbon::createFromDate($year, $month, $day)->format('d') }}
                                        </th>
                                    @endfor
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($employees as $index => $employee)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $employee->name }}</td>
                                        <td>{{ $employee->code }}</td>
                                        @php
                                            $totalAttendance = 0;
                                        @endphp
                                        @for ($day = 1; $day <= $daysInMonth; $day++)
                                            @php
                                                $date = \Carbon\Carbon::create($year, $month, $day);
                                                $attendance = $employee->getAttendance($date);
                                                $status = $attendance
                                                    ? strtoupper(substr($attendance->status, 0, 1))
                                                    : '-';
                                                $statusClass = '';

                                                // Tentukan kelas CSS berdasarkan status
                                                switch ($status) {
                                                    case 'P':
                                                        $statusClass = 'bg-success text-white';
                                                        break;
                                                    case 'O':
                                                        $statusClass = 'bg-primary text-white';
                                                        break;
                                                    case 'S':
                                                        $statusClass = 'bg-warning text-dark';
                                                        break;
                                                    case '-':
                                                        $statusClass = '';
                                                        break;
                                                }

                                                // Hitung total kehadiran
                                                if ($status === 'P') {
                                                    $totalAttendance++;
                                                }
                                            @endphp
                                            <td class="text-center {{ $statusClass }}">
                                                @if ($attendance)
                                                    <a class="fw-bold {{ $statusClass }}"
                                                        href="{{ route('admin.presences.detail', ['employeeId' => $employee->id, 'presenceId' => $attendance->id]) }}">
                                                        {{ $attendance->shift == 1 ? 'P1' : ($attendance->shift == 2 ? 'P2' : '') }}
                                                        <br>
                                                        <small>{{ $attendance->time_in ?? '-' }}</small>
                                                        <br>
                                                        <small>{{ $attendance->time_out ?? '-' }}</small>
                                                    </a>
                                                @else
                                                    {{ $status }}
                                                @endif
                                            </td>
                                        @endfor
                                        <td class="text-center">{{ $totalAttendance }}</td>
                                    </tr>
                                @endforeach



                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        @endif
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Simbol Presensi Kehadiran</h5>
                <ul>
                    <li><strong>P</strong> - Jika hadir</li>
                    <li><strong>O</strong> - Izin</li>
                    <li><strong>S</strong> - Sakit</li>
                    <li><strong>A</strong> - Alfa</li>
                </ul>
            </div>
        </div>
    </div>
</x-layout>
