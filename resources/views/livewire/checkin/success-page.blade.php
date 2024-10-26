<div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.9.6/lottie.min.js"></script>
    <div class="flex justify-center items-center">
        <div class="" style="margin-top: 6rem">
            <div id="lottie-animation" class=" mx-auto" style="width: 12rem;"></div>
            <div class="text-center mx-5 mb-16">
                <div class="text-3xl font-bold mb-4">Successfully Registered</div>
                <div>Hi {{$checkin->contact->first_name ?? ''}}, Thank you for your Registration from {{$checkin->contact->organization->name??''}}. </div>
                <br>
                <div>Here is your registration code: </div>
                <br>
                <br>
                <div class="font-semibold text-3xl">{{ substr($checkin->contact->id, -12) ?? ''}}</div>
            </div>
            <br><br>
            <div class="flex flex-col items-center space-y-4" style="margin-left: 3.5rem; margin-right: 3.5rem;">
                <div class="flex flex-col items-center mb-4">
                    <span class="text-center mb-1">Should you wish to continue? Click</span>
                    <button wire:click="redirect_page_to('https://homeful.ph')"
                            class="w-full max-w-xs rounded-lg text-white font-bold p-2"
                            style="background-color: #D97706;">
                        Avail Now
                    </button>
                </div>
                <div class="flex flex-col items-center">
                    <span class="text-center">Use Registration code to avail</span>
                    <button wire:click="redirect_page_to('/checkin/{{$checkin->campaign->id}}/{{$checkin->contact->organization->id}}')"
                            class="w-full max-w-xs rounded-lg text-black font-bold p-2 mt-1"
                            style="background-color: white; border: 1px solid rgb(212, 212, 212);">
                        Not Now
                    </button>
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
