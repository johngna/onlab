<div>
    @if($tipo == 1)
    <div class="p-6 bg-white rounded-lg shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-sky-700"  id="{{$sensor}}Name">Nível do Galão</h2>
            {{-- <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg> --}}
        </div>
        <div class="mb-2 text-4xl font-bold text-center text-sky-800">{{$readings[0]->$sensor}}</div>
        <div style="height: 120px;">
            <canvas id="{{$sensor}}Chart"></canvas>
        </div>
    </div>
    @else

    <div class="p-6 bg-white rounded-lg shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-sky-700">Nível do Galão</h2>
            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
        </div>
        <div class="mb-2 text-4xl font-bold text-center text-sky-800">{{$readings[0]->gal_0}}%</div>
        <div style="height: 120px; position: relative;">
            <svg id="waterTank" viewBox="0 0 200 200" style="width: 100%; height: 100%; margin: 0 auto;">
                <!-- Cylinder body -->
                <defs>
                    <linearGradient id="cylinderGradient" x1="0%" y1="0%" x2="100%" y1="0%" >
                        <stop offset="0%" style="stop-color:#e5e7eb" />
                        <stop offset="50%" style="stop-color:#f3f4f6" />
                        <stop offset="100%" style="stop-color:#d1d5db" />
                    </linearGradient>
                    <clipPath id="waterClip">
                        <path d="M40 20 L160 20 Q180 20 180 30 L180 170 Q180 180 160 180 L40 180 Q20 180 20 170 L20 30 Q20 20 40 20 Z" />
                    </clipPath>
                </defs>

                <!-- Cylinder container -->
                <path d="M40 20 L160 20 Q180 20 180 30 L180 170 Q180 180 160 180 L40 180 Q20 180 20 170 L20 30 Q20 20 40 20 Z"
                      fill="url(#cylinderGradient)"
                      stroke="#999"
                      stroke-width="2"/>

                <!-- Water -->
                <g clip-path="url(#waterClip)">
                    <path class="water"
                          opacity="0.8">
                        <animate attributeName="d"
                                 dur="2s"
                                 repeatCount="indefinite"
                                 values="
                                    M0 {{ 180 - ($readings[0]->gal_0 * 1.6) }} Q50 {{ 165 - ($readings[0]->gal_0 * 1.6) }} 100 {{ 180 - ($readings[0]->gal_0 * 1.6) }} Q150 {{ 195 - ($readings[0]->gal_0 * 1.6) }} 200 {{ 180 - ($readings[0]->gal_0 * 1.6) }} L200 200 L0 200 Z;
                                    M0 {{ 180 - ($readings[0]->gal_0 * 1.6) }} Q50 {{ 195 - ($readings[0]->gal_0 * 1.6) }} 100 {{ 180 - ($readings[0]->gal_0 * 1.6) }} Q150 {{ 165 - ($readings[0]->gal_0 * 1.6) }} 200 {{ 180 - ($readings[0]->gal_0 * 1.6) }} L200 200 L0 200 Z;
                                    M0 {{ 180 - ($readings[0]->gal_0 * 1.6) }} Q50 {{ 165 - ($readings[0]->gal_0 * 1.6) }} 100 {{ 180 - ($readings[0]->gal_0 * 1.6) }} Q150 {{ 195 - ($readings[0]->gal_0 * 1.6) }} 200 {{ 180 - ($readings[0]->gal_0 * 1.6) }} L200 200 L0 200 Z"/>
                    </path>
                </g>

                <!-- Top ellipse -->
                {{-- <ellipse cx="100" cy="20" rx="80" ry="10" fill="#f3f4f6" stroke="#999" stroke-width="2"/> --}}
            </svg>
        </div>
    </div>
    @endif
</div>
