<div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.9.6/lottie.min.js"></script>
    <div class="flex justify-center items-center">
        <div class="" style="margin-top: 6rem">
            <div id="lottie-animation" class=" mx-auto" style="width: 12rem;"></div>
            <div class="text-center mx-5 mb-16">
                <div class="text-3xl font-bold mb-4">Successfully Registered</div>
                <div>Hi {{$checkin['first_name'] ?? ''}}, Thank you for your Registration from {{$checkin['organization']??''}}. </div>
                <br>
                <div>Here is your registration code: </div>
                <div class="font-semibold">{{$checkin['registration_code'] ?? ''}}</div>
            </div>
            <br><br><br>
            <div class="text-center" style="margin-left: 3.5rem; margin-right: 3.5rem;">
                Should you wish to continue? Click 
                {{-- TODO: insert redirect link here: --}}
                <div wire:click="redirect_page_to('')" class="rounded-lg text-white p-5 font-bold" style="background-color: #D97706; padding: 0.5rem; padding: 0.5rem; margin-top: 0.5rem" >
                    Avail Now
                </div>
                <span>Use Registration code to avail</span>
                <div wire:click="redirect_page_to('https://homeful.ph')" class="rounded-lg text-black p-5 font-bold" style="background-color: white; padding: 0.5rem; padding: 0.5rem; margin-top: 0.5rem; border: 1px solid rgb(212 212 212); margin-top:1rem;">
                    Not Now
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
