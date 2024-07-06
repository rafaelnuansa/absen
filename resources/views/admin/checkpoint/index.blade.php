<x-layout>
    <div class="container-fluid">

        <div class="row">
            <div class="col">

                <div class="tools mb-3 ">
                    <a href="{{ route('admin.checkpoint.create') }}" class="btn btn-primary "><i class="fa fa-plus"></i>
                        Tambah Baru</a>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><b>Checkpoints</b></h3>

                    </div>
                    <div class="card-body">
                        <div class="tools py-2 mb-3">
                            <form action="{{ route('admin.checkpoint.index') }}" method="GET" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" placeholder="Cari berdasarkan nama..." value="{{ request()->input('search') }}">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
                            </form>
                        </div>
                        
                        <div class="table-responsive table-card">
                            <table id="swdatatable" class="table ">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Lokasi</th>
                                        <th>Deskripsi</th>
                                        <th>QR Code</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($checkpoints as $checkpoint)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $checkpoint->code }}</td>
                                            <td>{{ $checkpoint->name }}</td>
                                            <td>{{ $checkpoint->building->name }}</td>
                                            <td>{{ $checkpoint->description }}</td>
                                            <td><a href="{{ asset($checkpoint->qrcode) }}" class="btn btn-primary btn-sm"> <i class="mdi mdi-qrcode"></i></a></td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.checkpoint.show', $checkpoint->id)}}" class="btn btn-sm btn-primary"><i class="ri-eye-line"></i></a>
                                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item"
                                                                href="{{ route('admin.checkpoint.edit', $checkpoint->id) }}"><i
                                                                    class="mdi mdi-pencil"></i> Edit</a></li>
                                                        <li>
                                                            <form
                                                                action="{{ route('admin.checkpoint.destroy', $checkpoint->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Are you sure you want to delete this checkpoint?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item"><i
                                                                        class="mdi mdi-trash-can"></i> Delete</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>


                                            </td>
                                        </tr>
                                    @empty
                                        <tr align="center">
                                            <td colspan="6">Tidak ada data yang tersedia.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="row mt-4">
                            {{ $checkpoints->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
