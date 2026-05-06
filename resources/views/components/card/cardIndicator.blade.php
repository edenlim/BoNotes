@props([
    'trigger' => '',
    'fileType' => 'unknown',
    'noOfLikes' => 0,
    'noOfDislikes' => 0
])
<div
    x-data="{ fileType: '{{ $fileType }}' }"
    :class="fileType==='.pdf' ? 'bg-pdf-data' : 'bg-txt-data' "
    class="w-64 flex flex-col rounded-t-lg"
    @click="{{$trigger}}"
>
    <div class="mx-auto cursor-pointer">
        @if($fileType === '.pdf')
            <svg width="86" height="81" viewBox="0 0 86 81" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g filter="url(#filter0_d_193_41)">
                <path d="M8 10C8 7.79086 9.79086 6 12 6H74C76.2091 6 78 7.79086 78 10V67C78 69.2091 76.2091 71 74 71H12C9.79086 71 8 69.2091 8 67V10Z" fill="white" shape-rendering="crispEdges"/>
                <path d="M16 16C16 14.8954 16.8954 14 18 14H68C69.1046 14 70 14.8954 70 16C70 17.1046 69.1046 18 68 18H18C16.8954 18 16 17.1046 16 16Z" fill="#85B7EB"/>
                <path d="M16 25C16 23.8954 16.8954 23 18 23H68C69.1046 23 70 23.8954 70 25C70 26.1046 69.1046 27 68 27H18C16.8954 27 16 26.1046 16 25Z" fill="#B5D4F4"/>
                <path d="M16 34C16 32.8954 16.8954 32 18 32H46.4C47.5046 32 48.4 32.8954 48.4 34C48.4 35.1046 47.5046 36 46.4 36H18C16.8954 36 16 35.1046 16 34Z" fill="#B5D4F4"/>
                <path d="M16 43C16 41.8954 16.8954 41 18 41H68C69.1046 41 70 41.8954 70 43C70 44.1046 69.1046 45 68 45H18C16.8954 45 16 44.1046 16 43Z" fill="#B5D4F4"/>
                <path d="M16 52C16 50.8954 16.8954 50 18 50H46.4C47.5046 50 48.4 50.8954 48.4 52C48.4 53.1046 47.5046 54 46.4 54H18C16.8954 54 16 53.1046 16 52Z" fill="#85B7EB"/>
                <path d="M16 61C16 59.8954 16.8954 59 18 59H68C69.1046 59 70 59.8954 70 61C70 62.1046 69.1046 63 68 63H18C16.8954 63 16 62.1046 16 61Z" fill="#B5D4F4"/>
                </g>
                <defs>
                <filter id="filter0_d_193_41" x="0" y="0" width="86" height="81" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                <feOffset dy="2"/>
                <feGaussianBlur stdDeviation="4"/>
                <feComposite in2="hardAlpha" operator="out"/>
                <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.08 0"/>
                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_193_41"/>
                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_193_41" result="shape"/>
                </filter>
                </defs>
            </svg>
        @else
            <svg width="54" height="64" viewBox="0 0 54 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                <mask id="path-1-inside-1_193_63" fill="white">
                <path d="M0 4C0 1.79086 1.79086 0 4 0H50C52.2091 0 54 1.79086 54 4V60C54 62.2091 52.2091 64 50 64H4C1.79086 64 0 62.2091 0 60V4Z"/>
                </mask>
                <path d="M0 4C0 1.79086 1.79086 0 4 0H50C52.2091 0 54 1.79086 54 4V60C54 62.2091 52.2091 64 50 64H4C1.79086 64 0 62.2091 0 60V4Z" fill="white"/>
                <path d="M4 0V0.8H50V0V-0.8H4V0ZM54 4H53.2V60H54H54.8V4H54ZM50 64V63.2H4V64V64.8H50V64ZM0 60H0.8V4H0H-0.8V60H0ZM4 64V63.2C2.23269 63.2 0.8 61.7673 0.8 60H0H-0.8C-0.8 62.651 1.34903 64.8 4 64.8V64ZM54 60H53.2C53.2 61.7673 51.7673 63.2 50 63.2V64V64.8C52.651 64.8 54.8 62.651 54.8 60H54ZM50 0V0.8C51.7673 0.8 53.2 2.23269 53.2 4H54H54.8C54.8 1.34903 52.651 -0.8 50 -0.8V0ZM4 0V-0.8C1.34903 -0.8 -0.8 1.34903 -0.8 4H0H0.8C0.8 2.23269 2.23269 0.8 4 0.8V0Z" fill="#D3D1C7" mask="url(#path-1-inside-1_193_63)"/>
                <path d="M14.2317 27.4328H21.7337V28.6538H14.2317V27.4328ZM17.2567 28.4998H18.7197V34.5498H17.2567V28.4998ZM28.7862 27.4328H30.5682L27.2022 31.3598L27.1582 31.4038L24.6612 34.5498H22.8792L26.3442 30.4248L26.3772 30.3918L28.7862 27.4328ZM24.7712 27.4328L27.1472 30.4138L27.1692 30.4468L30.6012 34.5498H28.7422L26.2782 31.3598L26.2452 31.3378L22.9892 27.4328H24.7712ZM31.7532 27.4328H39.2552V28.6538H31.7532V27.4328ZM34.7782 28.4998H36.2412V34.5498H34.7782V28.4998Z" fill="#888780"/>
                <mask id="path-4-inside-2_193_63" fill="white">
                <path d="M41.1992 0.799805H53.1992V12.7998H41.1992V0.799805Z"/>
                </mask>
                <path d="M53.1992 12.7998V24.7998H65.1992V12.7998H53.1992ZM53.1992 0.799805H41.1992V12.7998H53.1992H65.1992V0.799805H53.1992ZM53.1992 12.7998V0.799805H41.1992V12.7998V24.7998H53.1992V12.7998Z" fill="#D3D1C7" mask="url(#path-4-inside-2_193_63)"/>
            </svg>
        @endif
    </div>

    <x-card.rating :noOfLikes="$noOfLikes" :noOfDislikes="$noOfDislikes" />
</div>
