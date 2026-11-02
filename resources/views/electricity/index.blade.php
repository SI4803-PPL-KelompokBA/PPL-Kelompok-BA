<!DOCTYPE html>
<html>
<head>
    <title>Pencatatan Konsumsi Listrik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Pencatatan Konsumsi Listrik</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('electricity.store') }}" method="POST" class="mb-4">
        @csrf
        <div class="mb-3">
            <label>Nama Lokasi / Fasilitas</label>
            <input type="text" name="location_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Konsumsi kWh</label>
            <input type="number" step="0.01" name="kwh_consumed" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Tanggal Catat</label>
            <input type="date" name="record_date" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Data</button>
    </form>
</body>
</html>