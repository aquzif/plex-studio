<div class="fixed right-0 top-0 m-8"
     style="z-index: 9999999;"
    x-data="{toasts: @entangle('toasts')}"
>

    <template x-for="toast in toasts" :key="toast.id">

        <div
            x-data="{ sh: false }"
            x-init="$nextTick(() => { sh = true })"
            x-show="sh && toast.show"
            x-transition:enter="transform ease-in-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-out duration-500"
            x-transition:leave-start="opacity-200"
            x-transition:leave-end="opacity-0"

            class="z-50 relative max-w-sm w-[290px] bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden mb-2
                dark:bg-gray-800 dark:ring-gray-700 dark:text-gray-100 dark:border dark:border-gray-700 dark:ring-opacity-20 dark:ring-gray-700 dark:text-gray-100 dark:border-gray-700 dark:ring-opacity-20
            ">
            <div class="p-2">
                <div class="flex items-start">
                    <div class="flex-shrink-0 pt-[2px] text-gray-600">
                        <template x-if="toast.type === 'success'">
                            <div

                                style="flex-shrink: 0; min-width: 20px; min-height: 20px; display: flex; align-items: center; justify-content: center; text-align: center;">
                                <svg viewBox="0 0 32 32" width="1.25rem" height="1.25rem" style="overflow: visible;">
                                    <circle cx="16" cy="16" r="0" fill="#34C759">
                                        <animate attributeName="opacity" values="0; 1; 1" dur="0.35s" begin="100ms"
                                                 fill="freeze" calcMode="spline" keyTimes="0; 0.6; 1"
                                                 keySplines="0.25 0.71 0.4 0.88; .59 .22 .87 .63"></animate>
                                        <animate attributeName="r" values="0; 17.5; 16" dur="0.35s" begin="100ms"
                                                 fill="freeze" calcMode="spline" keyTimes="0; 0.6; 1"
                                                 keySplines="0.25 0.71 0.4 0.88; .59 .22 .87 .63"></animate>
                                    </circle>
                                    <circle cx="16" cy="16" r="12" opacity="0" fill="#34C759">
                                        <animate attributeName="opacity" values="1; 0" dur="1s" begin="350ms"
                                                 fill="freeze" calcMode="spline" keyTimes="0; 1" keySplines="0.0 0.0 0.2 1"></animate>
                                        <animate attributeName="r" values="12; 26" dur="1s" begin="350ms" fill="freeze"
                                                 calcMode="spline" keyTimes="0; 1" keySplines="0.0 0.0 0.2 1"></animate>
                                    </circle>
                                    <path fill="none" stroke-width="4" stroke-dasharray="22" stroke-dashoffset="22"
                                          stroke-linecap="round" stroke-miterlimit="10"
                                          d="M9.8,17.2l3.8,3.6c0.1,0.1,0.3,0.1,0.4,0l9.6-9.7" stroke="#FCFCFC">
                                        <animate attributeName="stroke-dashoffset" values="22;0" dur="0.25s"
                                                 begin="250ms" fill="freeze" calcMode="spline" keyTimes="0; 1"
                                                 keySplines="0.0, 0.0, 0.58, 1.0"></animate>
                                    </path>
                                </svg>
                            </div>
                        </template>
                        <template x-if="toast.type === 'error'">
                            <div
                                style="flex-shrink: 0; min-width: 20px; min-height: 20px; display: flex; align-items: center; justify-content: center; text-align: center;">
                                <svg viewBox="0 0 32 32" width="1.25rem" height="1.25rem" style="overflow: visible;">
                                    <circle cx="16" cy="16" r="0" fill="#FF3B30">
                                        <animate attributeName="opacity" values="0; 1; 1" dur="0.35s" begin="100ms"
                                                 fill="freeze" calcMode="spline" keyTimes="0; 0.6; 1"
                                                 keySplines="0.25 0.71 0.4 0.88; .59 .22 .87 .63"></animate>
                                        <animate attributeName="r" values="0; 17.5; 16" dur="0.35s" begin="100ms"
                                                 fill="freeze" calcMode="spline" keyTimes="0; 0.6; 1"
                                                 keySplines="0.25 0.71 0.4 0.88; .59 .22 .87 .63"></animate>
                                    </circle>
                                    <circle cx="16" cy="16" r="12" opacity="0" fill="#FF3B30">
                                        <animate attributeName="opacity" values="1; 0" dur="1s" begin="320ms"
                                                 fill="freeze" calcMode="spline" keyTimes="0; 1" keySplines="0.0 0.0 0.2 1"></animate>
                                        <animate attributeName="r" values="12; 26" dur="1s" begin="320ms" fill="freeze"
                                                 calcMode="spline" keyTimes="0; 1" keySplines="0.0 0.0 0.2 1"></animate>
                                    </circle>
                                    <path fill="none" stroke-width="4" stroke-dasharray="9" stroke-dashoffset="9"
                                          stroke-linecap="round" d="M16,7l0,9" stroke="#FFFFFF">
                                        <animate attributeName="stroke-dashoffset" values="9;0" dur="0.2s" begin="250ms"
                                                 fill="freeze" calcMode="spline" keyTimes="0; 1"
                                                 keySplines="0.0, 0.0, 0.58, 1.0"></animate>
                                    </path>
                                    <circle cx="16" cy="23" r="2.5" opacity="0" fill="#FFFFFF">
                                        <animate attributeName="opacity" values="0;1" dur="0.25s" begin="350ms"
                                                 fill="freeze" calcMode="spline" keyTimes="0; 1"
                                                 keySplines="0.0, 0.0, 0.58, 1.0"></animate>
                                    </circle>
                                </svg>
                            </div>
                        </template>
                        <template x-if="toast.type === 'warning'">
                            <div
                                style="flex-shrink: 0; min-width: 20px; min-height: 20px; display: flex; align-items: center; justify-content: center; text-align: center;">
                                <svg viewBox="0 0 32 32" width="1.25rem" height="1.25rem" style="overflow: visible;">
                                    <circle cx="16" cy="16" r="0" fill="#FFCC00">
                                        <animate attributeName="opacity" values="0; 1; 1" dur="0.35s" begin="100ms"
                                                 fill="freeze" calcMode="spline" keyTimes="0; 0.6; 1"
                                                 keySplines="0.25 0.71 0.4 0.88; .59 .22 .87 .63"></animate>
                                        <animate attributeName="r" values="0; 17.5; 16" dur="0.35s" begin="100ms"
                                                 fill="freeze" calcMode="spline" keyTimes="0; 0.6; 1"
                                                 keySplines="0.25 0.71 0.4 0.88; .59 .22 .87 .63"></animate>
                                    </circle>
                                    <circle cx="16" cy="16" r="12" opacity="0" fill="#FFCC00">
                                        <animate attributeName="opacity" values="1; 0" dur="1s" begin="320ms"
                                                 fill="freeze" calcMode="spline" keyTimes="0; 1" keySplines="0.0 0.0 0.2 1"></animate>
                                        <animate attributeName="r" values="12; 26" dur="1s" begin="320ms" fill="freeze"
                                                 calcMode="spline" keyTimes="0; 1" keySplines="0.0 0.0 0.2 1"></animate>
                                    </circle>
                                    <path fill="none" stroke-width="4" stroke-dasharray="9" stroke-dashoffset="9"
                                          stroke-linecap="round" d="M16,7l0,9" stroke="#FFFFFF">
                                        <animate attributeName="stroke-dashoffset" values="9;0" dur="0.2s" begin="250ms"
                                                 fill="freeze" calcMode="spline" keyTimes="0; 1"
                                                 keySplines="0.0, 0.0, 0.58, 1.0"></animate>
                                    </path>
                                    <circle cx="16" cy="23" r="2.5" opacity="0" fill="#FFFFFF">
                                        <animate attributeName="opacity" values="0;1" dur="0.25s" begin="350ms"
                                                 fill="freeze" calcMode="spline" keyTimes="0; 1"
                                                 keySplines="0.0, 0.0, 0.58, 1.0"></animate>
                                    </circle>
                                </svg>
                            </div>
                        </template>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p x-text="toast.title" class="text-sm font-medium text-gray-900
                            dark:text-gray-100
                        "></p>
                        <template x-if="toast.message" >
                            <p
                                x-text="toast.message"
                                class="mt-1 text-sm text-gray-500">
                            </p>
                        </template>
                        <div class="mt-1 flex space-x-7">

                            {{--<button
                                type="button"
                                class="bg-white rounded-md text-sm font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                --}}{{--                    onClick={() => toast.dismiss(t.id)}--}}{{--
                            >
                                Dismiss
                            </button>--}}
                        </div>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button
                            class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500
                                dark:text-gray-300 dark:hover:text-gray-200 dark:focus:ring-blue-500
                            "
                            @click="Livewire.dispatch('toaster-hide-toast',{id:toast.id});"
                        >
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5 rounded dark:bg-gray-700" viewBox="0 0 20 20"
                                 x-bind:fill="darkMode ? 'lightGray' : 'gray'"
                            ><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>

{{--                            <div x-show="!darkMode">--}}
{{--    </div>--}}
{{--    <div x-show="darkMode">--}}
{{--        <svg class="h-5 w-5 rounded bg-gray-700" fill="lightGray"--}}
{{--         viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>--}}
{{--    </div>--}}
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </template>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
</div>




