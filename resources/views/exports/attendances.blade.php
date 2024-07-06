<table class="font-family:Calibri; font-size:14; border:1px solid #ddd">
    <thead>
        <tr>
            <th colspan="{{ $daysInMonth + 4 }}"><b>Employees Attendaces</b></th>
        </tr>
        <tr>
            <th>Month:</th>
            <th>{{ \Carbon\Carbon::create()->month($month)->format('F') }}</th>
            <th>Year:</th>
            <th>{{ $year }}</th>
        </tr>
        @if (request('position_id'))
            <tr>
                <th>Position:</th>
                <th>{{ $positions->where('id', request('position_id'))->first()->name }}</th>
            </tr>
        @endif
        @if (request('building_id'))
            <tr>
                <th>Department:</th>
                <th>{{ $buildings->where('id', request('building_id'))->first()->name }}</th>
            </tr>
        @endif
        <tr>
            <th scope="col" rowspan="2" style="text-align: center; vertical-align: middle;">No
            </th>
            <th scope="col" rowspan="2" style="width:200px;text-align: center; vertical-align: middle;">Name
            </th>
            <th scope="col" rowspan="2" style="width:200px;text-align: center; vertical-align: middle;">
                Employee ID</th>
            <th scope="col" rowspan="2" style="width:200px;text-align: center; vertical-align: middle;">
                Position, Departement</th>
            @for ($day = 1; $day <= $daysInMonth; $day++)
                <th scope="col" style="text-align:center"
                    class="{{ Carbon\Carbon::createFromDate($year, $month, $day)->isSunday() ? 'text-danger' : '' }}">
                    {{ Carbon\Carbon::createFromDate($year, $month, $day)->format('D') }}
                </th>
            @endfor
        </tr>
        <tr>
            @for ($day = 1; $day <= $daysInMonth; $day++)
                <th scope="col" style="text-align:center"
                    class="{{ Carbon\Carbon::createFromDate($year, $month, $day)->isSunday() ? 'text-danger' : '' }}">
                    {{ Carbon\Carbon::createFromDate($year, $month, $day)->format('d') }}
                </th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @foreach ($attendances as $index => $attendance)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><b>{{ $attendance['employee']->name }}</b></td>
                <td>{{ $attendance['employee']->code }}</td>
                <td>{{ $attendance['employee']->position->name }}, {{ $attendance['employee']->building->name }}</td>
                @for ($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $status = $attendance['days'][$day] ?? 'A';
                        $backgroundColor = '';
                        $color = '#ffffff'; // Warna teks default

                        // Tentukan warna latar belakang dan warna teks berdasarkan status
                        switch ($status) {
                            case 'P':
                                $backgroundColor = '#28a745'; // Hijau untuk Present
                                break;
                            case 'O':
                                $backgroundColor = '#ffc107'; // Kuning untuk On Leave
                                break;
                            case 'S':
                                $backgroundColor = '#17a2b8'; // Biru untuk Sick
                                break;
                            case 'A':
                                $backgroundColor = '#dc3545'; // Merah untuk Absent
                                break;
                        }
                    @endphp
                    <td
                        style="text-align: center; background-color: {{ $backgroundColor }}; color: {{ $color }}">
                        {{ $status }}</td>
                @endfor

            </tr>
        @endforeach
    </tbody>
</table>
