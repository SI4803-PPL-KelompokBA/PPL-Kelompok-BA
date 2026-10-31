<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESG Officer Dashboard | CBAM Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body style="background:#f4f8f5">

<div class="container py-5">
    <div class="card border-0 shadow-sm p-4">
        <h2 class="text-success">
            <i class="bi bi-graph-up-arrow"></i>
            ESG Officer Dashboard
        </h2>

        <h4 class="mt-4">
            Welcome, {{ auth()->user()->name }}!
        </h4>

        <p class="text-muted">
            Monitor carbon emissions, calculate emission values,
            and manage CBAM reports.
        </p>

        <form action="{{ route('cbam.logout') }}" method="POST">
            @csrf
            <button class="btn btn-success">Logout</button>
        </form>
    </div>
</div>

</body>
</html>