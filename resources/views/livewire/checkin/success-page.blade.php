<div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.9.6/lottie.min.js"></script>
    <div class="flex justify-center items-center">
        <div class="" style="margin-top: 6rem">
            <div id="lottie-animation" class=" mx-auto" style="width: 12rem;"></div>
            <div class="text-center mx-5 mb-10">
                <div class="text-3xl font-bold mb-4">Successfully Registered</div>
                <div>Hi {{$checkin->contact->first_name ?? ''}}, Thank you for your Registration from {{$checkin->contact->organization->name??''}}. </div>
                <br>
                <div>Here is your registration code: </div>
                <div class="font-semibold text-3xl">{{ $checkin->registration_code ?? ''}}</div>
            </div>
            <div class="flex flex-col items-center space-y-4" style="margin-left: 2rem; margin-right: 2rem;">
                    {{-- <button wire:click="redirect_page_to('{{$checkin->campaign->rider_url}}')" --}}
                            {{-- class="w-full max-w-xs rounded-lg text-black font-bold p-2 mt-1" --}}
                            {{-- style="background-color: white; border: 1px solid rgb(212, 212, 212);"> --}}
                        {{-- Close --}}
                    {{-- </button> --}}
                {{-- <div style="color: rgb(30 64 175); padding: 0.5rem; background-color: rgb(219 234 254); font-size: 0.8rem" class="rounded-lg text-center w-full"> You may use your code to waive the P10,000 Consultation Fee</div> --}}
               <div class="flex flex-col items-center w-full">
                   <span class="text-center mb-1">Should you wish to continue? Click</span>
{{--                   <button wire:click="redirect_page_to('{{ config('kwyc-check.campaign_url') . '?email=' . $checkin->contact->email . '&mobile=' . $checkin->contact->mobile . '&identifier='.$checkin->registration_code.'&code='.$checkin->campaign->project->seller_code.'&choice='.$checkin->campaign->project->product_code }}')"--}}
{{--                           class="w-full max-w-xs rounded-lg text-white font-bold p-2"--}}
{{--                           style="background-color: #D97706;">--}}
{{--                       Avail Now--}}
{{--                   </button>--}}
                   <button wire:click="availed()"
                           class="w-full max-w-xs rounded-lg text-white font-bold p-2"
                           style="background-color: #D97706;">
                       {{$this->checkin->campaign->avail_label}}
                   </button>
                   <span class="text-center">Use the registration code to avail</span>
               </div>
               <div class="flex flex-col items-center w-full">
                   <button wire:click="trip()"
                   class="w-full max-w-xs rounded-lg text-white font-bold p-2 mb-2 items-center justify-center flex gap-2"
                           style="background-color: #16A34A;">
                       <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5 mr-2">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M5 11h14M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                       </svg>
                       {{$this->checkin->campaign->trip_label}}
                   </button>
                   <button wire:click="chat_url()"
                           class="w-full max-w-xs rounded-lg text-white font-bold p-2 mb-2 items-center justify-center flex gap-2"
                           style="background-color: #16A34A;">
                       <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                       </svg>
                       Talk to US
                   </button>
                   {{-- <span class="text-center">Use Registration code to avail</span> --}}
{{--                   <button wire:click="redirect_page_to('{{$checkin->project->rider_url}}')"--}}
{{--                           class="w-full max-w-xs rounded-lg text-black font-bold p-2 mt-1"--}}
{{--                           style="background-color: white; border: 1px solid rgb(212, 212, 212);">--}}
{{--                       Not Now--}}
{{--                   </button>--}}
                   <button wire:click="not_now()"
                           class="w-full max-w-xs rounded-lg text-black font-bold p-2 mt-1"
                           style="background-color: white; border: 1px solid rgb(212, 212, 212);">
                       {{$this->checkin->campaign->undecided_label}}
                   </button>
                   {{-- <button wire:click="redirect_page_to('https://homeful.ph/')" --}}
                           {{-- class="w-full max-w-xs rounded-lg text-black font-bold p-2 mt-1" --}}
                           {{-- style="background-color: white; border: 1px solid rgb(212, 212, 212);"> --}}
                       {{-- Not Now --}}
                   {{-- </button> --}}
               </div>

            </div>


        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lottie.loadAnimation({
                container: document.getElementById('lottie-animation'), // HTML container element
                renderer: 'svg',
                loop: true,
                autoplay: true,
                path: "{{$success_lottiefile}}"
            });
        });
    </script>
</div>
