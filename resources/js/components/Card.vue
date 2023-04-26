<template>
    <card class="flex flex-col items-center justify-center min-height-card">
        <div class="wpp-container w-full relative text-90 bg-white">
            <div class="wpp-content w-full">
                <div class="container m-auto">
                    <div class="m-1 grid-rows-1 grid grid-flow-col gap-1">
                        <div
                            class="row-span-1 col-span-3 p-2 flex justify-between items-center"
                        >
                            <div class="flex justify-center items-center">
                                <div
                                    class="flex justify-center items-center rounded-full relative"
                                >
                                    <img
                                        v-if="info_user?.imgUrl"
                                        :src="info_user.imgUrl"
                                        class="rounded-full"
                                        width="50"
                                        height="50"
                                    />
                                    <Bot1svg v-else />
                                    <div
                                        v-if="
                                            status.accountStatus ==
                                            'authenticated'
                                        "
                                        class="absolute right-0 bottom-0 rounded-full p-1 bg-white"
                                    >
                                        <div
                                            class="w-2 h-2 rounded-full dot"
                                        ></div>
                                    </div>
                                </div>

                                <div class="ml-4">
                                    <p
                                        class="text-90 text-gray-800 text-base font-semibold"
                                    >
                                        {{ get_formated_number }}
                                    </p>
                                    <div class="my-3">
                                        <span
                                            class="text-sm text-gray-600 mr-1"
                                            >{{ __("status") }}</span
                                        >
                                        <span
                                            class="auth-label"
                                            v-bind:class="[
                                                {
                                                    'auth-blue':
                                                        status.accountStatus ==
                                                        'got qr code',
                                                    'auth-green':
                                                        status.accountStatus ==
                                                        'authenticated',
                                                    'auth-yellow':
                                                        loading ||
                                                        status.accountStatus ==
                                                            'loading' ||
                                                        !status.accountStatus,
                                                    'auth-red':
                                                        status.accountStatus ==
                                                        'Contact technical support',
                                                },

                                                'auth-label',
                                            ]"
                                            >{{ __(auth_state) }}

                                            <Loading
                                                :color="'#b98201'"
                                                :width="'25px'"
                                                v-if="
                                                    loading ||
                                                    status.accountStatus ==
                                                        'loading'
                                                "
                                            />
                                        </span>
                                    </div>
                                    <div v-if="show_queue_label">
                                        <div
                                            class="auth-label auth-red flex justify-center items-center"
                                        >
                                            <svg
                                                width="15"
                                                height="12"
                                                viewBox="0 0 15 12"
                                                fill="none"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    d="M13.8477 10.2129L8.43945 0.970703C8.21094 0.615234 7.85547 0.4375 7.5 0.4375C7.11914 0.4375 6.76367 0.615234 6.53516 0.970703L1.10156 10.2129C0.720703 10.9238 1.22852 11.8125 2.06641 11.8125H12.9082C13.7461 11.8125 14.2539 10.9238 13.8477 10.2129ZM2.32031 10.5938L7.47461 1.7832L12.6543 10.5938H2.32031ZM7.5 8.20703C7.04297 8.20703 6.6875 8.5625 6.6875 8.99414C6.6875 9.42578 7.04297 9.78125 7.5 9.78125C7.93164 9.78125 8.28711 9.42578 8.28711 8.99414C8.28711 8.5625 7.93164 8.20703 7.5 8.20703ZM6.89062 4.29688V6.73438C6.89062 7.08984 7.14453 7.34375 7.5 7.34375C7.83008 7.34375 8.10938 7.08984 8.10938 6.73438V4.29688C8.10938 3.9668 7.83008 3.6875 7.5 3.6875C7.14453 3.6875 6.89062 3.9668 6.89062 4.29688Z"
                                                    fill="#ED3833"
                                                />
                                            </svg>

                                            <span class="mx-15"
                                                >{{ __("You have") }}
                                                <span class="font-bold">{{
                                                    cant_queue_messages
                                                }}</span>
                                                {{ __("queue messages") }}</span
                                            >

                                            <a
                                                @click="showModalMessagesQueue"
                                                class="font-bold cursor-pointer"
                                            >
                                                {{ __("delete messages") }}</a
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-center items-center">
                                <disconect-button
                                    v-if="shown_buttons"
                                    :showModalDisconnect="showModalDisconnect"
                                />
                                <connect-button
                                    v-if="shown_connect_button"
                                    :showModalChoose="showModalChoose"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <Transition name="fade" mode="out-in">
                <!-- Modal for queue history -->
                <ModalMessagesQueue
                    v-if="show_modal_messages_queue"
                    :cant_queue_messages="cant_queue_messages"
                    :showModalMessagesQueue="showModalMessagesQueue"
                    :clearMessagesQueue="clearMessagesQueue"
                />

                <!-- Modal for disconnect-->
                <ModalDisconnect
                    v-if="show_modal_disconnect"
                    :showModalDisconnect="showModalDisconnect"
                    :disconnect="disconnect"
                />

                <!-- Modal for connect-->
                <ModalConnect
                    v-if="show_modal_connect"
                    :status="status"
                    :startInterval="updateStatus"
                    :suspended_qr="suspended_qr"
                    :showModalConnect="showModalConnect"
                    :loading="loading"
                    :video="video"
                />

                <!-- Modal for choose-->
                <ModalChoose
                    v-if="show_modal_choose"
                    :status="status"
                    :startInterval="updateStatus"
                    :suspended_qr="suspended_qr"
                    :showModalChoose="showModalChoose"
                    :choose="choose"
                />
            </Transition>
        </div>
    </card>
</template>

<script>
//Components status
import Bot1svg from "./subcomponents/bot1Svg.vue";
import Bot2svg from "./subcomponents/bot2Svg.vue";
import ConnectButton from "./subcomponents/ConnectButton.vue";
import DisconectButton from "./subcomponents/DisconectButton.vue";
import Loading from "./subcomponents/Loading.vue";

//Modal
import ModalMessagesQueue from "./subcomponents/ModalMessagesQueue";
import ModalDisconnect from "./subcomponents/ModalDisconnect";
import ModalConnect from "./subcomponents/ModalConnect";
import ModalChoose from "./subcomponents/ModalChoose";

export default {
    props: ["card", "resource"],

    mounted() {
        this.supplier = this.card.supplier;
        this.instance_code = this.card.instance_code;
        this.device_alias = this.card.device_alias;
        this.device_color = this.card.device_color || "#38c172";
        this.token = this.card.token;
        this.device_id = this.card.device_id;
        this.device_number = this.card.device_number;
        this.admin_view = this.card.admin_view;
        this.status = {};
        this.suspended_qr = false;
        this.official_status = this.card.official_status;
        this.wapi_url = `${this.card.wapi_url}/api/instance`;
        this.webhook_url = `${this.card.webhook_url}/api/instances`;
        this.getStatus();
        this.loadUserInfo();
        this.getQueueMessages();
    },

    data() {
        return {
            video: "android",
            supplier: "",
            admin_view: false,
            qr_call_number: 0,
            suspended_qr: false,
            instance_code: "",
            token: "",
            device_id: "",
            device_number: "",
            device_alias: "",
            device_color: "",
            official_status: "",
            status: {
                accountStatus: "loading",
            },
            loading: true,
            wapi_url: "",
            webhook_url: "",
            interval_status: null,
            info_user: null,
            show_body: false,
            is_expired: false,
            show_modal_disconnect: false,
            show_modal_messages_queue: false,
            show_modal_connect: false,
            show_modal_choose: false,
            cant_queue: 0,
        };
    },

    components: {
        Bot1svg,
        Bot2svg,
        ConnectButton,
        DisconectButton,
        ModalMessagesQueue,
        ModalDisconnect,
        ModalConnect,
        ModalChoose,
        Loading,
    },

    computed: {
        show_alias() {
            return this.device_alias && this.device_alias !== "";
        },

        show_number() {
            return this.device_number && this.device_number !== "";
        },

        get_formated_number() {
            if (this.show_alias && this.show_number) {
                return `+${this.device_number} (${this.device_alias})`;
            } else if (this.show_number) {
                return `+${this.device_number}`;
            } else {
                return this.__("No registered number");
            }
        },

        auth_state() {
            if (this.status.accountStatus) {
                switch (this.status.accountStatus) {
                    case "init":
                        return "Initialize";
                        break;
                    case "got qr code":
                        return "Scan QR Code";
                        break;
                    default:
                        return this.status.accountStatus;
                        break;
                }
                return this.status.accountStatus;
            } else return "loading";
        },

        cant_queue_messages() {
            return this.cant_queue;
        },

        show_queue_label() {
            return this.cant_queue > 0;
        },

        shown_buttons() {
            return this.status.accountStatus == "authenticated";
        },

        shown_connect_button() {
            return this.status.accountStatus == "got qr code";
        },
    },

    methods: {
        choose(choice) {
            this.video = choice;
            this.updateStatus(true, this.status.on || false);
            this.showModalChoose(false);
            this.showModalConnect(true);
        },

        showAlert(msg, type) {
            Nova[type](`${this.__(msg)}`);
        },

        showModalMessagesQueue(value) {
            this.show_modal_messages_queue = value;
        },

        showModalDisconnect(value) {
            this.show_modal_disconnect = value;
        },

        showModalConnect(value) {
            if (!value) {
                clearInterval(this.interval_status);
            }
            this.show_modal_connect = value;
        },

        showModalChoose(value) {
            if (!value) clearInterval(this.interval_status);
            this.show_modal_choose = value;
        },

        getQueueMessages() {
            const url = `${this.wapi_url}${this.instance_code}/showMessagesQueue?token=${this.token}`;
            Nova.request()
                .get(url)
                .then((response) => {
                    this.cant_queue = response.data.totalMessages;
                })
                .catch((err) => {
                    console.log(err.toString());
                });
        },

        clearMessagesQueue() {
            const url = `${this.wapi_url}${this.instance_code}/clearMessagesQueue?token=${this.token}`;
            Nova.request()
                .post(url)
                .then((response) => {
                    this.showModalMessagesQueue();
                    setTimeout(() => {
                        this.show_modal_messages_queue = false;
                    }, 2000);
                })
                .catch((err) => {
                    console.log(err.toString());
                });
        },

        async disconnect() {
            const url = `${this.wapi_url}${this.instance_code}/logout?token=${this.token}`;
            await Nova.request()
                .get(url)
                .then((response) => {
                    this.show_modal_disconnect = false;
                    if (response.status == 200) {
                        this.showAlert("Disconnected successfully", "success");
                        this.loading = true;
                        this.status = { accountStatus: "loading" };
                        this.getStatus();
                    } else {
                        this.showAlert("There was an error", "error");
                    }
                })
                .catch((err) => {
                    this.show_modal_disconnect = false;
                    console.log(err.toString());
                });
        },

        loadUserInfo() {
            const url = `${this.wapi_url}${this.instance_code}/me?token=${this.token}`;
            Nova.request()
                .get(url)
                .then((response) => {
                    if (response.status == 200) {
                        this.info_user = response.data;

                        //Comprobación de que sea el mismo número que el guardado en la base de datos
                        if (
                            this.device_number &&
                            this.device_number != this.info_user.phone
                        ) {
                            this.$toasted.show(
                                `${this.__("wrong_number", {
                                    number: this.info_user.phone,
                                })}`,
                                {
                                    duration: 50000,
                                    type: "warning",
                                }
                            );
                        }
                    }
                })
                .catch((err) => {
                    console.log(err.toString());
                });
        },

        async getStatus() {
            this.loading = true;
            await Nova.request()

                .get(
                    `${this.webhook_url}${this.instance_code}/status?token=${this.token}`,
                    {
                        params: { on: false },
                    }
                )
                .then(({ data }) => {
                    if (
                        data.error ||
                        (data.message && data.message.includes("is stopped"))
                    ) {
                        this.loading = false;

                        this.status = {
                            accountStatus: "Contact technical support",
                            statusData: {
                                msg: "Please contact the customer service staff so that they can restart your instance and we apologize for the inconvenience",
                            },
                        };
                    } else if (data.accountStatus == "Expired plan") {
                        this.loading = false;
                        this.is_expired = true;

                        this.status = {
                            accountStatus: data.accountStatus,
                            statusData: {
                                msg: "Your plan has expired, make a payment to continue using the service",
                            },
                        };
                    } else {
                        if (
                            data.accountStatus ==
                            "To continue sending a message, you must subscribe to this instance again"
                        ) {
                            this.suspended_qr = true;
                            data.accountStatus = "got qr code";
                            data.on = true;
                        } else if (data.accountStatus == "got qr code") {
                            this.suspended_qr = true;
                        } else {
                            this.showModalConnect(false);
                        }

                        if (
                            this.status &&
                            this.status.accountStatus &&
                            this.status.accountStatus != data.accountStatus
                        ) {
                            this.loadUserInfo();
                        }

                        this.status = data;
                        this.loading = false;
                    }
                })
                .catch((err) => {
                    this.loading = false;
                    this.status = {
                        accountStatus: "Contact technical support",
                        statusData: {
                            msg: "Please contact the customer service staff so that they can restart your instance and we apologize for the inconvenience",
                        },
                    };
                });
        },

        async updateStatus(restart_qr = false, on = false) {
            this.loading = true;
            if (restart_qr) {
                this.status = {};
                this.suspended_qr = false;
            }
            await Nova.request()

                .get(
                    `${this.webhook_url}${this.instance_code}/status?token=${this.token}`,
                    {
                        params: { on },
                    }
                )
                .then(({ data }) => {
                    if (
                        data.error ||
                        (data.message && data.message.includes("is stopped"))
                    ) {
                        this.loading = false;

                        this.status = {
                            accountStatus: "Contact technical support",
                            statusData: {
                                msg: "Please contact the customer service staff so that they can restart your instance and we apologize for the inconvenience",
                            },
                        };
                    } else if (data.accountStatus == "Expired plan") {
                        this.loading = false;
                        this.is_expired = true;

                        this.status = {
                            accountStatus: data.accountStatus,
                            statusData: {
                                msg: "Your plan has expired, make a payment to continue using the service",
                            },
                        };
                    } else {
                        if (
                            data.accountStatus ==
                            "To continue sending a message, you must subscribe to this instance again"
                        ) {
                            this.qr_call_number = 0;
                            this.suspended_qr = true;
                            data.accountStatus = "got qr code";
                            data.on = true;
                        } else if (data.accountStatus == "got qr code") {
                            if (this.qr_call_number >= 5) {
                                this.qr_call_number = 0;
                                this.suspended_qr = true;
                                clearInterval(this.interval_status);
                            } else if (this.qr_call_number == 0) {
                                this.interval_status = setInterval(() => {
                                    this.updateStatus();
                                }, 6000);
                                this.qr_call_number++;
                            } else {
                                this.qr_call_number++;
                                this.suspended_qr = false;
                            }
                        } else {
                            this.showModalConnect(false);
                            this.qr_call_number = 0;
                        }

                        if (
                            this.status &&
                            this.status.accountStatus &&
                            this.status.accountStatus != data.accountStatus
                        ) {
                            this.loadUserInfo();
                        }

                        this.status = data;
                        this.loading = false;
                    }
                })
                .catch((err) => {
                    this.loading = false;

                    this.status = {
                        accountStatus: "Contact technical support",
                        statusData: {
                            msg: "Please contact the customer service staff so that they can restart your instance and we apologize for the inconvenience",
                        },
                    };
                });
        },
    },
};
</script>
