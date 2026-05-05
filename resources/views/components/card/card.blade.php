@props([
    'title' => 'No title',
    'fileType' => 'unknown',
    'noOfLikes' => 42,
    'noOfDislikes' => 24,
    'tags' => ['mathe', 'informatik', 'wirtschaft']
])

<div 
    class="card bg-white w-64 rounded-lg"
>
    <x-card.cardIndicator :fileType="$fileType" :noOfLikes="$noOfLikes" :noOfDislikes="$noOfDislikes" />
    <!-- <div>
        @if($fileType == 'unknown')
            unknown
        @else
            {{$fileType}}
        @endif
    </div> -->
    <div class="flex flex-row justify-between py-2 px-3 ">
        <div>
            <h1 class="text-primary-text font-medium text-card-title mb-1">{{$title}}</h1>
            <x-card.tags :tags="$tags" />
        </div>
        <div class="flex items-end">
                    
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="size-6 bi bi-download fill-current text-secondary-text cursor-pointer">
                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
            </svg>
        </div>
        
    </div>
</div>