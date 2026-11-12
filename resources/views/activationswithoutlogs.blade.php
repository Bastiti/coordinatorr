<x-layout>
    <x-slot name="title">Coordinatorr</x-slot>

    <x-slot name="slot">
        @if($activations->count() > 0)
        <h3 class="text-center mb-4" style="margin-top: 50px;">Activations without logs:</h3>
        <div style="margin-bottom: 80px;">
            <div class="container mt-5 section">
                <table class="table table-bordered table-hover table-dark" style="margin-bottom: 5px;">
                    <thead class="thead-light">
                        <tr>
                            <th>Eventcallsign</th>
                            <th>Activatorcall</th>
                            <th>From (UTC)</th>
                            <th>To (UTC)</th>
                            @if($appmode != 'SINGLEOP')
                            <th>Band</th>
                            @endif
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activations as $activation)
                        <tr>
                            <td>{{ $activation->callsign->call }}</td>
                            <td>{{ $activation->activator->call }}</td>
                            <td>{{ $activation->start->setTimezone('UTC') }} UTC</td>
                            <td>{{ $activation->end->setTimezone('UTC') }} UTC</td>
                            @if($appmode != 'SINGLEOP')
                            <td>{{ $activation->band_id == null ? '' : $activation->band->band }}</td>
                            @endif
                            <td>
                                <form method="post" action="/activation/{{ $activation->id }}/logreceived" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Log received</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div style="margin-bottom: 80px;">
        <!-- Dummy Diff als Abstandshalter für Mobile Landscape -->
        </div>
        @endif
    </x-slot>

</x-layout>
    
