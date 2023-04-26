<template>
    <div class="modal-small modal-window fixed">
        <div
            class="relative wh-card flex items-center p-16 bg-white wh-card-status"
        >
            <svg
                @click="showModalConnect(false)"
                class="close-icon cursor-pointer"
                width="13"
                height="13"
                viewBox="0 0 13 13"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    d="M11.7031 11.291C12.0371 11.6621 12.0371 12.2188 11.7031 12.5527C11.332 12.9238 10.7754 12.9238 10.4414 12.5527L6.0625 8.13672L1.64648 12.5527C1.27539 12.9238 0.71875 12.9238 0.384766 12.5527C0.0136719 12.2188 0.0136719 11.6621 0.384766 11.291L4.80078 6.875L0.384766 2.45898C0.0136719 2.08789 0.0136719 1.53125 0.384766 1.19727C0.71875 0.826172 1.27539 0.826172 1.60938 1.19727L6.0625 5.65039L10.4785 1.23438C10.8125 0.863281 11.3691 0.863281 11.7031 1.23438C12.0742 1.56836 12.0742 2.125 11.7031 2.49609L7.28711 6.875L11.7031 11.291Z"
                    fill="#1A1A32"
                />
            </svg>
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
