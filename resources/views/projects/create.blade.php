<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create a new project') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold">New Project</h1>
                    <!-- create a  Form for make new project upload image and fields -->
                    <form class="mt-6 space-y-6" action="{{route('projects.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <x-input-label for="name" :value="__('Project Name')"/>
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus
                                          autocomplete="name"/>
                            <x-input-error class="mt-2" :messages="$errors->get('name')"/>
                        </div>

                        <div>
                            <x-input-label for="first_device_id" :value="__('First device ID')"/>
                            <x-text-input id="first_device_id" name="first_device_id" type="number" class="mt-1 block w-full" :value="old('first_device_id')"
                                          required autofocus/>
                            <x-input-error class="mt-2" :messages="$errors->get('first_device_id')"/>
                        </div>

                        <div>
                            <x-input-label for="csv_file" :value="__('Import CSV file')"/>
                            <x-text-input id="csv_file" name="csv_file" type="file" class="mt-1 block w-full" :value="old('csv_file')" required/>
                            <x-input-error class="mt-2" :messages="$errors->get('csv_file')"/>

                        </div>

                        <div>
                            <x-input-label for="image" :value="__('Upload image of plan')"/>
                            <x-text-input id="image" name="image" type="file" class="mt-1 block w-full" :value="old('image')" required/>
                            <x-input-error class="mt-2" :messages="$errors->get('image')"/>
                        </div>

                        <input type="hidden" value="" name="x1">
                        <input type="hidden" value="" name="y1">
                        <input type="hidden" value="" name="x2">
                        <input type="hidden" value="" name="y2">


                        <div class="flex items-center gap-4">
                            <x-primary-button id="submit" disabled>{{ __('Save') }}</x-primary-button>

                            @if (session('status') === 'project-created')
                                <p
                                    x-data="{ show: true }"
                                    x-show="show"
                                    x-transition
                                    x-init="setTimeout(() => show = false, 2000)"
                                    class="text-sm text-gray-600 dark:text-gray-400"
                                >{{ __('Saved.') }}</p>
                            @endif
                        </div>
                    </form>

                </div>
                <div class="p-6 text-gray-900 dark:text-gray-100 hidden" id="preview_holder">
                    <h1 class="text-2xl font-bold">Preview</h1>
                    <div class="mt-6" style="position: relative">
                        <img id="image_preview"  src="" alt="">
                        <canvas id="canvas" style="position: absolute; z-index: 99; top: 0; left: 0;"></canvas>
                    </div>

                </div>
            </div>
        </div>
    </div>


</x-app-layout>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://www.jqueryscript.net/demo/jQuery-Plugin-For-Selecting-Multiple-Areas-of-An-Image-Select-Areas/jquery.selectareas.js"></script>
<script>
    $(document).ready(function () {
        $('#image').change(function (e) {
            var file = e.target.files[0];
            var reader = new FileReader();
            reader.onload = function (e) {
                var canvas = document.getElementById("canvas");
                var img = document.getElementById("image_preview");
                img.onload = function() {
                    canvas.width = img.width;
                    canvas.height = img.height;

                    var ctx = canvas.getContext("2d");
                    ctx.strokeStyle = "blue";
                    ctx.lineWidth = 10;

                    var startX, startY, endX, endY;

                    $(canvas).mousedown(function(e) {
                        startX = e.offsetX;
                        startY = e.offsetY;
                    });
                    $(canvas).mouseup(function(e) {
                        endX = e.offsetX;
                        endY = e.offsetY;
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        ctx.beginPath();
                        ctx.moveTo(startX, startY);
                        ctx.lineTo(endX, endY);
                        ctx.stroke();
                        $('input[name="x1"]').val(startX);
                        $('input[name="y1"]').val(startY);
                        $('input[name="x2"]').val(endX);
                        $('input[name="y2"]').val(endY);

                        if($('input[name="x1"]').val() !== '' && $('input[name="y1"]').val() !== '' && $('input[name="x2"]').val() !== '' && $('input[name="y2"]').val() !== ''){
                            $('#submit').removeAttr('disabled');
                        }
                    });
                }
                $('#image_preview').attr('src', e.target.result);
                $('#preview_holder').removeClass('hidden');
            };
            reader.readAsDataURL(file);
        });

    });
</script>
