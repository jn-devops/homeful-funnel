<div class="flex justify-center items-center min-h-screen" x-data="{ showSplash: false }" x-init="setTimeout(() => showSplash = false, 2500)">
    <!-- Splash Screen -->
    <div x-show="showSplash" class="fixed inset-0 flex justify-center items-center bg-white z-50">
{{--        <img class="h-full w-full object-cover" src="{{ $this->campaign->splash_image_url }}" alt="Splash Image">--}}
    </div>

    <div  x-show="!showSplash" x-transition class="w-full max-w-lg bg-white p-4 rounded-lg">
        <div class=" flex justify-center ">
            <img class="h-auto w-full lg:w-full" src="/CompanyLogo.png" alt="CompanyLogo.png">
        </div>
        <div  class="flex justify-center ">
            <h2 class="text-xl font-bold leading-tight text-gray-800  text-center">
                Request Tripping
            </h2>

        </div>
        <form wire:submit="save" class="w-full mt-2">

            {{ $this->form }}
            <div class="flex justify-center mt-2">
                <x-filament::button type="submit" class="mt-4  text-white py-2 px-4 rounded mx-auto w-60">
                    Submit
                </x-filament::button>
            </div>
        </form>


        <x-filament-actions::modals />

    </div>
    <x-filament::modal
        id="success-modal"
        icon="heroicon-o-check-circle"
        icon-color="success"
        sticky-header
        width="md"
        class="rounded-md"
        :autofocus="false"
        x-on:close-modal.window="$wire.closeModal()">
        <x-slot name="heading">
            Checkin Complete
        </x-slot>
        <x-slot name="description">
            Thank you for completing this form!
        </x-slot>
        <div class="px-4 py-2">
            <table class="table-auto w-full">
                <tbody>
                <tr class="border-b">
                    <td class="px-4 py-2">Name</td>
                    <td class="px-4 py-2">{{ $data['first_name'] ?? '' }} {{ $data['last_name'] ?? '' }}</td>
                </tr>
                <tr class="border-b">
                    <td class="px-4 py-2">Mobile Number</td>
                    <td class="px-4 py-2">{{ $data['mobile'] ?? '' }}</td>
                </tr>
                <tr class="border-b">
                    <td class="px-4 py-2">Email</td>
                    <td class="px-4 py-2">{{ $data['email'] ?? '' }}</td>
                </tr>

                </tbody>
            </table>

        </div>
    </x-filament::modal>

    <x-filament::modal
        id="error-modal"
        icon="heroicon-o-check-circle"
        icon-color="danger"
        sticky-header
        width="md"
        class="rounded-md"
        :autofocus="false">
        <x-slot name="heading">
            Error
        </x-slot>
        <x-slot name="description">
            Please check this error message!
        </x-slot>
        <div class="px-4 py-2 ">
            {{$this->error}}
        </div>
    </x-filament::modal>

</div>
