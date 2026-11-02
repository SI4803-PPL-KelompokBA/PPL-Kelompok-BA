<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBAM Tracker - Upload Bukti Operasional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
    <div class="container bg-white p-4 rounded shadow-sm">
        <h2 class="mb-4 text-primary">Input Data Transportasi Operasional</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('transports.store') }}" method="POST" enctype="multipart/form-data" class="mb-5">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label font-weight-bold">Faktor Emisi / Item</label>
                    <select name="emission_factor_id" class="form-select" required>
                        <option value="">-- Pilih Jenis Item --</option>
                        @foreach($emissionFactors as $factor)
                            <option value="{{ $factor->id }}">
                                {{ $factor->nama_item }} ({{ $factor->nilai_emisi }} {{ $factor->satuan }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label font-weight-bold">Jarak (KM)</label>
                    <input type="number" step="0.01" name="jarak_km" class="form-control" placeholder="Contoh: 120.5" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label font-weight-bold">Muatan (Ton)</label>
                    <input type="number" step="0.01" name="muatan_ton" class="form-control" placeholder="Contoh: 15" required>
                </div>

                <div class="col-md-12">
                    <label class="form-label font-weight-bold">Unggah Dokumen Bukti (PDF / JPG / PNG, Max 2MB)</label>
                    <input type="file" name="bukti_dokumen" class="form-control" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Simpan Data & Dokumen</button>
        </form>

        <hr>

        <h4 class="mt-4 mb-3">Daftar Catatan Transportasi</h4>
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Item Emisi</th>
                        <th>Jarak (KM)</th>
                        <th>Muatan (Ton)</th>
                        <th>Dokumen Bukti</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transports as $transport)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $transport->emissionFactor->nama_item ?? '-' }}</td>
                            <td>{{ $transport->jarak_km }}</td>
                            <td>{{ $transport->muatan_ton }}</td>
                            <td>
                                @if($transport->bukti_dokumen)
                                    <a href="{{ asset('storage/' . $transport->bukti_dokumen) }}" target="_blank" class="btn btn-sm btn-info text-white">
                                        Lihat Dokumen
                                    </a>
                                @else
                                    <span class="text-muted">Tidak Ada Dokumen</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('transports.destroy', $transport->id) }}" method="POST" onsubmit="return confirm('Apakah yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data transportasi yang tersimpan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>