<div>

    @if($integra)
    <style>
        .water {
            opacity: 0.8;
            fill: #3B82F6;
        }
        .tank-body {
            filter: drop-shadow(2px 2px 4px rgba(0,0,0,0.2));
        }
    </style>
    @endif

    <div class="px-4 mx-auto mt-8 max-w-7xl" wire:poll.10s="fetchEquipmentData">

        <div class="grid grid-cols-3 p-6 mb-6 bg-white rounded-lg shadow-lg">
            <div class="col-span-2">
                <div class="flex items-start ">
                    <!-- Imagem do equipamento -->
                    <div class="hidden w-48 h-48 mr-6 overflow-hidden bg-gray-200 rounded-lg sm:block">
                        <img alt="Equipamento de Osmose Reversa - vista frontal, estilo técnico" src="/assets/img/filtro_1000.png" class="object-cover w-full h-full" width="192" height="192">
                    </div>

                    <div class="flex-1">
                        <!-- Nome do equipamento -->
                        <h1 class="text-2xl font-bold text-sky-800">{{$equipamento['name']}} - {{$equipamento['identifier']}}</h1>

                        <!-- Informações do cliente -->
                        <div class="mt-2">
                            <p class="text-lg text-gray-600">Cliente: {{$cliente['description']}}</p>
                            <p class="text-gray-500">{{$cliente['address']}}</p>
                        </div>

                        <!-- Status e ações -->
                        <div class="flex items-center gap-6 mt-4">

                            @if($integra)
                            @if($avisos['status'])
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="ml-2 font-medium text-green-500">{{$avisos['status']}}</span>
                            </div>
                            @endif


                            @if($avisos['flags'])
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <span class="ml-2 font-medium text-red-500">{{$avisos['flags']}}</span>
                            </div>
                            @endif

                            @if($avisos['alarmes'])
                            <div class="flex items-center px-3 py-1 bg-red-700 animate-pulse">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <span class="ml-2 font-medium text-white ">{{$avisos['alarmes']}}</span>
                            </div>
                            @endif
                            @endif


                        </div>
                    </div>
                </div>
            </div>

            @if($integra)

            <div class="col-span-1">

                <div style="height: 180px; position: relative;">
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
                                            M0 {{ 180 - ($reservs[0]->gal_0 * 1.6) }} Q50 {{ 165 - ($reservs[0]->gal_0 * 1.6) }} 100 {{ 180 - ($reservs[0]->gal_0 * 1.6) }} Q150 {{ 195 - ($reservs[0]->gal_0 * 1.6) }} 200 {{ 180 - ($reservs[0]->gal_0 * 1.6) }} L200 200 L0 200 Z;
                                            M0 {{ 180 - ($reservs[0]->gal_0 * 1.6) }} Q50 {{ 195 - ($reservs[0]->gal_0 * 1.6) }} 100 {{ 180 - ($reservs[0]->gal_0 * 1.6) }} Q150 {{ 165 - ($reservs[0]->gal_0 * 1.6) }} 200 {{ 180 - ($reservs[0]->gal_0 * 1.6) }} L200 200 L0 200 Z;
                                            M0 {{ 180 - ($reservs[0]->gal_0 * 1.6) }} Q50 {{ 165 - ($reservs[0]->gal_0 * 1.6) }} 100 {{ 180 - ($reservs[0]->gal_0 * 1.6) }} Q150 {{ 195 - ($reservs[0]->gal_0 * 1.6) }} 200 {{ 180 - ($reservs[0]->gal_0 * 1.6) }} L200 200 L0 200 Z"/>
                            </path>
                        </g>

                        <!-- Level lines -->
                        <line x1="40" y1="50" x2="160" y2="50" stroke="#999" stroke-width="1" stroke-dasharray="4" opacity="0.5"/>
                        <line x1="40" y1="90" x2="160" y2="90" stroke="#999" stroke-width="1" stroke-dasharray="4" opacity="0.5"/>
                        <line x1="40" y1="130" x2="160" y2="130" stroke="#999" stroke-width="1" stroke-dasharray="4" opacity="0.5"/>
                        <line x1="40" y1="170" x2="160" y2="170" stroke="#999" stroke-width="1" stroke-dasharray="4" opacity="0.5"/>

                        <!-- Top ellipse -->
                        {{-- <ellipse cx="100" cy="20" rx="80" ry="10" fill="#f3f4f6" stroke="#999" stroke-width="2"/> --}}
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center text-2xl font-bold text-sky-800">{{$reservs[0]->gal_0}}%</div>
                </div>
            </div>
            @endif

        </div>


        @if($integra)

            <!-- Cards de monitoramento -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-4" >


                <!-- Card Condutivímetro -->

                @livewire('components.card-condutividade', ['readings' => $readings, 'sensor' => 'cd_ou'])

                @livewire('components.card-condutividade', ['readings' => $readings, 'sensor' => 'cd_md'])

                @livewire('components.card-condutividade', ['readings' => $readings, 'sensor' => 'cd_in'])






                <!-- Fluxo -->
                @livewire('components.card-fluxo', ['readings' => $readings, 'sensor' => 'fx_in'])

                @livewire('components.card-fluxo', ['readings' => $readings, 'sensor' => 'fx_md'])




                <!-- Card Temperatura -->

                @livewire('components.card-temperatura', ['sensor' => 'temp1', 'readings' => $readings])

                @livewire('components.card-temperatura', ['sensor' => 'tplc1', 'readings' => $readings])


                <!-- Pressão -->
                @livewire('components.card-pressao', ['readings' => $readings, 'sensor' => 't_pre'])

                <!-- Card Nível do Galão -->
                @livewire('components.card-galao', ['readings' => $readings, 'sensor' => 'gal_0'])

                @livewire('components.card-galao', ['readings' => $readings, 'sensor' => 'gal_1'])

                @livewire('components.card-galao', ['readings' => $readings, 'sensor' => 'gal_2'])

                @livewire('components.card-galao', ['readings' => $readings, 'sensor' => 'gal_3'])


            </div>

            <!-- tabela de leituras -->
            <div class="mt-12 mb-12 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Sensor</th>
                            @foreach($readings as $reading)
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">{{ $reading->created_at->format('H:i') }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-500 whitespace-nowrap">Temperatura Placa (°C)</td>
                            @foreach($readings as $reading)
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{$reading->tplc1}}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-500 whitespace-nowrap">Temperatura Água (°C)</td>
                            @foreach($readings as $reading)
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{$reading->temp1}}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-500 whitespace-nowrap">Condutividade Feed (µS/cm)</td>
                            @foreach($readings as $reading)
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{$reading->cd_ou}}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-500 whitespace-nowrap">Condutividade Entrada (µS/cm)</td>
                            @foreach($readings as $reading)
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{$reading->cd_in}}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-500 whitespace-nowrap">Condutividade Pós Membrana (µS/cm)</td>
                            @foreach($readings as $reading)
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{$reading->cd_md}}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-500 whitespace-nowrap">Nível do Galão (%)</td>
                            @foreach($readings as $reading)
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{$reading->gal_0}}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-500 whitespace-nowrap">Pressão (psi)</td>
                            @foreach($readings as $reading)
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{$reading->t_pre}}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-500 whitespace-nowrap">Fluxo Pós Membrana (L/min)</td>
                            @foreach($readings as $reading)
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{$reading->fx_md}}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-500 whitespace-nowrap">Fluxo Entrada (L/min)</td>
                            @foreach($readings as $reading)
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{$reading->fx_in}}</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>

            <script>
                document.addEventListener('livewire:init', () => {

                    Livewire.on('InitializeChartData', (readings) => {

                        const generateTimeLabels = [];
                        readings[0].map(reading => {
                            const date = new Date(reading.created_at);
                            date.setHours(date.getHours() + 3);
                            generateTimeLabels.push(date.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' }));
                        });

                        // Configuração base dos gráficos
                        const chartConfig = {
                            type: 'line',
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    x: {
                                        display: true,
                                        grid: {
                                            display: false
                                        },
                                        ticks: {
                                            maxRotation: 45,
                                            minRotation: 45
                                        }
                                    },
                                    y: {
                                        display: true,
                                        beginAtZero: false,
                                        grid: {
                                            drawBorder: false,
                                            color: 'rgba(0, 0, 0, 0.1)'
                                        },
                                        ticks: {
                                            font: {
                                                size: 10
                                            },
                                            color: 'rgba(0, 0, 0, 0.5)'
                                        }
                                    }
                                },
                                elements: {
                                    line: {
                                        tension: 0.4
                                    },
                                    point: {
                                        radius: 3,
                                        hoverRadius: 5
                                    }
                                },
                                interaction: {
                                    intersect: false,
                                    mode: 'index'
                                },
                                animation: {
                                    duration: 1000
                                }
                            }
                        };

                        //destroy all charts before initializing
                        Chart.helpers.each(Chart.instances, function (instance) {
                            instance.destroy();
                        });


                        // Criação dos gráficos
                        createChart(generateTimeLabels, chartConfig, 'Temperatura Placa (°C)', readings, 'tplc1');
                        createChart(generateTimeLabels, chartConfig, 'Temperatura Água (°C)', readings, 'temp1');
                        createChart(generateTimeLabels, chartConfig, 'Condutividade Feed (µS/cm)', readings, 'cd_ou');
                        createChart(generateTimeLabels, chartConfig, 'Condutividade Entrada (µS/cm)', readings, 'cd_in');
                        createChart(generateTimeLabels, chartConfig, 'Condutividade Pós Membrana (µS/cm)', readings, 'cd_md');
                        createChart(generateTimeLabels, chartConfig, 'Nível do Galão (%)', readings, 'gal_0');
                        createChart(generateTimeLabels, chartConfig, 'Nível do Galão 2(%)', readings, 'gal_1');
                        createChart(generateTimeLabels, chartConfig, 'Nível do Galão 3(%)', readings, 'gal_2');
                        createChart(generateTimeLabels, chartConfig, 'Nível do Galão 4(%)', readings, 'gal_3');
                        createChart(generateTimeLabels, chartConfig, 'Pressão (psi)', readings, 't_pre');
                        createChart(generateTimeLabels, chartConfig, 'Fluxo Pós Membrana (L/min)', readings, 'fx_md');
                        createChart(generateTimeLabels, chartConfig, 'Fluxo Entrada (L/min)', readings, 'fx_in');

                    });


                    Livewire.on('refreshChartData', (readings) => {



                        const tempChart = Object.values(Chart.instances).filter(instance => instance.canvas.id === 'tplc1Chart')[0];
                        const temp1Chart = Object.values(Chart.instances).filter(instance => instance.canvas.id === 'temp1Chart')[0];
                        const cd_ouChart = Object.values(Chart.instances).filter(instance => instance.canvas.id === 'cd_ouChart')[0];
                        const cd_inChart = Object.values(Chart.instances).filter(instance => instance.canvas.id === 'cd_inChart')[0];
                        const cd_mdChart = Object.values(Chart.instances).filter(instance => instance.canvas.id === 'cd_mdChart')[0];
                        const gal_0Chart = Object.values(Chart.instances).filter(instance => instance.canvas.id === 'gal_0Chart')[0];
                        const gal_1Chart = Object.values(Chart.instances).filter(instance => instance.canvas.id === 'gal_1Chart')[0];
                        const gal_2Chart = Object.values(Chart.instances).filter(instance => instance.canvas.id === 'gal_2Chart')[0];
                        const gal_3Chart = Object.values(Chart.instances).filter(instance => instance.canvas.id === 'gal_3Chart')[0];
                        const t_preChart = Object.values(Chart.instances).filter(instance => instance.canvas.id === 't_preChart')[0];
                        const fx_mdChart = Object.values(Chart.instances).filter(instance => instance.canvas.id === 'fx_mdChart')[0];
                        const fx_inChart = Object.values(Chart.instances).filter(instance => instance.canvas.id === 'fx_inChart')[0];


                        updateChart(tempChart, readings[0][0], 'tplc1');
                        updateChart(temp1Chart, readings[0][0], 'temp1');
                        updateChart(cd_ouChart, readings[0][0], 'cd_ou');
                        updateChart(cd_inChart, readings[0][0], 'cd_in');
                        updateChart(cd_mdChart, readings[0][0], 'cd_md');
                        updateChart(gal_0Chart, readings[0][0], 'gal_0');
                        updateChart(gal_1Chart, readings[0][0], 'gal_1');
                        updateChart(gal_2Chart, readings[0][0], 'gal_2');
                        updateChart(gal_3Chart, readings[0][0], 'gal_3');
                        updateChart(t_preChart, readings[0][0], 't_pre');
                        updateChart(fx_mdChart, readings[0][0], 'fx_md');
                        updateChart(fx_inChart, readings[0][0], 'fx_in');




                    });



                    function updateChart(chart, newReading, sensor_name) {
                        chart.data.labels.shift();
                        const date = new Date(newReading.created_at);
                        date.setHours(date.getHours() + 3);
                        chart.data.labels.push(date.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' }));
                        chart.data.datasets[0].data.shift();
                        chart.data.datasets[0].data.push(newReading[sensor_name]);
                        chart.update();

                        document.getElementById(sensor_name + 'Value').innerText = newReading[sensor_name];
                    }


                    function createChart(generateTimeLabels, chartConfig, label, readings, sensor_name) {


                        // ID do gráfico
                        const chart_id = sensor_name + 'Chart';

                        // Dados específicos para cada gráfico
                        const generateData = () => ({
                            labels: generateTimeLabels,
                            datasets: [{
                                label: label,
                                data: readings[0].map(reading => reading[sensor_name]),
                                borderColor: '#3B82F6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                fill: true,
                                borderWidth: 2
                            }]
                        });

                        // Criação do gráfico
                        const newchart = new Chart(document.getElementById(chart_id),
                            {...chartConfig, data: generateData()}
                        );

                        //atualizar label do gráfico
                        document.getElementById(sensor_name + 'Name').innerText = label;

                    }






                });
            </script>


        @else

            <div class="flex flex-col items-center justify-start p-6 py-24 mb-6 bg-white rounded-lg shadow-lg">
                <div>
                    <x-icon name="exclamation-circle" class="w-12 h-12 text-gray-600" />
                </div>
                <div>
                    <h3 class="mt-8 text-3xl font-bold text-center text-gray-600 ">Este equipamento não possui integração ou ainda não está habilitada</h3>
                </div>
            </div>

        @endif


    </div>
</div>
