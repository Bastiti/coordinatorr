<x-layout>
    <x-slot name="title">Coordinatorr</x-slot>

    <x-slot name="slot">
        <div class="container mt-5">
            <h1>Event access code:</h1>
            <p>Logging, ending, planning and deleting activations requires the event access code. Ask the event coordinator if you don't have it.</p>
            <form action="/access" method="post" class="panel">
                @csrf
                <div class="form-group">
                    <label for="access_code">Access code:</label>
                    <input id="access_code" name="access_code" class="form-control" type="password" autocomplete="current-password" placeholder="Enter the event access code here..." autofocus>
                </div>
                <button type="submit" class="btn btn-primary">Unlock</button>
            </form>
        </div>

        <div style="margin-bottom: 80px;">
            <!-- Dummy Diff als Abstandshalter für Mobile Landscape -->
        </div>
    </x-slot>
</x-layout>
