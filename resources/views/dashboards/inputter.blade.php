<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inputter Dashboard | CBAM Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f8f5;
            font-family: 'Segoe UI', sans-serif;
        }

        .sidebar {
            position: fixed;
            width: 250px;
            height: 100vh;
            background: #174b35;
            color: white;
            padding: 25px 18px;
        }

        .brand {
            font-size: 21px;
            font-weight: bold;
            margin-bottom: 45px;
        }

        .menu {
            display: block;
            padding: 13px 15px;
            color: #d7e9dd;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .menu.active, .menu:hover {
            background: #28734e;
            color: white;
        }

        .main {
            margin-left: 250px;
            padding: 35px;
        }

        .stat-card {
            border: none;
            border-radius: 13px;
            padding: 22px;
            box-shadow: 0 4px 15px rgba(0,0,0,.04);
        }

        .stat-icon {
            font-size: 27px;
            color: #238653;
        }

        .topbar {
            background: white;
            padding: 17px 25px;
            border-radius: 12px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="brand">
        <i class="bi bi-leaf-fill"></i> CBAM TRACKER
    </div>

    <small class="text-uppercase text-white-50">Main Menu</small>

    <a href="{{ route('inputter.dashboard') }}" class="menu active">
        <i class="bi bi-grid me-2"></i> Dashboard
    </a>

    <a href="#" class="menu">
        <i class="bi bi-database me-2"></i> Activity Data
    </a>

    <a href="#" class="menu">
        <i class="bi bi-box-seam me-2"></i> Product Data
    </a>

    <hr>

    <form action="{{ route('cbam.logout') }}" method="POST">
        @csrf
        <button class="menu w-100 border-0 text-start bg-transparent">
            <i class="bi bi-box-arrow-left me-2"></i> Logout
        </button>
    </form>
</aside>

<main class="main">

    <div class="topbar d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-1">Inputter Dashboard</h5>
            <small class="text-muted">Carbon Emission Data Management</small>
        </div>

        <div>
            <i class="bi bi-person-circle me-2"></i>
            {{ auth()->user()->name }}
        </div>
    </div>

    <h3 class="fw-bold">Welcome, {{ auth()->user()->name }}!</h3>
    <p class="text-muted">Manage your company's operational emission data.</p>

    <div class="row g-4 mt-2">

        <div class="col-md-4">
            <div class="card stat-card">
                <i class="bi bi-database stat-icon"></i>
                <h6 class="text-muted mt-3">Total Activity Data</h6>
                <h2 class="fw-bold">0</h2>
                <small class="text-muted">Recorded data</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card">
                <i class="bi bi-cloud-upload stat-icon"></i>
                <h6 class="text-muted mt-3">Pending Submission</h6>
                <h2 class="fw-bold">0</h2>
                <small class="text-muted">Data awaiting submission</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card">
                <i class="bi bi-check-circle stat-icon"></i>
                <h6 class="text-muted mt-3">Approved Data</h6>
                <h2 class="fw-bold">0</h2>
                <small class="text-muted">Verified records</small>
            </div>
        </div>

    </div>

</main>
</body>
</html>
