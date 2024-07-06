<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Patrols Report - {{ \Carbon\Carbon::create()->month($month)->format('F') }} {{ $year }}</title>
    <style>
        /* Styling untuk PDF bisa ditambahkan di sini */
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            /* Margin bawah tambahan untuk memberi ruang antara tabel dan teks berikutnya */
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 10px;
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
        }

        /* Atur lebar kolom hari di sini */
        th.day-header {
            width: 100px;
            /* Lebar untuk kolom tanggal */
            text-align: center;
        }

        td.patrol-info {
            vertical-align: top;
        }

        .photo-cell {
            text-align: center;
        }

        .photo {
            max-width: 120px;
            max-height: 120px;
            margin: 20px auto;
        }
    </style>
</head>

<body>
    <h2>Patrols Report - {{ \Carbon\Carbon::create()->month($month)->format('F') }} {{ $year }}</h2>
  <table>
        @for ($day = 1; $day <= \Carbon\Carbon::create($year, $month, 1)->daysInMonth; $day++)
            @php
                $patrolsByDay = $patrols->filter(function ($patrol) use ($day) {
                    return \Carbon\Carbon::parse($patrol->date)->day == $day;
                });
            @endphp

            @if ($patrolsByDay->isEmpty())
                <tbody>
                    <tr>
                        <th class="day-header" colspan="5">Tanggal {{ $day }}</th>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align: center;">Tidak ada data patroli pada tanggal ini.</td>
                    </tr>
                </tbody>
            @else
              <tbody>
                    <tr>
                        <th class="day-header" colspan="5">Tanggal {{ $day }}</th>
                    </tr>
                    <tr>
                        <th>No</th>
                        <th>Patrols</th>
                        <th>Time</th>
                        <th>Photos</th>
                        <th>Information</th>
                    </tr>
                    @foreach ($patrolsByDay as $index => $patrol)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td> <b>{{ $patrol->employee->name }}</b>, <br>
                                Lokasi : {{ $patrol->checkpoint->building->name }}, {{ $patrol->checkpoint->name }} <br>
                                Longitude: {{ $patrol->longitude }},
                                Latitude: {{ $patrol->latitude }} <br>
                                Status: {{ $patrol->status }}
                            </td>
                            <td>{{ $patrol->time }}</td>
                            <td class="photo-cell">
                                @foreach ($patrol->photos as $photo)
                                    @php
                                        $imagePath = public_path($photo->file_path);
                                        $imageData = file_exists($imagePath)
                                            ? base64_encode(file_get_contents($imagePath))
                                            : null;
                                        $imageSrc = $imageData
                                            ? 'data:image/svg+xml;base64,' . $imageData
                                            : asset('broke_image.png');
                                    @endphp
                                    <img class="photo" src="{{ $imageSrc }}" alt="Photo">
                                @endforeach
                            </td>
                            <td>{{ $patrol->information }}</td>
                        </tr>
                    @endforeach
                </tbody>
            @endif
        @endfor
    </table>


</body>

</html>
