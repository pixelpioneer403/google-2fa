<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="pt-12 pb-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex justify-between items-center">
                    <span>{{ __('Enable 2FA') }}</span>
                    <span>
                        <form id="enable2fa" action="{{ route('setting.store') }}" method="POST">
                            @csrf
                            <label class="relative inline-flex items-center mr-5 cursor-pointer">
                                <input type="checkbox" id="is_enabled2fa" class="sr-only peer">
                                <div
                                    class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-focus:ring-4 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600">
                                </div>
                            </label>
                        </form>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex flex-col justify-center items-center">
                    <p>{{ __('Open Google Authenticator App and Scan Qr Code') }}</p>
                    <div id="qrcode"></div>
                    <p>{{ __('Also Download Recovery Codes and Keep it in Safe Place') }}</p>
                    <p>{{ __('If You Lost May Not Be Able To Use Your Account Again') }}</p>
                    <button class="bg-purple-800 hover:bg-purple-900 mt-6 mb-4 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                        <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"/></svg>
                        <span>Download</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#is_enabled2fa').change(function() {
                    let switchValue = $(this).prop('checked');
                    $(this).attr('checked', switchValue);

                    let url = $('#enable2fa').attr('action');
                    let method = $('#enable2fa').attr('method');
                    let formData = new FormData($('#enable2fa')[0]);
                    formData.append('is_enabled2fa', switchValue);

                    $.ajax({
                        url: url,
                        type: method,
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            // Handle the success response
                            console.log(response);
                            if (response.qrcode == true) {
                                $('#qrcode').html(response.qr_code_url)
                            }else{
                                $('#qrcode').html('')
                            }
                        },
                        error: function(xhr, status, error) {
                            // Handle the error response
                            console.log(xhr.responseText);
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
