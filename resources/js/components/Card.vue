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
                                        v-if="status_authenticated"
                                        class="absolute right-0 bottom-0 rounded-full p-1 bg-white"
                                    >
                                        <div
                                            class="w-2 h-2 rounded-full dot"
                                        ></div>
                                    </div>
                                </div>

                                <div class="ml-4">
                                    <p
                                        class="text-90 montserrat text-gray-800 text-xs md:text-base font-semibold"
                                    >
                                        {{ get_formated_number }}
                                    </p>
                                    <div class="my-2 md:my-3">
                                        <span
                                            class="text-xs md:text-sm text-gray-600 mr-1"
                                            >{{ __("status") }}</span
                                        >
                                        <span
                                            class="auth-label"
                                            v-bind:class="[
                                                {
                                                    'auth-blue':
                                                        status_got_qr_code,
                                                    'auth-green':
                                                        status_authenticated,
                                                    'auth-yellow':
                                                        status_loading,
                                                    'auth-red': status_error,
                                                },
                                                'auth-label',
                                            ]"
                                            >{{ __(auth_state) }}
                                            <Loading
                                                :color="'#b98201'"
                                                :width="'25px'"
                                                v-if="status_loading"
                                            />
                                        </span>
                                    </div>
                                    <div v-if="show_queue_label">
                                        <div
                                            class="auth-label auth-red flex justify-center items-center"
                                        >
                                            <Exclamationsvg />
                                            <span class="mx-15"
                                                >{{ __("You have") }}
                                                <span class="gilroy-bold">{{
                                                    cant_queue_messages
                                                }}</span>
                                                {{ __("queue messages") }}</span
                                            >
                                            <a
                                                @click="showModalMessagesQueue"
                                                class="gilroy-bold cursor-pointer"
                                            >
                                                {{ __("view messages") }}</a
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-center items-center">
                                <disconect-button
                                    v-if="status_authenticated"
                                    :showModalDisconnect="showModalDisconnect"
                                />
                                <connect-button
                                    v-if="status_got_qr_code"
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
                    :queue_messages="queue_messages"
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
                    :clear_loading="clear_loading"
                    :video="video"
                    :device_number="device_number"
                    :changeNumber="changeNumber"
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
import Bot1svg from "./subcomponents/svg/bot1.vue";
import Exclamationsvg from "./subcomponents/svg/exclamation.vue";
import ConnectButton from "./subcomponents/buttons/ConnectButton.vue";
import DisconectButton from "./subcomponents/buttons/DisconectButton.vue";
import Loading from "./subcomponents/Loading.vue";

//Modal
import ModalMessagesQueue from "./subcomponents/ModalMessagesQueue";
import ModalDisconnect from "./subcomponents/ModalDisconnect";
import ModalConnect from "./subcomponents/ModalConnect";
import ModalChoose from "./subcomponents/ModalChoose";

export default {
    props: ["card", "resource"],

    mounted() {
        this.instance_code = this.card.instance_code;
        this.device_alias = this.card.device_alias;
        this.token = this.card.token;
        this.device_number = this.card.device_number;
        this.device_id = this.card.device_id;
        this.status = {};
        this.suspended_qr = false;
        this.wapi_url = `${this.card.wapi_url}/api/instance`;
        this.webhook_url = `${this.card.webhook_url}/api`;
        this.getStatus();
        this.loadUserInfo();
        this.getQueueMessages();
    },

    data() {
        return {
            video: "android",
            qr_call_number: 0,
            suspended_qr: false,
            instance_code: "",
            token: "",
            device_id: "",
            device_number: "",
            device_alias: "",
            status: {
                accountStatus: "loading",
            },
            loading: true,
            clear_loading: false,
            wapi_url: "",
            webhook_url: "",
            interval_status: null,
            info_user: null,
            is_expired: false,
            show_modal_disconnect: false,
            show_modal_messages_queue: false,
            show_modal_connect: false,
            show_modal_choose: false,
            cant_queue: 0,
            queue_messages: [],
        };
    },

    components: {
        Bot1svg,
        Exclamationsvg,
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

        status_got_qr_code() {
            return this.status.accountStatus == "got qr code";
        },

        status_authenticated() {
            return this.status.accountStatus == "authenticated";
        },

        status_loading() {
            return (
                this.status.accountStatus == "loading" ||
                this.loading ||
                !this.status.accountStatus
            );
        },

        status_error() {
            return this.status.accountStatus == "Contact technical support";
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
                    this.queue_messages = response.data.first100;
                    this.cant_queue = response.data.totalMessages;
                })
                .catch((err) => {
                    console.log(err.toString());
                    this.showAlert("There was an error", "error");
                });
        },

        clearMessagesQueue() {
            const url = `${this.wapi_url}${this.instance_code}/clearMessagesQueue?token=${this.token}`;
            Nova.request()
                .post(url)
                .then((response) => {
                    this.getQueueMessages();
                    this.showModalMessagesQueue(false);
                })
                .catch((err) => {
                    console.log(err.toString());
                    this.showAlert("There was an error", "error");
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
                    this.showAlert("There was an error", "error");
                });
        },

        loadUserInfo() {
            const url = `${this.wapi_url}${this.instance_code}/me?token=${this.token}`;
            Nova.request()
                .get(url)
                .then((response) => {
                    if (response.status == 200 && !response.data.error) {
                        this.info_user = response.data;

                        //Comprobación de que sea el mismo número que el guardado en la base de datos
                        if (
                            this.device_number &&
                            this.device_number != this.info_user.phone
                        ) {
                            this.showAlert(
                                `${this.__("wrong_number", {
                                    number: this.info_user.phone,
                                })}`,
                                "error"
                            );
                        }
                    }
                })
                .catch((err) => {
                    console.log(err.toString());
                    this.showAlert("There was an error", "error");
                });
        },

        async changeNumber() {
            this.clear_loading = true;
            await clearInterval(this.interval_status);
            await Nova.request()
                .post(`${this.webhook_url}/devices/${this.device_id}/clean`)
                .then(() => {
                    this.clear_loading = false;
                    this.device_number = "";
                    this.updateStatus(true, this.status.on || false);
                    this.showAlert("The number was cleaned!", "success");
                })
                .catch(({}) => {
                    this.clear_loading = false;
                    console.log(err.toString());
                    this.showAlert("There was an error", "error");
                });
        },

        async getStatus() {
            this.loading = true;
            await Nova.request()

                .get(
                    `${this.webhook_url}/instances/${this.device_id}/status?token=${this.token}`,
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
                this.qr_call_number = 0;
                this.status = {};
                this.suspended_qr = false;
            }
            await Nova.request()

                .get(
                    `${this.webhook_url}/instances/${this.device_id}/status?token=${this.token}`,
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
