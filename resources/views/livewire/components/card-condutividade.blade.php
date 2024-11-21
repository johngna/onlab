<div>
    <div class="p-6 bg-white rounded-lg shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-sky-700"  id="{{$sensor}}Name">Condutividade</h2>
            {{-- <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg> --}}
        </div>
        <div class="mb-2 text-4xl font-bold text-center text-sky-800"  id="{{$sensor}}Value">{{$readings[0]->$sensor}}</div>
        <div style="height: 120px;">
            <canvas id="{{$sensor}}Chart"></canvas>
        </div>
    </div>

</div>
