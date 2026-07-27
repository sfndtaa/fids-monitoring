@extends('layouts.app')


@section('content')

<div class="container">

<h1>Devices</h1>


<form method="GET">

<input 
type="text" 
name="search"
placeholder="Search device..."
>


<button>
Search
</button>

</form>


<table border="1">

<tr>
<th>Name</th>
<th>Location</th>
<th>Status</th>
</tr>


@foreach($devices as $device)

<tr>

<td>
{{ $device->name }}
</td>


<td>
{{ $device->location }}
</td>


<td>

@if($device->status == 'online')

<span style="color:green">
🟢 Online
</span>


@elseif($device->status == 'warning')

<span style="color:orange">
🟡 Warning
</span>


@else

<span style="color:red">
🔴 Offline
</span>

@endif


</td>


</tr>

@endforeach


</table>


{{ $devices->links() }}


</div>


@endsection