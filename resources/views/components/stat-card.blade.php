<div class="bg-white shadow-lg rounded-lg p-4 flex items-center border-l-4 border-{{ $color }}-500">
    <div class="flex-grow">
        <p class="text-sm text-{{ $color }}-600 font-semibold">{{ strtoupper($title) }}</p>
        <h3 class="text-2xl font-bold">{{ $count }}</h3>
    </div>
    <div>
        <i class="{{ $icon }} text-{{ $color }}-500 text-3xl"></i>
    </div>
</div>
