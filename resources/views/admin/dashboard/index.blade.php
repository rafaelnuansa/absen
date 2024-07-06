<x-layout>
    <div class="container-fluid">

        <div class="row">
            <div class="col-xxl-12">
                <div class="d-flex flex-column h-100">
                    <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p class="fw-medium text-muted mb-0">Pengguna Aktif</p>
                                            <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{ $employeesActiveCount }}">{{ $employeesActiveCount}}</span></h2>
                                        </div>
                                        <div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users text-info"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div> <!-- end card-->
                        </div> <!-- end col-->

                        <div class="col-md-6 col-lg-3">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p class="fw-medium text-muted mb-0">Pending Request</p>
                                            <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{ $pendingLeaveCount }}">{{ $pendingLeaveCount }}</span></h2>
                                        </div>
                                        <div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock text-info"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div> <!-- end card-->
                        </div> <!-- end col-->


                        <div class="col-md-6 col-lg-3">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p class="fw-medium text-muted mb-0">Lokasi</p>
                                            <h2 class="mt-4 ff-secondary fw-semibold">
                                                <span class="counter-value" data-target="{{ $locationsCount }}">{{ $locationsCount }}</span>
                                            </h2>
                                        </div>
                                        <div>
                                        
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                    <i class="mdi mdi-map-marker text-info"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div> <!-- end card-->
                        </div> <!-- end col-->

                      
                        <div class="col-md-6 col-lg-3">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p class="fw-medium text-muted mb-0">Jabatan</p>
                                            <h2 class="mt-4 ff-secondary fw-semibold">
                                                <span class="counter-value" data-target="{{ $positionsCount }}">{{ $positionsCount }}</span>
                                            </h2>
                                        </div>
                                        <div>
                                        
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                    <i class="mdi mdi-map-marker text-info"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div> <!-- end card-->
                        </div> <!-- end col-->

                    </div> <!-- end row-->

                </div>
            </div> <!-- end col-->

         
        </div>

<!-- Daftar Pengajuan Cuti Terbaru -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card card-animate">
            <!-- Card Header -->
            <div class="card-header">
                <h4 class="card-title">New Request</h4>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nama Pegawai</th>
                                <th>Status</th>
                                <th>Type</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pendingLeaves as $leave)
                                <tr>
                                    <td>{{ $leave->employee->name }}</td>
                                    <td>{{ $leave->status }}</td>
                                    <td>{{ $leave->type }}</td>
                                    <td>
                                        <a href="{{ route('admin.leaves.show', $leave->id) }}" class="btn btn-sm btn-primary">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">Tidak ada pengajuan cuti pending.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- end card-->
    </div> <!-- end col-->
</div> <!-- end row-->

     
    </div>
</x-layout>
