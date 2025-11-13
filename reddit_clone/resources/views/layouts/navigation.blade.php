<div x-data="{ open: false, openModal: false }">
    <nav class="bg-red-600 border-b border-gray-300 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo and Navigation Links -->
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <span class="font-bold text-xl text-gray-800">PostSphear</span>
                    </a>
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="ml-4">
                        {{ __('Home') }}
                    </x-nav-link>
                </div>

                <!-- User Dropdown -->
                <div class="flex items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-200 transition">
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('user.show', Auth::user()->id)">
                                {{ __('Show Profile') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Edit Profile') }}
                            </x-dropdown-link>
                            <x-dropdown-link href="#" @click.prevent="openModal = true">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div :class="{ 'block': open, 'hidden': !open }" class="sm:hidden hidden bg-white border-t border-gray-300">
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Home') }}
                </x-responsive-nav-link>
            </div>

            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-900">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-600">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="#" @click.prevent="openModal = true">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </div>
            </div>
        </div>
    </nav>

    <!-- Logout Confirmation Modal -->
   <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 transition-opacity duration-300 z-50" x-show="openModal" x-transition style="display: none;">
        <div class="bg-white p-6 rounded shadow-lg max-w-md w-full" @click.outside="openModal = false">
            <h2 class="text-xl font-bold mb-2">Conferma Logout</h2>
            <p class="mb-4">Sei sicuro di voler uscire?</p>
            <div class="flex justify-end space-x-2">
                <button @click="openModal = false" class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400 transition">
                    Annulla
                </button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                        Esci
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>