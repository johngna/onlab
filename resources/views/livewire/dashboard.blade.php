<div>
    <div class="mt-8">
        <div class="max-w-6xl px-4 mx-auto sm:px-6 lg:px-8">
          {{-- <h2 class="text-lg font-medium leading-6 text-sky-900">Indicadores</h2> --}}
          <div class="grid grid-cols-1 gap-5 mt-2 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Card -->

            <div class="overflow-hidden bg-white rounded-lg shadow">
              <div class="p-5">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <!-- Heroicon name: outline/scale -->
                    <x-icon name="beaker" class="w-6 h-6 text-gray-400" />
                  </div>
                  <div class="flex-1 w-0 ml-5">
                    <dl>
                      <dt class="text-sm font-medium text-gray-500 truncate">Equipamentos</dt>
                      <dd>
                        <div class="text-lg font-medium text-gray-900">{{count($equipamentos)}}</div>
                      </dd>
                    </dl>
                  </div>
                </div>
              </div>
              {{-- <div class="px-5 py-3 bg-gray-50">
                <div class="text-sm">
                  <a href="{{route('equipamentos')}}" class="font-medium text-cyan-700 hover:text-cyan-900"> Acessar </a>
                </div>
              </div> --}}
            </div>

            <div class="overflow-hidden bg-white rounded-lg shadow">
              <div class="p-5">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <x-icon name="chat-bubble-left-right" class="w-6 h-6 text-gray-400" />
                  </div>
                  <div class="flex-1 w-0 ml-5">
                    <dl>
                      <dt class="text-sm font-medium text-gray-500 truncate">Chamados</dt>
                      <dd>
                        <div class="text-lg font-medium text-gray-900">{{count($tasks)}}</div>
                      </dd>
                    </dl>
                  </div>
                </div>
              </div>
              {{-- <div class="px-5 py-3 bg-gray-50">
                <div class="text-sm">
                  <a href="#" class="font-medium text-cyan-700 hover:text-cyan-900"> Acessar </a>
                </div>
              </div> --}}
            </div>

            <div class="overflow-hidden bg-white rounded-lg shadow">
              <div class="p-5">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <x-icon name="calendar" class="w-6 h-6 text-gray-400" />
                  </div>
                  <div class="flex-1 w-0 ml-5">
                    <dl>
                      <dt class="text-sm font-medium text-gray-500 truncate">Proxima Manutenção</dt>
                      <dd>
                        <div class="text-lg font-medium text-gray-900">{{ \Carbon\Carbon::parse($data_proxima_visita)->format('d/m/Y') }}</div>
                      </dd>
                    </dl>
                  </div>
                </div>
              </div>
              {{-- <div class="px-5 py-3 bg-gray-50">
                <div class="text-sm">
                  <a href="#" class="font-medium text-cyan-700 hover:text-cyan-900"> Acessar </a>
                </div>
              </div> --}}
            </div>

            <!-- More items... -->
          </div>
        </div>


        <h2 class="max-w-6xl px-4 mx-auto mt-8 text-lg font-medium leading-6 text-sky-900 sm:px-6 lg:px-8">Equipamentos</h2>
        <!-- Equipamentos table (small breakpoint and up) -->
        <div class="hidden sm:block">
          <div class="max-w-6xl px-4 mx-auto mt-8 sm:px-6 lg:px-8">

            <div class="flex flex-col mt-2">
              <div class="min-w-full overflow-hidden overflow-x-auto align-middle shadow sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead>
                    <tr>
                      <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase bg-gray-50">Nome</th>
                      <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase bg-gray-50">Número de Série</th>

                      <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase bg-gray-50">Status</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($equipamentos as $equipamento)
                    <tr class="bg-white">
                      <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                        <div class="flex">
                          <a href="{{route('equipamento', $equipamento['id'])}}" class="inline-flex space-x-2 text-sm truncate group">
                            <p class="text-gray-500 truncate group-hover:text-gray-900">{{$equipamento['name']}}</p>
                          </a>
                        </div>
                      </td>
                      <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                        {{$equipamento['identifier']}}
                      </td>

                      <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                        @if($equipamento['active'] == 'true')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 capitalize"> Ativo </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 capitalize"> Inativo </span>
                        @endif
                    </td>
                    </tr>
                    @endforeach
                    <!-- More transactions... -->
                    </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="shadow sm:hidden">
            <ul role="list" class="overflow-hidden divide-y divide-gray-200 shadow sm:hidden">

                @foreach($equipamentos as $equipamento)
              <li>
                <a href="{{route('equipamento', $equipamento['id'])}}" class="block px-4 py-4 bg-white hover:bg-gray-50">
                  <span class="flex items-center space-x-4">
                    <span class="flex flex-1 space-x-2 truncate">
                      <span class="flex flex-col text-sm text-gray-500 truncate">
                        <span class="truncate">{{$equipamento['name']}}</span>
                        <span>{{$equipamento['identifier']}}</span>
                        <span >
                            @if($equipamento['active'] == 'true')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 capitalize"> Ativo </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 capitalize"> Inativo </span>
                            @endif
                        </span>
                      </span>
                    </span>
                    <!-- Heroicon name: solid/chevron-right -->
                    <svg class="flex-shrink-0 w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                      <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                  </span>
                </a>
              </li>
              @endforeach
              <!-- More transactions... -->
            </ul>



          </div>

        <h2 class="max-w-6xl px-4 mx-auto mt-8 text-lg font-medium leading-6 text-sky-900 sm:px-6 lg:px-8">Histórico de Chamados</h2>

        <!-- Activity list (smallest breakpoint only) -->
        <div class="shadow sm:hidden">
          <ul role="list" class="mt-2 overflow-hidden divide-y divide-gray-200 shadow sm:hidden">

            @foreach($tasks as $task)
            <li>
              <a href="{{$task['taskUrl']}}" class="block px-4 py-4 bg-white hover:bg-gray-50">
                <span class="flex items-center space-x-4">
                  <span class="flex flex-1 space-x-2 truncate">
                    <span class="flex flex-col text-sm text-gray-500 truncate">
                      <span class="truncate">{{$task['orientation']}}</span>
                      <span>{{$task['taskTypeDescription']}} </span>
                      <time datetime="2020-07-11"> {{ \Carbon\Carbon::parse($task['taskDate'])->format('d/m/Y H:i') }}</time>
                    </span>
                  </span>
                  <!-- Heroicon name: solid/chevron-right -->
                  <svg class="flex-shrink-0 w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                  </svg>
                </span>
              </a>
            </li>
            @endforeach
            <!-- More transactions... -->
          </ul>



        </div>

        <!-- Activity table (small breakpoint and up) -->
        <div class="hidden sm:block">
          <div class="max-w-6xl px-4 mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col mt-2">
              <div class="min-w-full overflow-hidden overflow-x-auto align-middle shadow sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead>
                    <tr>
                      <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase bg-gray-50">Data</th>
                      <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase bg-gray-50">Descrição</th>
                      <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase bg-gray-50">Tipo</th>
                      <th class="hidden px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase bg-gray-50 md:block">Status</th>

                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($tasks as $task)
                    <tr class="bg-white">
                     <td class="px-6 py-4 text-sm text-right text-gray-500 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($task['taskDate'])->format('d/m/Y H:i') }}
                      </td>
                      <td class="w-full px-6 py-4 text-sm text-gray-900 max-w-0 whitespace-nowrap">
                        <div class="flex">
                          <a href="{{$task['taskUrl']}}" class="inline-flex space-x-2 text-sm truncate group">
                            <p class="text-gray-500 truncate group-hover:text-gray-900">{{$task['orientation']}}</p>
                          </a>
                        </div>
                      </td>
                      <td class="px-6 py-4 text-sm text-left text-gray-500 whitespace-nowrap">
                        {{$task['taskTypeDescription']}}
                      </td>
                      <td class="hidden px-6 py-4 text-sm text-gray-500 whitespace-nowrap md:block">
                        @if($task['finished'])
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 capitalize"> Concluída </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 capitalize"> Aberta </span>
                        @endif
                    </td>

                    </tr>
                    @endforeach

                    <!-- More transactions... -->
                  </tbody>
                </table>
                <!-- Pagination -->

              </div>
            </div>
          </div>
        </div>


      </div>
</div>
