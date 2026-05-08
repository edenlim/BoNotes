<div
    x-data="{ tags: {{ Js::from($tags) }} }"
    class="flex flex-wrap gap-2"
>
    @foreach($tags as $tag)
        @php
            $loweredTag = strtolower($tag);
        @endphp

        <x-general.tag
            :content="$loweredTag"
            class="px-2 py-1 text-card-tags font-medium"
        />
    @endforeach
</div>
