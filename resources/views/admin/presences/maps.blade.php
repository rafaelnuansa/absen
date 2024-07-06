<x-layout>
    <div class="container-fluid">
        <a href="{{ route('admin.presences.index')}}" class="btn btn-dark mb-4"><i class="mdi mdi-arrow-left"></i> Kembali</a>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        
                        <div id="map" style="height: 400px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src='https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.js'></script>
        <link href='https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.css' rel='stylesheet' />
        <script>
            mapboxgl.accessToken = 'pk.eyJ1IjoicmFmYWVsbnVhbnNhIiwiYSI6ImNsYjNsOXdmZzBmMmozdm9hY2tvdnFkdGIifQ.tFCXSNFPjjQz8ZqiJfQ9IA'; // Ganti dengan token Mapbox Anda

            var map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/streets-v11', // Ganti dengan gaya peta yang diinginkan
                center: [{{ $longitude }}, {{ $latitude }}], // Gunakan nilai latitude dan longitude dari controller
                zoom: 13 // Ganti dengan tingkat zoom yang diinginkan
            });

            // Tambahkan kontrol zoom in dan zoom out
            map.addControl(new mapboxgl.NavigationControl());

            // Tambahkan marker ke peta
            var marker = new mapboxgl.Marker()
                .setLngLat([{{ $longitude }}, {{ $latitude }}]) // Gunakan nilai latitude dan longitude dari controller
                .addTo(map);
        </script>
    @endpush
</x-layout>
