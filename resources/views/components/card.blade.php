@props([
    'title' => 'No title',
    'fileType' => 'unknown'
])

<div class="card">
    <div>
        @if($fileType == 'unknown')
            unknown
        @else
            {{$fileType}}
        @endif
    </div>
    <div>
        <h1 class="text-3xl font-bold text-like-green">{{$title}}</h1>
        <h1 class="text-3xl font-bold text-red-500">{{$title}}</h1>
        <x-like />
        <div>Tags</div>
    </div>
</div>