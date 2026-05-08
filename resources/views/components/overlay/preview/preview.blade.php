@props([
    'title' => 'No Title',
    'tags' => []
])

<div
    class="w-[53rem] mx-auto bg-preview-bg"
    x-data="{
        title: $title,
        tags: $tags
    }"
>
    <div class="flex px-5 py-4 justify-between">
        <h1 class="text-white text-lg font-bold">{{$title}}</h1>
        <div class="flex flex-row gap-[0.75rem]">
            @foreach($tags as $tag)
                @php
                    $loweredTag = strtolower($tag)
                 @endphp

                <x-general.tag
                    :content="$loweredTag"
                    class="px-2 py-1 text-xs font-medium"
                />
            @endforeach
            <x-general.closeButton />
        </div>
    </div>
</div>
