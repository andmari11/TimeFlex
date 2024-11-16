<p><strong>ID:</strong> {{ $schedule->id }}</p>
<p><strong>Company ID:</strong> {{ $schedule->company_id }}</p>
<p><strong>Estado:</strong> {{ $schedule->status }}</p>

@foreach($schedule->shifts as $shift)
    <div class="ps-4">
        <h4>Turno ID: {{$shift->id}}</h4>
        <h5> ({{ \Carbon\Carbon::parse($shift->start)->format('d-m-Y H:i') }}
            a
            {{ \Carbon\Carbon::parse($shift->end)->format('d-m-Y H:i') }})
        </h5>
        <h5>Usuarios asignados:</h5>
        <ul class="ps-4">
            @foreach($shift->users as $user)
                <li>{{ $user->name }} ({{$user->email}})</li>
            @endforeach
        </ul>
    </div>
    <hr>
@endforeach

