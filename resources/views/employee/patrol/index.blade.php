<x-employee.layout>
    <div class="content-inner pt-0">
        <div class="container fb">
            <div class="card">
                <div class="card-body">
                    <h2>Patroli</h2>
                    <div class="row mt-4">
                        <div class="col-6 d-flex justify-content-center">
                            <button type="button" class="btn btn-danger sos-button rounded-circle">SOS</button>
                        </div>
                        <div class="col-6 d-flex justify-content-center">
                            <a href="{{ route('employee.presence') }}"
                                class="btn btn-primary abs-button rounded-circle"><i class="ri-time-line"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="title-bar">
                <h5 class="dz-title">Lokasi Patroli</h5>
            </div>
            <div class="list item-list recent-jobs-list">
                <ul>
                    @forelse($checkpoints as $checkpoint)
                        <li>
                            <div class="item-content">
                                <a href="#" class="item-media"><i class="ri-shield-check-fill fs-30"></i></a>
                                <div class="item-inner">
                                    <div class="item-title-row">
                                        <div class="item-subtitle">{{ $checkpoint->code }}</div>
                                        <h6 class="item-title">{{ $checkpoint->location }}</h6>
                                    </div>
                                    @if ($checkpoint->patrol && $checkpoint->patrol->status === 'completed')
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="ri-shield-check-fill text-success"></i>
                                            <div class="item-price">Patroli Selesai</div>
                                        </div>
                                    @elseif($checkpoint->patrol && $checkpoint->patrol->status === 'pending')
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="ri-shield-fill text-warning"></i>
                                            <div class="item-price">Patroli Belum Selesai</div>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="ri-shield-fill text-muted"></i>
                                            <div class="item-price">Belum ada patroli</div>
                                        </div>
                                    @endif
                                    <a wire:navigate href="{{ route('employee.patrol.show', $checkpoint->id) }}"
                                        class="btn btn-sm btn-primary"> <i class="ri-more-line me-2"></i>Detail </a>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li>Tidak ada checkpoint yang harus dipatroli.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-employee.layout>
