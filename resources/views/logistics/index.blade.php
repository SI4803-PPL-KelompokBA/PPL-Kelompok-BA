<!DOCTYPE html>
<html>
<head>
    <title>Logistik Ekspor Pertanian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Logistik Ekspor Pertanian</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('logistics.store') }}" method="POST" class="mb-4">
        @csrf
        <div class="mb-3">
            <label>Fasilitas Pengolahan</label>
            <input type="text" name="processing_facility" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Pelabuhan Asal</label>
            <input type="text" name="origin_port" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Pelabuhan Tujuan</label>
            <input type="text" name="destination_port" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Moda Transportasi</label>
            <input type="text" name="transport_mode" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Jarak (KM)</label>
            <input type="number" step="0.01" name="distance_km" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Berat Kargo (Ton)</label>
            <input type="number" step="0.01" name="cargo_weight_ton" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Simpan Data Logistik</button>
    </form>
</body>
</html>