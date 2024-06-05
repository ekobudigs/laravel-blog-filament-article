
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <title>{{ $metaTitle ?: 'Eko Budi Blog' }}</title>
    <meta name="author" content="Eko Budi">
    <meta name="description" content="{{ $metaDescription }}">

  
    <style>
        @import url('https://fonts.googleapis.com/css?family=Karla:400,700&display=swap');
    </style>

    @livewireStyles
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-family-karla">



    <!-- Text Header -->
    <header class="w-full container mx-auto">
        <div class="flex flex-col items-center py-12">
            <a class="font-bold text-gray-800 uppercase hover:text-gray-700 text-5xl" href="{{ route('home')}}">
                Eko Budi News 
            </a>
            <p class="text-lg text-gray-600">
               {{ \App\Models\TextWidget::getTitle('header') }}
            </p>
        </div>
    </header>

    <!-- Topic Nav -->
    <nav class="w-full py-4 border-t border-b bg-gray-100" x-data="{ open: false }">
        <div class="block sm:hidden">
            <a
                href="#"
                class="block md:hidden text-base font-bold uppercase text-center flex justify-center items-center"
                @click="open = !open"
            >
                Topics <i :class="open ? 'fa-chevron-down': 'fa-chevron-up'" class="fas ml-2"></i>
            </a>
        </div>
        <div :class="open ? 'block': 'hidden'" class="w-full flex-grow sm:flex sm:items-center justify-between sm:w-auto">
            <div class="w-full  container mx-auto flex flex-col sm:flex-row items-center justify-between text-sm font-bold uppercase mt-0 px-6 py-2">
               
              <div>
                <a href="{{ route('home') }}" class="hover:bg-blue-600 hover:text-white rounded py-2 px-4 mx-2">Home</a>

                @foreach ($categories as $category )
                    
                <a href="{{ route('by-category', $category) }}" class="hover:bg-blue-600 hover:text-white rounded py-2 px-4 mx-2">{{ $category->title }}</a>
                @endforeach
                
                <a href="{{ route('about-us') }}" class="hover:bg-blue-600 hover:text-white rounded py-2 px-4 mx-2">About Us</a>
              </div>

              

              <div class="flex items-center">
                <form method="get" action="{{route('seacrh')}}">
                    <input name="q" value="{{request()->get('q')}}" class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 font-medium" placeholder="Type an hit enter to search anything">
                </form>
                @auth
                <!-- Settings Dropdown -->
                <div class="flex sm:items-center sm:ml-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="hover:bg-blue-600 hover:text-white rounded py-2 px-4 mx-2 flex items-center">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

             @else
                 
             <a href="{{ route('login') }}" class="hover:bg-blue-600 hover:text-white rounded py-2 px-4 mx-2">Login</a>
             <a href="{{ route('register') }}" class="bg-blue-600 text-white rounded py-2 px-4 mx-2">Register</a>
             @endauth
              </div>
            </div>
        </div>
    </nav>


    <div class="container mx-auto py-6">

        {{-- <div class="mx-auto max-w-4xl p-3">
            
        </div> --}}

      {{ $slot }}

   

    </div>

    <footer class="w-full border-t bg-white pb-12">
        <div class="w-full container mx-auto flex flex-col items-center">
           
            <div class="uppercase py-6">&copy; myblog.com</div>
        </div>
    </footer>

@livewireScripts
</body>
</html>