<x-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Patrol Detail</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Employee:</strong> {{ $patrol->employee->name }}</p>
                        <p><strong>Checkpoint:</strong> {{ $patrol->checkpoint->name }}</p>
                        <p><strong>Date:</strong> {{ $patrol->date }}</p>
                        <p><strong>Time:</strong> {{ $patrol->time }}</p>

                        <!-- Tampilkan foto-foto patroli -->
                        @if ($patrol->photos->isNotEmpty())
                            <h5>Photos:</h5>
                            <div class="row">
                                @foreach ($patrol->photos as $photo)
                                    <div class="col-md-4 mb-3">
                                        <div class="card rounded shadow overflow-hidden" >
                                            <img src="{{ asset($photo->file_path) }}" alt="Patrol Photo"
                                                class="card-img-top">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p>No photos available for this patrol.</p>
                        @endif
                        <a href="{{ route('admin.patrols.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
