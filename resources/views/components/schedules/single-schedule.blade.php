<p><strong>ID:</strong> {{ $schedule->id }}</p>
<p><strong>Company ID:</strong> {{ $schedule->company_id }}</p>
<p><strong>Estado:</strong> {{ $schedule->status }}</p>

@foreach($schedule->shifts as $shift)
    <div class="shift-card">
        <h4>Turno ID: {{$shift->id}}</h4>
        <h5>Usuarios asignados:</h5>
        <ul>
            @foreach($shift->users as $user)
                <li>{{ $user->name }} ({{$user->email}})</li>
            @endforeach
        </ul>
    </div>
    <hr>
@endforeach

