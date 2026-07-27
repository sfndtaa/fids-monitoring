<!DOCTYPE html>
<html>
<head>
    <title>FIDS Monitoring</title>

    <style>

        body{
            font-family:Arial;
            background:#f5f7fb;
            margin:30px;
        }

        h1{
            margin-bottom:30px;
        }

        .cards{
            display:flex;
            gap:20px;
            margin-bottom:30px;
        }

        .card{
            background:white;
            padding:20px;
            border-radius:10px;
            width:180px;
            box-shadow:0 2px 8px rgba(0,0,0,.08);
        }

        table{
            width:100%;
            border-collapse:collapse;
            background:white;
        }

        th,td{
            padding:12px;
            border-bottom:1px solid #ddd;
        }

        th{
            background:#0d6efd;
            color:white;
        }

    </style>

</head>
<body>

<h1>Airport FIDS Monitoring</h1>

<div class="cards">

<div class="card">
<h3>Total Device</h3>
<h1>{{ $total }}</h1>
</div>

<div class="card">
<h3>Online</h3>
<h1>{{ $online }}</h1>
</div>

<div class="card">
<h3>Offline</h3>
<h1>{{ $offline }}</h1>
</div>

<div class="card">
<h3>Warning</h3>
<h1>{{ $warning }}</h1>
</div>

<div class="card">
<h3>Maintenance</h3>
<h1>{{ $maintenance }}</h1>
</div>

</div>

<table>

<tr>

<th>Device</th>
<th>Location</th>
<th>IP Address</th>
<th>Status</th>

</tr>

@foreach($devices as $device)

<tr>

<td>{{ $device->device_name }}</td>
<td>{{ $device->location }}</td>
<td>{{ $device->ip_address }}</td>
<td>{{ strtoupper($device->status) }}</td>

</tr>

@endforeach

</table>

</body>
</html>