<div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.9.6/lottie.min.js"></script>
    <div class="flex justify-center items-center">
        <div class="" style="margin-top: 6rem">
            <div id="lottie-animation" class=" mx-auto" style="width: 12rem;"></div>
            <div class="text-center mx-5 mb-10">
                <div class="text-3xl font-bold mb-4">The tripping has been scheduled successfully.</div>
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
