<template>
    <div class="modal-small modal-window fixed">
        <div class="relative modal-padding bg-white" style="width: 595px">
            <div class="mb-6">
                <h3 class="font-bold text-base text-black">
                    {{ __("Attention!") }}
                </h3>
                <Close :close="showModalDisconnect" />
            </div>

            <div class="modal-body-c mb-8">
                <p class="text-justify text-black text-sm">
                    {{
                        __(
                            "It will disconnect Funnelchat account from your phone. You will need to scan a QR code again"
                        )
                    }}.
                </p>
            </div>

            <div class="modal-footer flex justify-end">
                <button
                    class="cursor-pointer close-button hover:shadow font-bold"
                    @click="showModalDisconnect(false)"
                >
                    {{ __("Cancel") }}
                </button>
                <button
                    class="cursor-pointer btn-default btn-primary rounded shadow-none font-bold"
                    @click="change()"
                >
                    <span v-if="!press_button">{{
                        __("Disconnect your WhatsApp")
                    }}</span>
                    <Loading v-else color="white" />
                </button>
            </div>
        </div>
    </div>
</template>
<script>
import Loading from "./Loading.vue";
import Close from "./svg/close.vue";
export default {
    props: ["showModalDisconnect", "disconnect"],
    components: {
        Close,
        Loading,
    },

    mounted() {
        console.log("KSDJFHSKJDFHSKDJHFSKJDFHKSJDHF");
    },

    data() {
        return {
            press_button: false,
        };
    },
    methods: {
        change() {
            if (!this.press_button) this.disconnect();
            this.press_button = true;
        },
    },
};
</script>
