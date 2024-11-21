<x-app-layout>

    <div class="flex-1 pb-8">
        <!-- Page header -->
        <div class="bg-white shadow">
          <div class="px-4 sm:px-6 lg:max-w-6xl lg:mx-auto lg:px-8">
            <div class="py-6 md:flex md:items-center md:justify-between lg:border-t lg:border-gray-200">
              <div class="flex-1 min-w-0">
                <!-- Profile -->
                <div class="flex items-center">
                  {{-- <img class="hidden w-16 h-16 rounded-full sm:block" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2.6&w=256&h=256&q=80" alt=""> --}}
                  <x-icon name="user" class="hidden w-16 h-16 rounded-full sm:block text-sky-800" />
                  <div>
                    <div class="flex items-center">
                      {{-- <img class="w-16 h-16 rounded-full sm:hidden" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2.6&w=256&h=256&q=80" alt=""> --}}
                      <x-icon name="user" class="w-16 h-16 rounded-full sm:hidden text-sky-800" />
                      <h1 class="ml-3 text-2xl font-bold leading-7 text-gray-700 sm:leading-9 sm:truncate">Bem Vindo, <span class="text-sky-800">{{Auth::user()->name}}</span></h1>
                    </div>
                    <dl class="flex flex-col mt-6 sm:ml-3 sm:mt-1 sm:flex-row sm:flex-wrap">
                      <dt class="sr-only">Email</dt>
                      <dd class="flex items-center text-sm font-medium text-gray-500 sm:mr-6">
                        <!-- Heroicon name: solid/office-building -->
                        <x-icon name="user" class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" />
                        {{Auth::user()->email}}
                      </dd>
                      <dt class="sr-only">Account status</dt>
                      <dd class="flex items-center mt-3 text-sm font-medium text-gray-500 capitalize sm:mr-6 sm:mt-0">
                        <!-- Heroicon name: solid/check-circle -->
                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Conta Verificada
                      </dd>
                    </dl>
                  </div>
                </div>
              </div>
              <div class="flex mt-6 space-x-3 md:mt-0 md:ml-4">
                {{-- <button type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">Add money</button> --}}
                <a href="https://suporteonlab.auvo.com.br/Ticket/Novo" target="blank" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white border border-transparent rounded-md shadow-sm bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">Abrir Chamado</a>
              </div>
            </div>
          </div>
        </div>

        @livewire('dashboard')
    </div>

</x-app-layout>
