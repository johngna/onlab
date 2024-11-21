<div>
    <div class="p-6 bg-white rounded-lg shadow-lg" >
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-sky-700"  id="{{$sensor}}Name">Pressão</h2>
            {{-- <svg class="w-8 h-8 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg> --}}
        </div>
        <div class="mb-2 text-4xl font-bold text-center text-sky-800">{{$readings[0]->$sensor}}</div>
        <div style="height: 120px;">
            <canvas id="{{$sensor}}Chart"></canvas>
        </div>
    </div>

</div>
