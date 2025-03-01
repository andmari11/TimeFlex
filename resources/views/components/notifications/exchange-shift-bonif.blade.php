<li class="flex flex-col items-center px-4 py-4 bg-blue-50 rounded-lg">
    <div class="flex justify-between w-full mb-4">
        <div>
            <h3><strong>{{$notification->message}}</strong></h3>
        </div>
        <div class="flex-grow"></div> <!-- Espaciador flexible para empujar los botones a la derecha -->
    <div class="flex space-x-2"> <!-- Añadir espacio entre los botones -->
        <form action="/shift-exchange/accept/{{ $notification->shiftExchange->id }}" method="POST">
            @csrf
            <button type="submit" class="bg-blue-500 px-2 py-1 rounded-xl text-xs text-white max-h-6">Aceptar</button>
        </form>
        <form action="/shift-exchange/cancel/{{ $notification->shiftExchange->id }}" method="POST">
                @csrf
            <button type="submit" class="bg-red-500 px-2 py-1 rounded-xl text-xs text-white max-h-6">Rechazar</button>
        </form>
    </div>
    </div>
    <div class="w-full"> <!-- Contenedor de la tabla -->
        <table class="w-full border-collapse border-0">
            <thead>
            <tr>
                <th class="px-2 py-1"></th>
                <th class="px-2 py-1 text-center">Actual</th>
                <th class="px-2 py-1 text-center">Cambio</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="px-1 py-1 bg-blue-50 text-sky-900 text-s text-center font-semibold rounded">
                    {{ $notification->shiftExchange->demander->id!=auth()->user()->id ? $notification->shiftExchange->demander->name: "Tú" }}
                </td>
                <td class="px-1 py-1 bg-blue-50 text-sky-900 text-sm text-center font-semibold rounded">
                    <a href="/horario/{{ $notification->shiftExchange->shiftDemander->schedule->id }}/turno/{{ $notification->shiftExchange->shift_demander_id }}"
                       class="hover:underline hover:cursor-pointer">
                        {{ \Carbon\Carbon::parse($notification->shiftExchange->shiftDemander->date)->format('d-m-Y') }}<br>
                        ({{ \Carbon\Carbon::parse($notification->shiftExchange->shiftDemander->start)->format('H:i') }} -
                        {{ \Carbon\Carbon::parse($notification->shiftExchange->shiftDemander->end)->format('H:i') }})
                    </a>
                </td>
                <td class="px-1 py-1 bg-blue-50 text-sky-900 text-sm text-center font-semibold rounded">
                    <a href="/horario/{{ $notification->shiftExchange->shiftReceiver->schedule->id }}/turno/{{ $notification->shiftExchange->shift_receiver_id }}"
                       class="hover:underline hover:cursor-pointer">
                        {{ \Carbon\Carbon::parse($notification->shiftExchange->shiftReceiver->date)->format('d-m-Y') }}<br>
                        ({{ \Carbon\Carbon::parse($notification->shiftExchange->shiftReceiver->start)->format('H:i') }} -
                        {{ \Carbon\Carbon::parse($notification->shiftExchange->shiftReceiver->end)->format('H:i') }})
                    </a>
                </td>
            </tr>
            <tr>
                <td class="px-1 py-1 bg-blue-50 text-sky-900 text-s text-center font-semibold rounded">
                    @if($notification->shiftExchange->receiver)
                        {{ $notification->shiftExchange->receiver->id==auth()->user()->id ? "Tú" : $notification->shiftExchange->receiver->name }}
                    @else
                        {{"No asignado"}}
                    @endif
                </td>
                <td class="px-1 py-1 bg-blue-50 text-sky-900 text-sm text-center font-semibold rounded">
                    <a href="/horario/{{ $notification->shiftExchange->shiftReceiver->schedule->id }}/turno/{{ $notification->shiftExchange->shift_receiver_id }}"
                       class="hover:underline hover:cursor-pointer">
                        {{ \Carbon\Carbon::parse($notification->shiftExchange->shiftReceiver->date)->format('d-m-Y') }}<br>
                        ({{ \Carbon\Carbon::parse($notification->shiftExchange->shiftReceiver->start)->format('H:i') }} -
                        {{ \Carbon\Carbon::parse($notification->shiftExchange->shiftReceiver->end)->format('H:i') }})
                    </a>
                </td>
                <td class="px-1 py-1 bg-blue-50 text-sky-900 text-sm text-center font-semibold rounded">
                    <a href="/horario/{{ $notification->shiftExchange->shiftDemander->schedule->id }}/turno/{{ $notification->shiftExchange->shift_demander_id }}"
                       class="hover:underline hover:cursor-pointer">
                        {{ \Carbon\Carbon::parse($notification->shiftExchange->shiftDemander->date)->format('d-m-Y') }}<br>
                        ({{ \Carbon\Carbon::parse($notification->shiftExchange->shiftDemander->start)->format('H:i') }} -
                        {{ \Carbon\Carbon::parse($notification->shiftExchange->shiftDemander->end)->format('H:i') }})
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</li>
