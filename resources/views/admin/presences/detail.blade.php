<x-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">

                        <h5 class="card-title">Absensi
                            {{ \Carbon\Carbon::createFromFormat('Y-m-d', $presence->date)->isoFormat('D MMMM YYYY') }}
                        </h5>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="table table-hovered">
                                <thead class="fw-bold">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Jabatan</th>
                                        <th>Lokasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>

                                        <td>{{ $presence->employee->code }}</td>
                                        <td>{{ $presence->employee->name }}</td>
                                        <td>{{ $presence->employee->email }}</td>
                                        <td>{{ $presence->employee->position->name }}</td>
                                        <td>{{ $presence->employee->building->name }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-hover dataTable no-footer" id="presences" role="grid">
                                <thead class="fw-bold">
                                    <tr>
                                        <td>Foto Masuk</td>
                                        <td>Jam Masuk</td>
                                        <td>Terlambat</td>
                                        <td>Foto Pulang</td>
                                        <td>Jam Pulang</td>
                                        <td>Pulang Cepat</td>
                                        <td>Status</td>
                                    </tr>
                                </thead>
                                <tbody>
                                        <tr>
                                        <td>

                                            @if ($presence->picture_in)
                                                
                                            <img class="img-thumbnail" style="width: 50px"
                                            src="{{ asset($presence->picture_in) }}"></img>
                                            @endif

                                        </td>
                                        <td>{{ $presence->time_in }}</td>
                                        <td>{{ $lateMinutes ?? '-'}} Menit</td>
                                        <td>
                                            @if ($presence->picture_out)
                                                
                                            <img class="img-thumbnail" style="width: 50px"
                                            src="{{ asset($presence->picture_out) }}"></img>
                                            @endif
                                        </td>
                                        <td>{{ $presence->time_out ?? '-' }}</td>
                                        <td>{{ $earlyMinutes ?? '-' }} Menit</td>
                                        <td>
                                            <span class="badge bg-dark">{{ $presence->status }}</span>
                                        </td>
                                       
                                        </tr>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Lokasi Masuk</h5>
                        <div id="mapIn" style="height: 400px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Lokasi Keluar</h5>
                        <div id="mapOut" style="height: 400px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src='https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.js'></script>
        <link href='https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.css' rel='stylesheet' />
        <script>
            mapboxgl.accessToken =
                'pk.eyJ1IjoicmFmYWVsbnVhbnNhIiwiYSI6ImNsYjNsOXdmZzBmMmozdm9hY2tvdnFkdGIifQ.tFCXSNFPjjQz8ZqiJfQ9IA'; // Ganti dengan token Mapbox Anda

            // Buat peta untuk lokasi masuk
            var mapIn = new mapboxgl.Map({
                container: 'mapIn',
                style: 'mapbox://styles/mapbox/streets-v11',
                center: [{{ $longitude_in }}, {{ $latitude_in }}],
                zoom: 13
            });

            // Tambahkan kontrol zoom in dan zoom out untuk peta lokasi masuk
            mapIn.addControl(new mapboxgl.NavigationControl());

            // Tambahkan marker untuk lokasi masuk
            var markerIn = new mapboxgl.Marker()
                .setLngLat([{{ $longitude_in }}, {{ $latitude_in }}])
                .addTo(mapIn);

            // Buat peta untuk lokasi keluar
            var mapOut = new mapboxgl.Map({
                container: 'mapOut',
                style: 'mapbox://styles/mapbox/streets-v11',
                center: [{{ $longitude_out }}, {{ $latitude_out }}],
                zoom: 13
            });

            // Tambahkan kontrol zoom in dan zoom out untuk peta lokasi keluar
            mapOut.addControl(new mapboxgl.NavigationControl());

            // Tambahkan marker untuk lokasi keluar
            var markerOut = new mapboxgl.Marker()
                .setLngLat([{{ $longitude_out }}, {{ $latitude_out }}])
                .addTo(mapOut);
        </script>
    @endpush

</x-layout>
