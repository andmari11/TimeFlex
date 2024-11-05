<p><strong>ID:</strong> {{ $schedule->id }}</p>
<p><strong>Company ID:</strong> {{ $schedule->company_id }}</p>
<p><strong>Estado:</strong> {{ $schedule->status }}</p>

@foreach($schedule->shifts as $shift)
    <p>{{$shift}}</p>
@endforeach
<p><strong>Schedule JSON</strong></p>

@if($schedule->scheduleJSON)
    <ul class="list-disc list-inside">
        @foreach($schedule->scheduleJSON as $user => $dates)
            <strong>{{ $user }}:</strong>
            <ul class="ml-4 list-decimal list-inside">
                @foreach($dates as $date)
                    <li>{{ $date }}</li>
                @endforeach
            </ul>
        @endforeach
    </ul>
@else
    <p>No hay horarios disponibles.</p>
@endif
