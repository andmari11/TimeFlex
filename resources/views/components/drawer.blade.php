<div class="relative z-10" aria-labelledby="slide-over-title" role="dialog" aria-modal="true"  x-data="{ open_drawer: true }" x-show="open_drawer">
    <!--
      Background backdrop, show/hide based on slide-over state.

      Entering: "ease-in-out duration-500"
        From: "opacity-0"
        To: "opacity-100"
      Leaving: "ease-in-out duration-500"
        From: "opacity-100"
        To: "opacity-0"
    -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

    <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                <!--
                  Slide-over panel, show/hide based on slide-over state.

                  Entering: "transform transition ease-in-out duration-500 sm:duration-700"
                    From: "translate-x-full"
                    To: "translate-x-0"
                  Leaving: "transform transition ease-in-out duration-500 sm:duration-700"
                    From: "translate-x-0"
                    To: "translate-x-full"
                -->
                <div class="pointer-events-auto relative w-screen max-w-md">
                    <!--
                      Close button, show/hide based on slide-over state.

                      Entering: "ease-in-out duration-500"
                        From: "opacity-0"
                        To: "opacity-100"
                      Leaving: "ease-in-out duration-500"
                        From: "opacity-100"
                        To: "opacity-0"
                    -->
                    <div class="absolute left-0 top-0 -ml-8 flex pr-2 pt-4 sm:-ml-10 sm:pr-4">
                        <button @click="open_drawer = false" type="button" class="relative rounded-md text-gray-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-white">
                            <span class="absolute -inset-2.5"></span>
                            <span class="sr-only">Close panel</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex h-full flex-col overflow-y-scroll bg-white py-6 shadow-xl">
                        <div class="px-4 sm:px-6">
                            <h2 class="text-base font-semibold leading-6 text-gray-900" id="slide-over-title">Panel title</h2>
                        </div>
                        <div class="relative mt-6 flex-1 px-4 sm:px-6">
                            {{$slot}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
