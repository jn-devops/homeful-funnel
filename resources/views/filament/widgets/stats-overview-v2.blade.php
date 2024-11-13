{{-- <x-filament-widgets::widget>
    <x-filament::section>
    </x-filament::section>
</x-filament-widgets::widget> --}}
<div class="w-full">
    {{-- @foreach ($checkin as $item)
        <li>{{ $item->campaign_id }}</li>
    @endforeach --}}
    <style>
        @media (min-width: 768px) {
            .custom-height-md {
                height: 5rem; /* equivalent to 96px */
            }
        }

    </style>
    <div class="grid md:grid-flow-col gap-4 auto-cols-fr ">
        <div class=" border border-stone-400 rounded-xl bg-white shadow-md p-4 flex flex-col gap-3">
            <div>
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="20" cy="20" r="20" fill="url(#paint0_linear_1892_34974)"/>
                    <path d="M16.0576 8.64062C14.1008 8.68125 12.924 9.32177 12.3444 10.657C11.7608 12.0017 12.0303 13.8339 12.3647 15.1487L12.3607 15.1501L12.3349 15.1731C12.1481 15.3289 11.9666 15.6633 12.0221 16.1346C12.0777 16.6031 12.1941 16.9539 12.3688 17.18C12.5015 17.352 12.6532 17.4522 12.8224 17.4766C12.8753 17.9031 13.0378 18.4245 13.249 18.8538C13.3303 19.0176 13.6119 19.4712 13.7609 19.7096V21.6406C13.4427 22.0861 12.9023 22.575 12.5462 22.7537C12.1643 22.946 11.7499 23.1072 11.3098 23.2792C11.0201 23.3916 10.7289 23.5053 10.4432 23.6299C9.53995 24.0199 8.89807 24.4289 8.42005 24.9164C7.97318 25.3714 7.68203 25.9685 7.52901 26.7404L7.51953 27.3606H17.7841L17.8247 27.1575L17.9507 26.5346C18.1132 25.7126 18.4422 25.0626 18.9541 24.548C19.4335 24.0673 20.0469 23.6746 20.8865 23.3157C20.9271 23.2981 20.9678 23.2846 21.0084 23.267C21.007 23.267 19.7192 22.7808 19.6678 22.7551C19.3116 22.575 18.7632 22.0875 18.4395 21.6406V19.7096C18.5885 19.4712 18.8702 19.0162 18.9514 18.851C19.1667 18.4042 19.3224 17.8964 19.3766 17.4766C19.5459 17.4522 19.6989 17.3533 19.8316 17.1814C20.0063 16.9552 20.1228 16.6031 20.1783 16.1359C20.2297 15.7148 20.093 15.4426 19.9792 15.295L19.8424 15.1528C19.9548 14.7994 20.3841 13.3558 20.2798 12.0125C20.2284 11.3381 20.0429 10.7613 19.7449 10.3455C19.3793 9.83635 18.8661 9.53979 18.1755 9.43687C17.8789 8.94667 17.0935 8.64469 16.0738 8.64062H16.0576ZM25.687 12.8006C24.1406 12.8318 23.1696 13.3599 22.7065 14.4148C22.261 15.4277 22.433 16.7778 22.6794 17.7907L22.6117 17.9532C22.5169 18.0832 22.4113 18.3067 22.4424 18.6357C22.4506 19.3765 22.7999 19.7881 23.171 19.9682C23.2766 20.4462 23.519 20.8674 23.6409 21.1206V22.1024C23.6409 22.6156 23.4648 23.0815 23.1453 23.4173C23.0288 23.5405 22.9516 23.5879 22.9178 23.6055C22.6266 23.7491 22.3097 23.8723 21.9726 24.0023C21.7464 24.089 21.5189 24.1756 21.2968 24.2718C20.5832 24.5765 20.074 24.8974 19.6921 25.2806C19.3292 25.6462 19.0922 26.1229 18.969 26.7377L18.8458 27.3606H32.5933L32.4701 26.7391C32.3482 26.127 32.1112 25.6503 31.7469 25.282C31.3651 24.896 30.8599 24.5751 30.1544 24.2718C29.931 24.1756 29.7008 24.0863 29.4719 23.9982C29.1374 23.8696 28.8192 23.7477 28.5321 23.6055C28.4969 23.5866 28.4197 23.5392 28.3006 23.4132C27.9769 23.076 27.7995 22.6048 27.7995 22.0889V21.1206C27.9201 20.8674 28.1611 20.4462 28.2681 19.9682C28.6391 19.7881 28.9871 19.3765 28.9966 18.6371C29.0318 18.2755 28.9032 18.0426 28.8003 17.918L28.7596 17.8462V17.8449C28.8761 17.4617 29.1483 16.4474 29.0738 15.4914C29.0305 14.9538 28.8788 14.4906 28.6351 14.1534C28.3412 13.7499 27.9349 13.5089 27.3946 13.4195C27.1279 13.039 26.4995 12.8033 25.7006 12.8006H25.687Z" fill="white"/>
                    <defs>
                    <linearGradient id="paint0_linear_1892_34974" x1="36.911" y1="2.31446e-05" x2="-0.840746" y2="1.25295" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#CC035C"/>
                    <stop offset="1" stop-color="#FCB115"/>
                    </linearGradient>
                    </defs>
                </svg>
            </div>
            <div class="text-stone-800 custom-height-md" style="color:#737373; font-size: 1rem;">
                Total Accounts
            </div>
            <div class="text-3xl text-black font-bold">
                {{$data['total_accounts']}} 
            </div>
        </div>
        <div class=" border border-stone-400 rounded-xl bg-white shadow-md p-4 flex flex-col gap-3">
            <div>
                <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="20.3333" cy="20" r="20" fill="#4CAF50"/>
                    <path d="M27.4708 26.1645C26.7562 25.2348 24.8185 24.2249 22.765 23.688C22.0294 24.3187 21.077 24.7016 20.0333 24.7016C18.9888 24.7016 18.0363 24.3178 17.3008 23.688C15.2464 24.2249 13.3095 25.2357 12.594 26.1654C11.9551 26.9966 12.5639 28.2016 13.6139 28.2016H26.4518C27.5009 28.2016 28.1107 26.9966 27.4708 26.1645ZM24.2333 16.3016C24.2333 13.9819 22.353 12.1016 20.0333 12.1016C17.7137 12.1016 15.8333 13.9819 15.8333 16.3016C15.8333 16.4201 15.8333 16.8831 15.8333 17.0016C15.8333 19.3212 17.7137 21.2016 20.0333 21.2016C22.353 21.2016 24.2333 19.3212 24.2333 17.0016C24.2333 16.8831 24.2333 16.4201 24.2333 16.3016Z" fill="white"/>
                    </svg>
            </div>
            <div class="text-stone-800 custom-height-md" style="color:#737373; font-size: 1rem;">
                Registered <br> <span class="" style="font-size: 0.75rem;">(Number of Prospect Pre-Registered)</span>
            </div>
            <div class="text-3xl text-black font-bold">
                {{$data['registered']}} <sup class="text-sm" style="font-weight: 400; color: red;  position: relative; top: -1em;">{{$data['registered_percent']}}%</sup>
            </div>
        </div>
        <div class=" border border-stone-400 rounded-xl bg-white shadow-md p-4 flex flex-col gap-3">
            <div>
                <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="20.6667" cy="20" r="20" fill="#FCB115"/>
                    <g clip-path="url(#clip0_2106_38376)">
                        <path d="M14.4286 11V12.2H12V29H29V12.2H26.5714V11H24.1429V12.2H16.8571V11H14.4286ZM14.4286 17H26.5714V26.6H14.4286V17ZM20.5 20.6V24.2H24.1429V20.6H20.5Z" fill="white"/>
                    </g>
                    <defs>
                        <clipPath id="clip0_2106_38376">
                            <rect width="17" height="18" fill="white" transform="translate(12 11)"/>
                        </clipPath>
                    </defs>
                </svg>
            </div>
            <div class="text-stone-800 custom-height-md" style="color:#737373; font-size: 1rem;">
                For Tripping <br> <span class="" style="font-size: 0.75rem;">(Prospect Scheduled Tripping)</span>
            </div>
            <div class="text-3xl text-black font-bold" >
                {{$data['for_tripping']}} <sup class="text-sm" style="font-weight: 400; color: red;  position: relative; top: -1em;">{{$data['for_tripping_percent']}}%</sup>
                
            </div>
        </div>
        <div class=" border border-stone-400 rounded-xl bg-white shadow-md p-4 flex flex-col gap-3">
            <div>
                <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="20.3333" cy="20" r="20" fill="#AE3CD8"/>
                    <g clip-path="url(#clip0_2043_37295)">
                        <path d="M17.0048 11.4257C16.4409 12.3177 15.994 13.5029 15.712 14.8906H17.0604C17.6055 12.4656 18.6591 10.9531 19.6332 10.9531C20.6074 10.9531 21.6609 12.4656 22.2061 14.8906H23.5545C23.2725 13.5029 22.8256 12.3177 22.2617 11.4257C23.9741 12.0623 25.3917 13.3055 26.2581 14.8906H27.7219C26.3401 11.7999 23.2324 9.64062 19.6332 9.64062C16.0341 9.64062 12.9263 11.7999 11.548 14.8906H13.0084C13.8748 13.3055 15.2924 12.0623 17.0048 11.4257ZM22.2617 25.5743C22.8256 24.6823 23.2725 23.4971 23.5545 22.1094H22.2061C21.6609 24.5344 20.6074 26.0469 19.6332 26.0469C18.6591 26.0469 17.6055 24.5344 17.0604 22.1094H15.712C15.994 23.4971 16.4409 24.6823 17.0048 25.5743C15.2924 24.9377 13.8748 23.6945 13.0084 22.1094H11.548C12.9263 25.2001 16.0341 27.3594 19.6332 27.3594C23.2324 27.3594 26.3401 25.2001 27.7219 22.1094H26.2581C25.3917 23.6945 23.9741 24.9377 22.2617 25.5743ZM16.035 15.8955H16.2016L16.1623 16.0579L14.9634 21.0037L14.9395 21.1045H13.6031L13.5783 21.0037L13.0092 18.6359L12.4282 21.0045L12.4034 21.1045H11.067L11.043 21.0037L9.86469 16.057L9.82623 15.8955H11.0558L11.0789 15.9989L11.7565 19.1067L12.441 15.998L12.4641 15.8955H13.5638L13.5869 15.998L14.2764 19.1058L14.9489 15.9989L14.972 15.8955H16.035ZM21.696 15.8955H21.59L21.5678 15.9989L20.8953 19.1067L20.2049 15.998L20.1818 15.8955H19.0829L19.0599 15.998L18.3763 19.1067L17.697 15.9989L17.6747 15.8955H16.446L16.4844 16.057L17.6619 21.0037L17.6859 21.1045H19.0223L19.0471 21.0045L19.6281 18.6359L20.1972 21.0037L20.222 21.1036H21.5584L21.5823 21.0037L22.7812 16.057L22.8205 15.8947H21.696V15.8955ZM29.2728 15.8955H28.2098L28.1867 15.9989L27.5142 19.1067L26.8238 15.998L26.8007 15.8955H25.7018L25.6788 15.998L24.9952 19.1067L24.3159 15.9989L24.2936 15.8955H23.064L23.1025 16.057L24.2808 21.0037L24.3047 21.1045H25.6412L25.666 21.0045L26.247 18.6359L26.8161 21.0037L26.8409 21.1036H28.1773L28.2012 21.0037L29.4001 16.057L29.4394 15.8947H29.2728V15.8955Z" fill="white"/>
                    </g>
                    <defs>
                        <clipPath id="clip0_2043_37295">
                            <rect width="21" height="21" fill="white" transform="translate(9.13324 8)"/>
                        </clipPath>
                    </defs>
                </svg>
            </div>
            <div class="text-stone-800 custom-height-md" style="color:#737373; font-size: 1rem;">
                Availed <br> <span class="" style="font-size: 0.75rem;">(Reach the Homeful Consultation)</span>
            </div>
            <div class="text-3xl text-black font-bold">
                {{$data['availed']}} <sup class="text-sm" style="font-weight: 400; color: red;  position: relative; top: -1em;">{{$data['availed_percent']}}%</sup>
                
            </div>
        </div>
        <div class=" border border-stone-400 rounded-xl bg-white shadow-md p-4 flex flex-col gap-3">
            <div>
                <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="20.6666" cy="20" r="20" fill="#E17055"/>
                    <g clip-path="url(#clip0_2043_37307)">
                        <path d="M13.5664 10.832C12.553 10.832 11.733 11.652 11.733 12.6654V27.332C11.733 28.3454 12.553 29.1654 13.5664 29.1654H19.693C19.4221 28.5948 19.2323 27.9801 19.1392 27.332H19.1344C19.0902 27.0324 19.0664 26.7269 19.0664 26.4154C19.0664 23.1832 21.4583 20.5179 24.5664 20.0715C24.866 20.0285 25.1715 19.9987 25.483 19.9987C25.7946 19.9987 26.1001 20.0285 26.3997 20.0715V17.0911C26.3997 16.6054 26.2063 16.1387 25.8626 15.7949L21.4368 11.3691C21.0931 11.0254 20.6264 10.832 20.1406 10.832H13.5664ZM19.983 12.1653L25.0247 17.2487H20.8997C20.3936 17.2487 19.983 16.8381 19.983 16.332V12.1653ZM25.483 21.832C22.9527 21.832 20.8997 23.885 20.8997 26.4154C20.8997 28.9457 22.9527 30.9987 25.483 30.9987C28.0134 30.9987 30.0664 28.9457 30.0664 26.4154C30.0664 23.885 28.0134 21.832 25.483 21.832ZM27.3629 24.6381C27.5945 24.6381 27.826 24.7253 28.0039 24.9019C28.3584 25.2576 28.3584 25.8317 28.0039 26.1862L25.6656 28.5244C25.3112 28.8789 24.7382 28.8789 24.3837 28.5244L22.9622 27.1029C22.6077 26.7484 22.6077 26.1743 22.9622 25.8186C23.3167 25.4641 23.8908 25.4641 24.2465 25.8186L25.0247 26.598L26.7196 24.9019C26.8974 24.7253 27.1314 24.6381 27.3629 24.6381Z" fill="white"/>
                    </g>
                    <defs>
                        <clipPath id="clip0_2043_37307">
                            <rect width="22" height="22" fill="white" transform="translate(8.06641 9)"/>
                        </clipPath>
                    </defs>
                </svg>
            </div>
            <div class="text-stone-800 custom-height-md" style="color:#737373; font-size: 1rem;">
                Consulted <br> <span class="" style="font-size: 0.75rem;">(Complete the Home Loan Consultation)</span>
            </div>
            <div class="text-3xl text-black font-bold">
                {{$data['consulted']}} <sup class="text-sm" style="font-weight: 400; color: red;  position: relative; top: -1em;">{{$data['consulted_percent']}}%</sup>
                
            </div>
        </div>
        <div class=" border border-stone-400 rounded-xl bg-white shadow-md p-4 flex flex-col gap-3">
            <div>
                <svg width="41" height="40" viewBox="0 0 41 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="20.6667" cy="20" r="20" fill="#1868FF"/>
                    <path d="M20.6666 11.7188C16.0935 11.7188 12.3866 15.4256 12.3866 19.9987C12.3866 24.5719 16.0935 28.2787 20.6666 28.2787C25.2398 28.2787 28.9466 24.5719 28.9466 19.9987C28.9466 15.4256 25.2398 11.7188 20.6666 11.7188ZM20.6666 12.4387C24.0838 12.4387 26.9751 14.7197 27.9069 17.8387H27.1523C26.8926 17.0634 26.4988 16.3528 25.9982 15.7294C25.5266 15.9488 25.0035 16.14 24.4298 16.2919C24.5863 16.7812 24.7157 17.2978 24.8066 17.8387H24.0782C23.9919 17.3541 23.8738 16.8928 23.7332 16.4559C22.8876 16.6303 21.9688 16.7297 21.0266 16.7503V17.8387H20.3066V16.7503C19.3663 16.7297 18.4476 16.6294 17.601 16.455C17.4594 16.8928 17.3413 17.3541 17.2551 17.8387H16.5266C16.6194 17.2978 16.7469 16.7812 16.9035 16.2919C16.3307 16.1391 15.8057 15.9488 15.3341 15.7294C14.8344 16.3537 14.4407 17.0634 14.181 17.8387H13.4244C14.3582 14.7197 17.2494 12.4387 20.6666 12.4387ZM20.3066 13.1953C19.8594 13.2825 19.4263 13.53 19.0176 13.9397C18.5544 14.4009 18.1635 15.0319 17.8532 15.7744C18.6219 15.9253 19.4526 16.0106 20.3066 16.0303V13.1953ZM21.0266 13.1953V16.0303C21.8816 16.0106 22.7113 15.9253 23.4801 15.7734C23.1669 15.0234 22.7704 14.3859 22.3026 13.9237C21.8976 13.5244 21.4682 13.2825 21.0266 13.1953ZM18.4007 13.5478C17.4276 13.8909 16.5548 14.4516 15.8394 15.1669C16.2388 15.3384 16.6738 15.4903 17.1463 15.6122C17.4782 14.7928 17.9001 14.0841 18.4007 13.5478ZM22.9288 13.5478C23.4313 14.0841 23.8532 14.7928 24.1869 15.6122C24.6594 15.4903 25.0963 15.3403 25.4938 15.1669C24.7785 14.4516 23.9057 13.8909 22.9288 13.5478ZM13.1066 18.5587H13.8632L14.4144 20.5378L15.081 18.5587H15.9594L16.5998 20.5716L17.1623 18.5587H17.9048L17.0169 21.4387H16.2341L15.5057 19.2853L14.7801 21.4387H13.9794L13.1066 18.5587ZM18.2676 18.5587H19.0232L19.5744 20.5378L20.2438 18.5587H21.1213L21.7626 20.5716L22.3213 18.5587H23.0657L22.1788 21.4387H21.3951L20.6666 19.2853L19.941 21.4387H19.1413L18.2676 18.5587ZM23.4107 18.5587H24.1701L24.7223 20.5378L25.3926 18.5587H26.2748L26.9169 20.5716L27.4794 18.5587H28.2266L27.3351 21.4387H26.5504L25.8191 19.2853L25.0888 21.4387H24.2863L23.4107 18.5587ZM24.0782 22.1587H24.8066C24.7138 22.6997 24.5863 23.2162 24.4298 23.7056C25.0026 23.8584 25.5276 24.0487 25.9991 24.2681C26.4988 23.6438 26.8935 22.9322 27.1523 22.1587H27.9069C26.9751 25.2759 24.0838 27.5587 20.6666 27.5587C17.2494 27.5587 14.3582 25.2778 13.4244 22.1587H14.181C14.4407 22.9341 14.8344 23.6447 15.3351 24.2681C15.8066 24.0487 16.3298 23.8575 16.9035 23.7056C16.7469 23.2162 16.6176 22.6997 16.5266 22.1587H17.2551C17.3413 22.6434 17.4594 23.1047 17.6001 23.5416C18.4457 23.3672 19.3644 23.2678 20.3066 23.2472V22.1587H21.0266V23.2472C21.9669 23.2678 22.8857 23.3681 23.7323 23.5425C23.8738 23.1047 23.9919 22.6434 24.0782 22.1587ZM20.3066 23.9672C19.4563 23.9869 18.6304 24.0722 17.8654 24.2231C18.4588 25.6303 19.3307 26.6053 20.3066 26.8003V23.9672ZM21.0266 23.9672V26.8003C21.4738 26.7141 21.9069 26.4666 22.3157 26.0569C22.7788 25.5966 23.1698 24.9656 23.4801 24.2231C22.7113 24.0722 21.8807 23.9869 21.0266 23.9672ZM24.1869 24.3853C23.8551 25.2047 23.4332 25.9134 22.9326 26.4497C23.9057 26.1066 24.7785 25.5459 25.4938 24.8306C25.0944 24.6591 24.6594 24.5072 24.1869 24.3853ZM17.1351 24.3862C16.6673 24.51 16.2332 24.6572 15.8376 24.8297C16.5491 25.5394 17.4144 26.0962 18.3801 26.4403C17.8879 25.9116 17.4669 25.2103 17.1351 24.3862Z" fill="white"/>
                </svg>
            </div>
            <div class="text-stone-800 custom-height-md" style="color:#737373; font-size: 1rem;">
                Not Now <br> <span class="" style="font-size: 0.75rem;">(Number of Prospect Visited Re-Direction URL)</span>
            </div>
            <div class="text-3xl text-black font-bold" >
                {{$data['not_now']}} <sup class="text-sm" style="font-weight: 400; color: red;  position: relative; top: -1em;">{{$data['not_now_percent']}}%</sup>
                
            </div>
        </div>
    </div>

</div>

