<div class="flex justify-center items-center min-h-screen" x-data="{ showSplash: true }" x-init="setTimeout(() => showSplash = false, 2500)">
    <!-- Splash Screen -->
    <div x-show="showSplash" class="fixed inset-0 flex justify-center items-center bg-white z-50">
        <img class="h-auto w-40 lg:w-full" src="/CompanyLogo.png" alt="Company Logo">
    </div>

    <div  x-show="!showSplash" x-transition class="w-full max-w-lg bg-white p-4 rounded-lg">
        <div class=" flex justify-center ">
            <img class="h-auto w-40 lg:w-full" src="/CompanyLogo.png" alt="CompanyLogo.png">
        </div>
        <form wire:submit="save" class="w-full">
            <div  class="flex justify-center ">
                <h2 class="text-xl font-bold leading-tight text-gray-800  text-center">
                    {{$this->campaign->name}}
                </h2>

            </div>
            <div  class="flex justify-center ">
                <p class="text-m  leading-tight text-gray-800 mb-4 text-center">
                    {{$this->organization->name}}
                </p>
            </div>

            {{ $this->form }}
            @if($this->isDifferentCompanyBefore)
                <div  class="flex justify-center ">
                    <h5 class="text-xl font-bold leading-tight text-rose-800 mb-4 text-center">
                        You belong to different company before, this will update your record.
                    </h5>
                </div>
            @endif
            <div class="flex justify-center ">
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
                    <td class="px-4 py-2">{{ $data['first_name'] ?? '' }} {{ $data['middle_name'] ?? '' }} {{ $data['last_name'] ?? '' }}</td>
                </tr>
                <tr class="border-b">
                    <td class="px-4 py-2">Mobile Number</td>
                    <td class="px-4 py-2">{{ $data['mobile'] ?? '' }}</td>
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
