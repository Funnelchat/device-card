<template>
    <div class="modal-small modal-window fixed">
        <div class="relative wh-card flex items-center p-6 md:p-16 bg-white">
            <Close :close="showModalChoose" />

            <div class="text-center">
                <h3 class="mb-8 md:mb-12 gilroy-bold text-lg md:text-2xl text-black">
                    {{ __("Connect your WhatsApp") }}
                </h3>
                <div class="mb-4">
                    <Bot2svg />
                </div>

                <h3 class="mb-4 gilroy-bold text-lg md:text-2xl text-black">
                    {{ __("What kind of smarthphone do you use?") }}
                </h3>
                <p class="text-base md:text-lg text-black">
                    {{
                        __(
                            "The type of phone you have determines the next steps of this setup."
                        )
                    }}
                </p>

                <div class="pt-8 md:pt-10 montserrat">
                    <button
                        class="cursor-pointer btn-choose rounded-full btn-default btn-primary btn-choose shadow-none"
                        @click="choose('android')"
                    >
                        <Android />
                        <span class="ml-3 text-base md:text-lg">Android</span>
                    </button>
                    <button
                        class="cursor-pointer rounded-full btn-default btn-primary btn-choose shadow-none ml-3 md:ml-6"
                        @click="choose('iphone')"
                    >
                        <Iphone />
                        <span class="ml-3 text-base md:text-lg">Iphone</span>
                    </button>
                </div>
            </div>
        </div>
        <div
            v-if="loading_disconnect"
            class="modal-loading relative w-full text-center flex justify-center items-center"
        >
            <div class="lds-dual-ring inline-block"></div>
        </div>
    </div>
</template>
<script>
import Android from "./svg/android.vue";
import Iphone from "./svg/iphone.vue";
import Bot2svg from "./svg/bot2.vue";
import Close from "./svg/close.vue";

export default {
    props: [
        "status",
        "suspended_qr",
        "startInterval",
        "showModalChoose",
        "choose",
    ],

    components: {
        Android,
        Iphone,
        Bot2svg,
        Close,
    },

    methods: {},
};
</script>
