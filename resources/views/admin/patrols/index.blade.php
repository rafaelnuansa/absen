<x-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Patrols</h3>
                    </div>
                    <div class="card-body">
                        <!-- Filter form -->
                        <form action="{{ route('admin.patrols.index') }}" method="GET" class="mb-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="building_id">Filter by Location:</label>
                                        <select name="building_id" id="building_id" class="form-control">
                                            <option value="">All Location</option>
                                            @foreach ($buildings as $building)
                                                <option value="{{ $building->id }}"
                                                    {{ request('building_id') == $building->id ? 'selected' : '' }}>
                                                    {{ $building->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="month">Month:</label>
                                        <select name="month" id="month" class="form-control">
                                            @php
                                                $currentMonth = \Carbon\Carbon::now()->month;
                                                $selectedMonth = request('month') ?? $currentMonth;
                                            @endphp
                                            @for ($m = 1; $m <= 12; $m++)
                                                <option value="{{ $m }}"
                                                    {{ $selectedMonth == $m ? 'selected' : '' }}>
                                                    {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="year">Year:</label>
                                        <select name="year" id="year" class="form-control">
                                            @php
                                                $currentYear = date('Y');
                                                $startYear = $currentYear - 5;
                                                $endYear = $currentYear + 5;
                                                $selectedYear = request('year') ?? $currentYear;
                                            @endphp
                                            @for ($y = $startYear; $y <= $endYear; $y++)
                                                <option value="{{ $y }}"
                                                    {{ $selectedYear == $y ? 'selected' : '' }}>
                                                    {{ $y }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
{{-- 
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="shift_id">Shift:</label>
                                        <select name="shift_id" id="shift_id" class="form-control">
                                            <option value="">All Shifts</option>
                                            <option value="1" {{ request('shift_id') == 1 ? 'selected' : '' }}>Shift 1</option>
                                            <option value="2" {{ request('shift_id') == 2 ? 'selected' : '' }}>Shift 2</option>
                                        </select>
                                    </div>
                                </div> --}}
                                
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label><br>
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Informasi filter yang diterapkan -->
                        @if (request()->filled('month') ||
                                request()->filled('year') ||
                                request()->filled('building_id'))
                            <div class="row mb-3">
                                <div class="col">
                                    @if (request()->filled('building_id'))
                                        <span class="badge bg-primary">Locations:
                                            {{ $building->where('id', request('building_id'))->first()->name }}</span>
                                    @endif
                                    @if (request()->filled('shift_id'))
                                        <span class="badge bg-primary">Shift:
                                            {{ $shifts->where('id', request('shift_id'))->first()->name }}</span>
                                    @endif
                                    @if (request()->filled('month'))
                                        <span class="badge bg-primary">Month:
                                            {{ \Carbon\Carbon::create()->month(request('month'))->format('F') }}</span>
                                    @endif
                                    @if (request()->filled('year'))
                                        <span class="badge bg-primary">Year: {{ request('year') }}</span>
                                    @endif
                                    <a href="{{ route('admin.patrols.index') }}" class="badge bg-secondary">Clear
                                        Filters</a>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <a href="{{ route('admin.patrols.exportPdf', ['month' => $month, 'year' => $year, 'building_id' => $buildingId]) }}" class="btn btn-primary">Export to PDF</a>

                                </div>
                            </div>

                            <!-- Table of patrols -->
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Employee</th>
                                            <th>Checkpoint at</th>
                                            @for ($day = 1; $day <= $daysInMonth; $day++)
                                                <th class="text-center">{{ $day }}</th>
                                            @endfor
                                        </tr>
                                    </thead>
                                    <tbody>
                                     @foreach ($patrols as $patrol)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $patrol->employee->name }}</td>
            <td>{{ $patrol->checkpoint->name }}</td>
            @for ($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
                @endphp
                <td class="text-center">
                    @if ($patrol->date == $date)
                        <a class="btn btn-primary btn-sm" href="{{ route('admin.patrols.show', $patrol->id) }}">{{ $patrol->time }}</a>
                    @endif
                </td>
            @endfor
        </tr>
    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
