<!DOCTYPE html>
<html>
<head>
    <title>Import Data Device FIDS</title>
</head>
<body>

    <h2>Import Data Device FIDS</h2>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="file" name="file" required>

        <br><br>

        <button type="submit">Import Excel</button>
    </form>

</body>
</html>