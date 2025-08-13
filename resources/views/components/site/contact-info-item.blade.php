@props(['icon', 'title', 'value'])

<div class="flex items-start">
    <div class="bg-blue-500 p-3 rounded-full mr-4">
        <i class="fas {{ $icon }} text-white"></i>
    </div>
    <div>
        <h4 class="font-semibold text-gray-800">{{ $title }}</h4>
        <p class="text-gray-600">{!! $value !!}</p>
    </div>
</div>
