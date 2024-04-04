<div class="sm:fixed sm:top-0 sm:right-0 p-6 text-end z-20">
    @auth
        <a href="{{ url('/dashboard') }}"
            class="font-semibold text-gray-600 hover:text-gray-900  focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500 bg-gray-200 px-8 py-2 rounded-3xl shadow-[10px_10px_1px_rgba(15,_118,_110,_1),_0_10px_20px_rgba(204,_204,_204,_1)]"
            wire:navigate>Dashboard</a>
    @else
        <a href="{{ route('login') }}"
            class="font-semibold text-gray-600 hover:text-gray-900  focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500 bg-gray-200 px-8 py-2 rounded-3xl shadow-[10px_10px_1px_rgba(15,_118,_110,_1),_0_10px_20px_rgba(204,_204,_204,_1)]"
            wire:navigate>Log in</a>
        {{-- // HACK: removes register link from production --}}
        {{-- @if (Route::has('register'))
            <a href="{{ route('register') }}" class="ms-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500" wire:navigate>Register</a>
        @endif --}}
    @endauth
</div>
