<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Projects') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold">Projects {{$project->name}}</h1>

                    <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        <ul
                            class="mb-5 flex list-none flex-col flex-wrap border-b-0 pl-0 md:flex-row"
                            role="tablist"
                            data-te-nav-ref>
                            <li role="presentation">
                                <a
                                    href="#tabs-home"
                                    class="my-2 block border-x-0 border-t-0 border-b-2 border-transparent px-7 pt-4 pb-3.5 text-xs font-medium uppercase leading-tight text-neutral-500 hover:isolate hover:border-transparent hover:bg-neutral-100 focus:isolate focus:border-transparent data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary dark:text-neutral-400 dark:hover:bg-transparent dark:data-[te-nav-active]:border-primary-400 dark:data-[te-nav-active]:text-primary-400"
                                    data-te-toggle="pill"
                                    data-te-target="#tabs-home"
                                    data-te-nav-active
                                    role="tab"
                                    aria-controls="tabs-home"
                                    aria-selected="true"
                                >Heatmap</a>
                            </li>
                            <li role="presentation">
                                <a
                                    href="#tabs-profile"
                                    class="focus:border-transparen my-2 block border-x-0 border-t-0 border-b-2 border-transparent px-7 pt-4 pb-3.5 text-xs font-medium uppercase leading-tight text-neutral-500 hover:isolate hover:border-transparent hover:bg-neutral-100 focus:isolate data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary dark:text-neutral-400 dark:hover:bg-transparent dark:data-[te-nav-active]:border-primary-400 dark:data-[te-nav-active]:text-primary-400"
                                    data-te-toggle="pill"
                                    data-te-target="#tabs-profile"
                                    role="tab"
                                    aria-controls="tabs-profile"
                                    aria-selected="false"
                                >Linear</a>
                            </li>
                        </ul>

                        <div class="mb-6">
                            <div
                                class="hidden opacity-0 opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
                                id="tabs-home"
                                role="tabpanel"
                                aria-labelledby="tabs-home-tab"
                                data-te-tab-active>
                                <div id="heatmap-container" style="position: relative">
                                    <img id="image" class="w-full h-auto" src="{{ asset('storage/images/'. $project->background_image) }}">
                                </div>
                            </div>

                            <div
                                class="hidden opacity-0 transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
                                id="tabs-profile"
                                role="tabpanel"
                                aria-labelledby="tabs-profile-tab">
                                <div id="linear-container" style="position: relative">
                                    <img id="image-linear" class="w-full h-auto" src="{{ asset('storage/images/'. $project->background_image) }}">
                                    <canvas id="linear" style="position: absolute;top: 0;left: 0;z-index: 2;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/heatmap.js/build/heatmap.min.js"></script>

<script>
    var img = document.getElementById('image');

    // Set the canvas width and height to match the image

    // Get the heatmap data from the backend
    var Data = {!! json_encode($data) !!};
    var Scale = {!! json_encode($scale) !!};
    // Prepare the data for the heatmap.js library
    // console.log(heatmapData);
    var heatmapPoints = Data.map(function (point) {
        return {
            x: Math.floor((img.width / 2) - (parseFloat(point.x1)) + (parseFloat(point.x) * parseFloat(Scale))),
            y: Math.floor((img.height / 2) - (parseFloat(point.y1) + (parseFloat(point.y) * parseFloat(Scale)))),
            value: 1 // Use a value of 1 for each point
        };
    });

    // Create a heatmap instance
    var heatmap = h337.create({
        container: document.getElementById('heatmap-container'),
    });

    // Set the data for the heatmap
    heatmap.setData({
        data: heatmapPoints,
        max: Math.max.apply(Math, heatmapPoints.map(function (o) {
            return o.value;
        }))

    });

    // Draw the heatmap
    const canvas = document.getElementById('linear');
    const ctx = canvas.getContext('2d');
    canvas.width = img.width;
    canvas.height = img.height;
    ctx.beginPath();
    ctx.moveTo(Data[0].x1, Data[0].y1);
    Data.forEach(point => {
        ctx.lineTo(
            Math.floor((img.width / 2) - (parseFloat(point.x1)) + (parseFloat(point.x) * parseFloat(Scale))),
            Math.floor((img.height / 2) - (parseFloat(point.y1) + (parseFloat(point.y) * parseFloat(Scale))))
        );
    });
    ctx.strokeStyle = 'red';
    ctx.lineWidth = 5;
    ctx.stroke();
</script>
