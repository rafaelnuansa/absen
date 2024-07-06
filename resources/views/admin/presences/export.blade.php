@foreach ($absenByDate as $date => $absens)
    @php
        $carbonDate = \Carbon\Carbon::parse($date);
    @endphp
    @foreach ($absens as $absenData)
        @php
            $absen = $absenData['absensi'];
            $lateMinutes = $absenData['lateMinutes'];
            $earlyMinutes = $absenData['earlyMinutes'];
        @endphp
        {{ $carbonDate->isoFormat('dddd, D MMMM YYYY') }},
        {{ $absen->time_in }},
        {{ $lateMinutes }} Menit,
        {{ $absen->time_out ?? '-' }},
        {{ $earlyMinutes }} Menit,
        {{ $absen->status }},
        {{ $absen->latitude_longitude_in ?? '#' }},
        {{ $absen->latitude_longtitude_out }}
    @endforeach
@endforeach
