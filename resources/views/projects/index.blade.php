<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ __('Projects') }}
                    </h2>
                </div>

                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <a
                        class="inline-flex justify-center rounded-lg text-white text-md font-semibold py-3 px-4 bg-dark/0 text-slate-900 ring-1 ring-slate-900/10 hover:bg-white/25 hover:ring-slate-900/15 "
                        href="{{route('projects.create')}}">
                        <span>{{ __('Create new project') }}
                            <span aria-hidden="true" class="hidden text-black/25 sm:inline">→</span>
                        </span>
                    </a>

                </div>
            </div>
        </div>


    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold">List of projects</h1>
                    @if ($projects->count() === 0)
                        <p class="text-gray-500">You have no projects yet. make a new one by click <a href="{{route('projects.create')}}">here</a> </p>

                    @else
                        <div class="flex flex-wrap mt-6">
                        @foreach($projects as $project)
                                <div class="block max-w-sm rounded-lg bg-white p-6 shadow-lg dark:bg-neutral-700 ml-2">
                                    <h6 class="mb-2 text-xl font-medium leading-tight text-center text-neutral-800 dark:text-neutral-50">
                                        {{ $project->name }}
                                    </h6>

                                    <a href="{{ route('projects.show', $project->id) }}"
                                       class="text-primary transition duration-150 ease-in-out hover:text-primary-600 focus:text-primary-600 active:text-primary-700 dark:text-primary-400 dark:hover:text-primary-500 dark:focus:text-primary-500 dark:active:text-primary-600">
                                        View
                                    </a>

                                </div>
                        @endforeach
                        </div>
                    @endif

                    {{$projects->links()}}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
