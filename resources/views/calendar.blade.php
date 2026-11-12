<x-layout>
    <x-slot name="title">Coordinator</x-slot>

    <x-slot name="slot">
        <div class="container mt-5">
            <h1>Calendar:</h1>
            <div style="text-align: center"><a href="/planned_activations"><button class="btn btn-primary">Back to planned activations</button></a></div>
            <div class="section">
                <x-calendar />
            </div>
        </div>

        <div style="margin-bottom: 80px;">
            <!-- Dummy Diff als Abstandshalter für Mobile Landscape -->
        </div>
    </x-slot>
</x-layout>
