@push('scripts')

@endpush
<x-employee.layout>
    <div class="content-inner pt-0">
        <div class="container fb">

            <div class="card">

            </div>

            <!-- Dashboard Area -->
            <div class="dashboard-area mt-5">

                <!-- Recent Jobs -->
                <div class="title-bar">
                    <h5 class="dz-title">Permohonan Cuti</h5>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                        data-bs-target="#bottomSheetModal">
                        Buat Baru
                    </button>

                </div>
                <div class="list item-list recent-jobs-list">
                    <ul>
                        @foreach ($leaves as $leave)
                            <li class="d-flex justify-content-between align-items-center">
                                <div class="item-content">
                                    <div class="item-inner">
                                        <div class="d-flex align-items-center">
                                            <div class="fs-11">
                                                @if ($leave->status == 'approved')
                                                    <i class="ri-check-fill text-success"></i> Disetujui.
                                                @elseif ($leave->status == 'rejected')
                                                    <i class="ri-close-fill text-danger"></i> Ditolak.
                                                @else
                                                    <i class="ri-time-fill text-warning"></i> Menunggu persetujuan.
                                                @endif
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="fs-11">
                                                <i class="ri-calendar-event-fill"></i>
                                                {{ Carbon\Carbon::parse($leave->start_date)->format('d M Y') }} -
                                                {{ Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="fs-11">
                                                <i class="ri-calendar-event-fill"></i> Mulai Kerja
                                                {{ Carbon\Carbon::parse($leave->date_work)->format('d M Y') }}
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center">
                                            <div class="fs-11">
                                                <i class="ri-information-fill"></i> {{ $leave->reason }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if ($leave->status == 'pending')
                                    <div class="sortable-handler">
                                        <x-employee.modal-edit-leave :leave="$leave" />

                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>




            </div>
        </div>
    </div>


    <!-- Modal untuk edit cuti -->
    <div class="modal fade" id="editLeaveModal" tabindex="-1" aria-labelledby="editLeaveModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="editLeaveModalLabel">Edit Cuti</h5>
                    <button type="button" class="btn btn-icon btn-primary text-white" data-bs-dismiss="modal"
                        aria-label="Close"><i class="ri-close-circle-fill"></i></button>
                </div>
                <div class="modal-body">
                    <!-- Form edit cuti -->
                    <form action="{{ route('employee.leave.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="leave_id" id="editLeaveId">

                        <div class="mb-3">
                            <label for="edit_start_date" class="form-label">Tanggal Mulai Cuti</label>
                            <input type="date" class="form-control" id="edit_start_date" name="start_date"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_end_date" class="form-label">Tanggal Selesai Cuti</label>
                            <input type="date" class="form-control" id="edit_end_date" name="end_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_date_work" class="form-label">Tanggal Mulai Bekerja Kembali</label>
                            <input type="date" class="form-control" id="edit_date_work" name="date_work"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_total" class="form-label">Total Hari Cuti</label>
                            <input type="number" class="form-control" id="edit_total" name="total" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_reason" class="form-label">Alasan Cuti</label>
                            <textarea class="form-control" id="edit_reason" name="reason" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-sm w-100 btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</x-employee.layout>
