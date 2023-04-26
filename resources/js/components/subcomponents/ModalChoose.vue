<template>
    <div class="modal-small modal-window fixed">
        <div
            class="relative wh-card flex items-center p-16 bg-white"
            style="animation: opac 0.8s"
        >
            <svg
                @click="showModalChoose(false)"
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
                        class="cursor-pointer rounded-full btn btn-default btn-primary btn-modal mx-3"
                        @click="choose('android')"
                    >
                        <svg
                            width="23"
                            height="13"
                            viewBox="0 0 23 13"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M16.6562 9.79688C17.1641 9.79688 17.5938 9.40625 17.5938 8.85938C17.5938 8.35156 17.1641 7.92188 16.6562 7.92188C16.1484 7.92188 15.7188 8.35156 15.7188 8.85938C15.7188 9.40625 16.1484 9.79688 16.6562 9.79688ZM6.30469 9.79688C6.8125 9.79688 7.24219 9.40625 7.24219 8.85938C7.24219 8.35156 6.8125 7.92188 6.30469 7.92188C5.79688 7.92188 5.36719 8.35156 5.36719 8.85938C5.36719 9.40625 5.79688 9.79688 6.30469 9.79688ZM17.0078 4.17188C20.2109 5.92969 22.3984 9.17188 22.75 13H0.25C0.5625 9.17188 2.75 5.92969 5.95312 4.17188L4.07812 0.929688C4.03906 0.851562 4.03906 0.773438 4.03906 0.695312C4.03906 0.5 4.19531 0.304688 4.42969 0.304688C4.58594 0.304688 4.70312 0.421875 4.78125 0.539062L6.65625 3.82031C8.14062 3.15625 9.78125 2.80469 11.5 2.80469C13.2188 2.80469 14.8203 3.15625 16.3047 3.82031L18.1797 0.539062C18.2578 0.421875 18.375 0.304688 18.5312 0.304688C18.7656 0.304688 18.9219 0.5 18.9219 0.695312C18.9219 0.773438 18.9219 0.851562 18.8828 0.929688L17.0078 4.17188Z"
                                fill="white"
                            />
                        </svg>
                        <span class="ml-3">Android</span>
                    </button>
                    <button
                        class="cursor-pointer rounded-full btn btn-default btn-primary btn-modal mx-3"
                        @click="choose('iphone')"
                    >
                        <svg
                            width="16"
                            height="18"
                            viewBox="0 0 16 18"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M12.9219 9.50781C12.9219 8.0625 13.5859 7.00781 14.875 6.1875C14.1328 5.13281 13.0391 4.58594 11.5938 4.46875C10.1875 4.35156 8.66406 5.25 8.11719 5.25C7.53125 5.25 6.20312 4.50781 5.14844 4.50781C2.96094 4.54688 0.65625 6.22656 0.65625 9.70312C0.65625 10.7188 0.8125 11.7734 1.20312 12.8672C1.71094 14.3125 3.50781 17.8281 5.38281 17.75C6.35938 17.75 7.0625 17.0469 8.35156 17.0469C9.60156 17.0469 10.2266 17.75 11.3203 17.75C13.2344 17.75 14.875 14.5469 15.3438 13.1016C12.8047 11.8906 12.9219 9.58594 12.9219 9.50781ZM10.7344 3.10156C11.7891 1.85156 11.6719 0.679688 11.6719 0.25C10.7344 0.328125 9.64062 0.914062 9.01562 1.61719C8.3125 2.39844 7.92188 3.375 8 4.42969C9.01562 4.50781 9.95312 4 10.7344 3.10156Z"
                                fill="white"
                            />
                        </svg>

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
import Bot2svg from "./bot2Svg.vue";
export default {
    props: [
        "status",
        "suspended_qr",
        "startInterval",
        "showModalChoose",
        "choose",
    ],

    components: {
        Bot2svg,
    },

    methods: {
        hideModalDisconnect() {
            this.$emit("hideModalDisconnect");
        },
        disconnect() {
            this.$emit("disconnect");
        },
    },
};
</script>
