<x-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="card card-animate">
                    <div class="card-header">
                        <h3 class="card-title">Requests</h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Kode Pegawai:</th>
                                    <td>{{ $leave->employee->code }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Pegawai:</th>
                                    <td>{{ $leave->employee->name }}</td>
                                </tr>

                                <tr>
                                    <th>Shift & Jam Kerja:</th>
                                    <td>{{ $leave->employee->shift->name }}, {{ $leave->employee->shift->time_in }} - {{ $leave->employee->shift->time_out }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Mulai:</th>
                                    <td>{{ $leave->start_date }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Berakhir:</th>
                                    <td>{{ $leave->end_date }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Kerja :</th>
                                    <td>{{ $leave->date_work }}</td>
                                </tr>
                                <tr>
                                    <th>Total Hari :</th>
                                    <td>{{ $leave->total }}</td>
                                </tr>
                                <tr>
                                    <th>Alasan/Dikarenakan :</th>
                                    <td>{{ $leave->reason }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge {{ $leave->status === 'pending' ? 'bg-secondary' : ($leave->status === 'approved' ? 'bg-success' : 'bg-danger') }}">
                                            {{ $leave->status }}
                                        </span>
                                    </td>
                                </tr>


                                <tr>
                                    <th>Bukti Penunjang Izin:</th>
                                    <td>
                                        @if ($leave->image)
                                            
                                      <img src="{{ asset($leave->image )}}" alt="{{ asset($leave->image )}}" class="img-thumbnail" style="max-height: 100px">
                                        @else
                                            Tidak ada
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        @if ($leave->status === 'pending')
                        <div class="form-group">
                            <form action="{{ route('admin.leaves.approve', $leave->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary mb-3">Setujui</button>
                            </form>
                            <form action="{{ route('admin.leaves.decline', $leave->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger ml-2">Tolak</button>
                            </form>
                        </div>
                        @endif
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{ route('admin.leaves.index') }}" class="btn btn-primary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
