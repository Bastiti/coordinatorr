<x-layout>
    <x-slot name="title">Coordinatorr Adminpanel</x-slot>

    <x-slot name="slot">
        <div class="container mt-5" style="margin-top: 50px;">
            <form action="/admin/add_activator" method="post" class="panel">
                @csrf
                <div class="form-group">
                    <label for="activator_callsign">Activator:</label>
                    <input name="activator_callsign" class="form-control" type="text" placeholder="Activator callsign here...">
                </div>
                <div class="text-center">
                    <input type="submit" class="btn btn-primary" value="Add activator">
                </div>
            </form>
        </div>
        <div class="container mt-5">
            <form action="/admin/add_callsign" method="post" class="panel">
                @csrf
                <div class="form-group">
                    <label for="event_callsign">Eventcallsign:</label>
                    <input name="event_callsign" class="form-control" type="text" placeholder="Event callsign here...">
                </div>
                <div class="text-center">
                    <input type="submit" class="btn btn-primary" value="Add event callsign">
                </div>
            </form>
        </div>
        <h3 class="text-center mb-4" style="margin-top: 30px;">Coordinatorr mode:</h3>
        <div style="margin-bottom: 80px;">
            <div class="container mt-5">
                <form action="/admin/switch_mode" method="post" class="panel">
                    @csrf
                    <div class="form-group">
                        <label for="mode" class="text-danger">DANGER: Changing this will delete all current activations and upcoming planned activations!</label>
                        <select class="form-control" name="mode">
                            @foreach ($modes as $mode)
                            <option value="{{ $mode->option }}" {{ $current_appmode == $mode->option ? 'selected' : '' }}>{{ $mode->option }} -> {{ $mode->description }}</option>    
                            @endforeach
                        </select>
                    </div>
                    <div class="text-center">
                        <input type="submit" class="btn btn-primary" value="Change coordinatorr mode">
                    </div>
                </form>
            </div>
        </div>
        <h3 class="text-center mb-4" style="margin-top: 30px;">Activators:</h3>
        <div style="margin-bottom: 80px;">
            <div class="container mt-5 section">
                <table class="table table-bordered table-hover table-dark" style="margin-bottom: 5px;">
                    <thead class="thead-light">
                        <tr>
                            <th>Activatorcall</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activators as $activator)
                        <tr>
                            <td>{{ $activator->call }}</td>
                            <td style="text-align: center;">
                                <form method="post" action="/activator/{{ $activator->call }}/remove" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Remove Activator</button>
                                </form>
                                @if($activator->locked)
                                <form method="post" action="/activator/{{ $activator->call }}/lock" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Unlock Activator</button>
                                </form>
                                @else
                                <form method="post" action="/activator/{{ $activator->call }}/lock" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-warning">Lock Activator</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <h3 class="text-center mb-4" style="margin-top: 30px;">Eventcalls:</h3>
        <div style="margin-bottom: 80px;">
            <div class="container mt-5 section">
                <table class="table table-bordered table-hover table-dark" style="margin-bottom: 5px;">
                    <thead class="thead-light">
                        <tr>
                            <th>Eventcalls</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eventcalls as $eventcall)
                        <tr>
                            <td>{{ $eventcall->call }}</td>
                            <td style="text-align: center;">
                                <form method="post" action="/callsign/{{ $eventcall->call }}/remove" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Remove Eventcallsign</button>
                                </form>
                                @if($eventcall->hidden)
                                <form method="post" action="/callsign/{{ $eventcall->call }}/hide" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Reveal Eventcallsign</button>
                                </form>
                                @else
                                <form method="post" action="/callsign/{{ $eventcall->call }}/hide" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-warning">Hide Eventcallsign</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div style="margin-bottom: 80px;">
        <!-- Dummy Diff als Abstandshalter für Mobile Landscape -->
        </div>
    </x-slot>

</x-layout>

