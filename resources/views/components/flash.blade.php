@if (session('status'))
    <div class="rounded bg-green-50 p-3 text-green-700 text-sm">{{ session('status') }}</div>
@endif
@if ($errors->has('hapus'))
    <div class="rounded bg-red-50 p-3 text-red-700 text-sm">{{ $errors->first('hapus') }}</div>
@endif