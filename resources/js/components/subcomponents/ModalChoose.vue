<template>
    <div class="modal-small modal-window fixed">
        <div class="relative wh-card flex items-center p-16 bg-white">
            <Close :close="showModalChoose" />

            <div class="text-center">
                <h3 class="mb-12 font-semibold text-2xl text-black">
                    {{ __("Connect your WhatsApp") }}
                </h3>
                <div class="mb-4">
                    <Bot2svg />
                </div>

                <h3 class="mb-4 font-semibold text-2xl text-black">
                    {{ __("What kind of smarthphone do you use?") }}
                </h3>
                <p class="text-lg text-black">
                    {{
                        __(
                            "The type of phone you have determines the next steps of this setup."
                        )
                    }}
                </p>

                <div class="pt-10 text-lg">
                    <button
                        class="cursor-pointer h-12 rounded-full btn-default btn-primary shadow-none mx-3"
                        @click="choose('android')"
                    >
                        <Android />
                        <span class="ml-3">Android</span>
                    </button>
                    <button
                        class="cursor-pointer h-12 rounded-full btn-default btn-primary shadow-none mx-3"
                        @click="choose('iphone')"
                    >
                        <Iphone />
                        <span class="ml-3">Iphone</span>
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
