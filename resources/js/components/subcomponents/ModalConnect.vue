<template>
    <div class="modal-small modal-window fixed">
        <div
            class="relative wh-card flex items-center p-16 bg-white wh-card-status overflow-y-auto overflow-x-hidden md:overflow-hide"
        >
            <BackButton :close="showModalConnect" />

            <div class="text-center">
                <div class="md:flex md:items-center md:justify-between">
                    <div class="p-8">
                        <video
                            v-if="show_android_video"
                            class="width-video"
                            autoplay
                            loop
                        >
                            <source
                                src="https://funnelchat-app-elements.s3.amazonaws.com/android_tutorial_vincular_dispositivo.mp4"
                                type="video/mp4"
                            />
                            Your browser does not support the video tag.
                        </video>

                        <video
                            v-if="show_iphone_video"
                            class="width-video"
                            autoplay
                            loop
                        >
                            <source
                                src="https://funnelchat-app-elements.s3.amazonaws.com/iphone_tutorial_vincular_dispositivo.mp4"
                                type="video/mp4"
                            />
                            Your browser does not support the video tag.
                        </video>
                    </div>

                    <div class="width-code">
                        <div class="flex items-center justify-center mb-4">
                            <img
                                v-if="status.qrCode && !suspended_qr"
                                :src="status.qrCode"
                                width="150"
                                alt
                            />

                            <Loading
                                v-else-if="loading"
                                style="height: 150px"
                            />

                            <div
                                v-else
                                @click="reloadQr"
                                class="cursor-pointer hover:shadow-lg"
                            >
                                <img
                                    src="/qrcode.png"
                                    alt
                                    width="150"
                                    height="150"
                                />
                                <span class="text-xx-small text-gray-500">
                                    {{ __("Reload QR code") }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-center mb-4">
                            <SpanNumber
                                :device_number="device_number"
                                :loading="clear_loading"
                            />
                            <ChangeButton :clean="changeNumber" :loading="clear_loading"/>
                        </div>

                        <h3
                            class="mb-4 font-semibold text-lg md:text-2xl text-black"
                        >
                            {{ __("Lee antes de escanear") }}
                        </h3>

                        <p class="text-black pb-3 text-left text-help">
                            {{
                                __(
                                    "Para comenzar a usar Funnelchat, debes vincular tu número de WhatsApp"
                                )
                            }}:
                        </p>

                        <div
                            class="text-black text-left text-help pb-5 ml-2 "
                        >
                            <ol>
                                <li>
                                    1. {{ __("Open WhatsApp on your phone") }}.
                                </li>
                                <li>
                                    2. {{ __("Touch Menu") }}
                                    <span class="inline-block">
                                        <svg
                                            width="4"
                                            height="11"
                                            viewBox="0 0 4 11"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M2 8.46875C2.66016 8.46875 3.21875 9.02734 3.21875 9.6875C3.21875 10.373 2.66016 10.9062 2 10.9062C1.31445 10.9062 0.78125 10.373 0.78125 9.6875C0.78125 9.02734 1.31445 8.46875 2 8.46875ZM2 4.40625C2.66016 4.40625 3.21875 4.96484 3.21875 5.625C3.21875 6.31055 2.66016 6.84375 2 6.84375C1.31445 6.84375 0.78125 6.31055 0.78125 5.625C0.78125 4.96484 1.31445 4.40625 2 4.40625ZM2 2.78125C1.31445 2.78125 0.78125 2.24805 0.78125 1.5625C0.78125 0.902344 1.31445 0.34375 2 0.34375C2.66016 0.34375 3.21875 0.902344 3.21875 1.5625C3.21875 2.24805 2.66016 2.78125 2 2.78125Z"
                                                fill="#1A1A32"
                                            />
                                        </svg>
                                    </span>
                                    {{ __("or Configuration") }}
                                    <span class="inline-block">
                                        <svg
                                            width="12"
                                            height="13"
                                            viewBox="0 0 12 13"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M3.75 6.5C3.75 5.25781 4.73438 4.25 6 4.25C7.24219 4.25 8.25 5.25781 8.25 6.5C8.25 7.74219 7.24219 8.75 6 8.75C4.73438 8.75 3.75 7.74219 3.75 6.5ZM6 5.375C5.36719 5.375 4.875 5.89062 4.875 6.5C4.875 7.13281 5.36719 7.625 6 7.625C6.60938 7.625 7.125 7.13281 7.125 6.5C7.125 5.89062 6.60938 5.375 6 5.375ZM6.86719 0.5C7.38281 0.5 7.82812 0.875 7.94531 1.36719L8.13281 2.14062C8.34375 2.23438 8.53125 2.35156 8.69531 2.46875L9.46875 2.23438C9.96094 2.09375 10.5 2.30469 10.7578 2.75L11.625 4.27344C11.8828 4.71875 11.7891 5.28125 11.4141 5.63281L10.8516 6.17188C10.8516 6.28906 10.875 6.40625 10.875 6.5C10.875 6.61719 10.8516 6.73438 10.8516 6.82812L11.4141 7.39062C11.7891 7.74219 11.8828 8.30469 11.625 8.75L10.7578 10.2734C10.5 10.7188 9.96094 10.9297 9.46875 10.7891L8.69531 10.5547C8.53125 10.6719 8.34375 10.7891 8.13281 10.8828L7.94531 11.6562C7.82812 12.1484 7.38281 12.5 6.86719 12.5H5.10938C4.59375 12.5 4.14844 12.1484 4.03125 11.6562L3.84375 10.8828C3.63281 10.7891 3.44531 10.6719 3.28125 10.5547L2.50781 10.7891C2.01562 10.9297 1.47656 10.7188 1.21875 10.2734L0.351562 8.75C0.09375 8.30469 0.1875 7.74219 0.5625 7.39062L1.125 6.82812C1.125 6.73438 1.125 6.61719 1.125 6.5C1.125 6.40625 1.125 6.28906 1.125 6.17188L0.5625 5.63281C0.1875 5.28125 0.09375 4.71875 0.351562 4.27344L1.21875 2.75C1.47656 2.30469 2.01562 2.09375 2.50781 2.23438L3.28125 2.46875C3.44531 2.35156 3.63281 2.23438 3.84375 2.14062L4.03125 1.36719C4.14844 0.875 4.59375 0.5 5.10938 0.5H6.86719ZM4.80469 2.9375L4.54688 3.05469C4.24219 3.17188 3.96094 3.33594 3.72656 3.52344L3.49219 3.71094L2.20312 3.3125L1.33594 4.83594L2.29688 5.75L2.27344 6.03125C2.25 6.19531 2.25 6.35938 2.25 6.5C2.25 6.66406 2.25 6.82812 2.27344 6.99219L2.29688 7.27344L1.33594 8.1875L2.20312 9.71094L3.49219 9.3125L3.72656 9.5C3.96094 9.6875 4.24219 9.85156 4.54688 9.96875L4.80469 10.0859L5.10938 11.375H6.86719L7.17188 10.0859L7.42969 9.96875C7.73438 9.85156 8.01562 9.6875 8.25 9.5L8.48438 9.3125L9.77344 9.71094L10.6406 8.1875L9.67969 7.27344L9.70312 6.99219C9.72656 6.82812 9.75 6.66406 9.75 6.5C9.75 6.35938 9.72656 6.19531 9.70312 6.03125L9.67969 5.75L10.6406 4.83594L9.77344 3.3125L8.48438 3.71094L8.25 3.52344C8.01562 3.33594 7.73438 3.17188 7.42969 3.05469L7.17188 2.9375L6.86719 1.625H5.10938L4.80469 2.9375Z"
                                                fill="#1A1A32"
                                            />
                                        </svg>
                                    </span>
                                    {{ __("and select WhatsApp Web") }}.
                                </li>
                                <li>
                                    3.
                                    {{ __("Press the PAIR A DEVICE button") }}.
                                </li>
                                <li>
                                    4.
                                    {{
                                        __(
                                            "When the camera is activated point this screen to scan the code"
                                        )
                                    }}.
                                </li>
                            </ol>
                        </div>

                        <a
                            class="cursor-pointer font-bold text-sm md:text-lg link-problems montserrat"
                            @click="openHelp()"
                        >
                            ¿Problemas para conectar?
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Loading from "./Loading.vue";
import Close from "./svg/close.vue";
import BackButton from "./buttons/BackButton.vue";
import ChangeButton from "./buttons/ChangeNumber.vue";
import SpanNumber from "./SpanNumber.vue";

export default {
    props: [
        "status",
        "suspended_qr",
        "startInterval",
        "showModalConnect",
        "video",
        "loading",
        "clear_loading",
        "device_number",
        "changeNumber",
    ],

    components: {
        Loading,
        Close,
        BackButton,

        ChangeButton,
        SpanNumber,
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

        openHelp() {
            $crisp.push([
                "do",
                "message:send",
                [
                    "text",
                    "Hola!, tengo un problema al conectar mi WhatsApp a Funnelchat.",
                ],
            ]);

            $crisp.push(["do", "chat:open"]);
        },

       
    },
};
</script>
