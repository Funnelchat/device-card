<template>
    <div class="modal-small modal-window fixed">
        <div
            class="relative wh-card flex items-center p-16 bg-white wh-card-status"
        >
            <Close :close="showModalConnect" />
            <div class="text-center">
                <h3 class="mb-10 font-semibold text-2xl text-black">
                    {{ __("Connect your WhatsApp") }}
                </h3>

                <div
                    class="flex items-center justify-between"
                    style="width: 750px"
                >
                    <div class="qr-text p-8">
                        <video v-if="show_android_video" width="360" controls>
                            <source
                                src="https://funnelchat-app-elements.s3.amazonaws.com/android_tutorial_vincular_dispositivo.mp4"
                                type="video/mp4"
                            />
                            Your browser does not support the video tag.
                        </video>

                        <video v-if="show_iphone_video" width="360" controls>
                            <source
                                src="https://funnelchat-app-elements.s3.amazonaws.com/iphone_tutorial_vincular_dispositivo.mp4"
                                type="video/mp4"
                            />
                            Your browser does not support the video tag.
                        </video>
                    </div>

                    <img
                        v-if="status.qrCode && !suspended_qr"
                        :src="status.qrCode"
                        alt
                    />

                    <Loading v-else-if="loading" />
                    <div
                        v-else
                        class="py-8 px-16 w-2/6 text-center cursor-pointer hover:shadow-lg"
                        @click="reloadQr"
                    >
                        <img src="/qrcode.png" alt />
                        <span class="text-sm text-gray-800"
                            >{{
                                __(
                                    "Your code has expired, click here to get a new one"
                                )
                            }}.
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Loading from "./Loading.vue";
import Close from "./svg/close.vue";
export default {
    props: [
        "status",
        "suspended_qr",
        "startInterval",
        "showModalConnect",
        "video",
        "loading",
    ],

    components: {
        Loading,
        Close,
    },

    computed: {
        show_android_video() {
            return this.video == "android";
        },
        show_iphone_video() {
            return this.video == "iphone";
        },
    },

    methods: {
        reloadQr() {
            this.startInterval(true, this.status.on || false);
        },

        hideModalDisconnect() {
            this.$emit("hideModalDisconnect");
        },
        disconnect() {
            this.$emit("disconnect");
        },
    },
};
</script>
