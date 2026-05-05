@php
    $tagStyles = [
        'wirtschaft' => 'text-[#633806] bg-[#FAEEDA]',
        'informatik' => 'text-[#155E75] bg-[#CFFAFE]',
        'mathe'      => 'text-[#3C3489] bg-[#EEEDFE]',
    ];
@endphp

<div 
    x-data="{ tags: {{ Js::from($tags) }} }"
    class="flex flex-wrap gap-2"
>
    @foreach($tags as $tag)
        @php 
            $loweredTag = strtolower($tag);
            $colorStyle = $tagStyles[$loweredTag] ?? 'bg-gray-200 text-gray-800';
        @endphp
        
        <x-general.tag 
            :content="$loweredTag" 
            :active-classes="$colorStyle" 
            class="px-2 py-1 text-card-tags font-medium" 
        />
    @endforeach 
</div>