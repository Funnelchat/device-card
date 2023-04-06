<template>
    <card class="flex flex-col items-center justify-center">
        <div class="wpp-container w-full relative text-90 bg-white">
            <div class="wpp-content w-full">
                <div class="container m-auto">
                    <div class="m-1 grid-rows-1 grid grid-flow-col gap-1">
                        <div
                            class="row-span-1 col-span-3 bg-gray-100 rounded p-2 flex justify-between items-center"
                        >
                            <div class="flex justify-center items-center">
                                <div
                                    v-bind:style="{ background: device_color }"
                                    class="flex justify-center items-center p-1 rounded-full"
                                >
                                    <WhatsappSvg />
                                </div>
                                <span class="text-90 ml-2 font-semibold">
                                    {{ get_formated_number }}
                                </span>
                            </div>
                            <div class="flex justify-center items-center">
                                <span
                                    v-if="
                                        status.accountStatus !=
                                        'Contact technical support'
                                    "
                                    class="text-90 capitalize"
                                >
                                    {{ __("status") }}:&nbsp;
                                </span>
                                <p
                                    v-bind:class="[
                                        {
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
                                        'uppercase',
                                        'auth-label',
                                        'whitespace-nowrap',
                                    ]"
                                >
                                    {{ __(auth_state) }}

                                    <loading
                                        v-if="
                                            loading ||
                                            status.accountStatus == 'loading' ||
                                            !status.accountStatus
                                        "
                                        :color="'#b98201'"
                                    />
                                </p>

                                <action-button
                                    v-if="shown_buttons"
                                    :action="showModalMessagesQueue"
                                    :icon="'stackoverflow-icon'"
                                    :title="'Messages Queue'"
                                    :cant_queue_messages="cant_queue_messages"
                                    :is_expired="is_expired"
                                />

                                <action-button
                                    v-if="shown_buttons"
                                    :action="showModalDisconnect"
                                    :icon="'logout-icon'"
                                    :title="'Disconnect your WhatsApp'"
                                    :is_expired="is_expired"
                                />
                            </div>
                        </div>
                    </div>
                    <div v-if="show_body" class="body text-90 p-1 m-1">
                        <StatusQR
                            v-if="status.qrCode || status.on"
                            :status="status"
                            :startInterval="updateStatus"
                            :suspended_qr="suspended_qr"
                        />
                        <StatusNotPaid v-else :status="status" />
                    </div>
                </div>
            </div>
            <!-- Modal for queue history -->
            <ModalMessagesQueue
                v-if="show_modal_messages_queue"
                :cant_queue_messages="cant_queue_messages"
                @hideModalMessagesQueue="hideModalMessagesQueue"
                @clearMessagesQueue="clearMessagesQueue"
            />

            <!-- Modal for disconnect-->
            <ModalDisconnect
                v-if="show_modal_disconnect"
                @hideModalDisconnect="hideModalDisconnect"
                @disconnect="disconnect"
            />
        </div>
    </card>
</template>

<script>
import WhatsappSvg from "./subcomponents/WhatsappSvg.vue";
import "vue-material-design-icons/styles.css";
//Components status
import StatusNotPaid from "./subcomponents/StatusNotPaid";
import StatusQR from "./subcomponents/StatusQR";
import ActionButton from "./subcomponents/ActionButton.vue";
import Loading from "./subcomponents/Loading.vue";

//Modal
import ModalMessagesQueue from "./subcomponents/ModalMessagesQueue";
import ModalDisconnect from "./subcomponents/ModalDisconnect";

export default {
    props: ["card", "resource"],

    mounted() {
        this.$parent.$el.classList.remove("min-h-40");
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
        this.updateStatus();
        this.loadUserInfo();
        this.getQueueMessages();
    },

    data() {
        return {
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
            cant_queue: 0,
        };
    },

    components: {
        StatusNotPaid,
        StatusQR,
        WhatsappSvg,
        ActionButton,
        ModalMessagesQueue,
        ModalDisconnect,
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
                return `${this.device_alias} (+${this.device_number})`;
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
                        this.controlShowBody("open");
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

        shown_buttons() {
            return this.status.accountStatus == "authenticated";
        },
    },

    methods: {
        async updateStatus(restart_qr = false, on = false) {
            if (restart_qr) {
                this.status = {};
                this.suspended_qr = false;
            }
            await Nova.request()
                .get(`${this.webhook_url}${this.device_id}/status`, {
                    params: { on },
                })
                .then(({ data }) => {
                    if (
                        data.error ||
                        (data.message && data.message.includes("is stopped"))
                    ) {
                        this.loading = false;
                        this.controlShowBody("open");
                        this.status = {
                            accountStatus: "Contact technical support",
                            statusData: {
                                msg: "Please contact the customer service staff so that they can restart your instance and we apologize for the inconvenience",
                            },
                        };
                    } else if (data.accountStatus == "Expired plan") {
                        this.loading = false;
                        this.is_expired = true;
                        this.controlShowBody("open");
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
                    this.controlShowBody("open");
                    this.status = {
                        accountStatus: "Contact technical support",
                        statusData: {
                            msg: "Please contact the customer service staff so that they can restart your instance and we apologize for the inconvenience",
                        },
                    };
                });
        },

        showModalMessagesQueue() {
            console.log("WEP 1");
            this.show_modal_messages_queue = true;
        },

        hideModalMessagesQueue() {
            console.log("WEP 2");
            this.show_modal_messages_queue = false;
        },

        showModalDisconnect() {
            console.log("WEP 3");
            this.show_modal_disconnect = true;
        },

        hideModalDisconnect() {
            console.log("WEP 4");
            this.show_modal_disconnect = false;
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
                .post(url)
                .then((response) => {
                    this.show_modal_disconnect = false;
                    if (response.status == 200) {
                        this.showAlert("Disconnected successfully", "success");
                        this.loading = true;
                        this.status = { accountStatus: "loading" };
                        this.updateStatus();
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

        showAlert(msg, type) {
            this.$toasted.show(`${this.__(msg)}.`, { duration: 10000, type });
        },

        showBody() {
            this.show_body = true;
            localStorage.setItem(`device-${this.device_id}`, "open");
        },

        hideBody() {
            this.show_body = false;
            localStorage.setItem(`device-${this.device_id}`, "hide");
        },

        controlShowBody(value = null) {
            const local_value = localStorage.getItem(
                `device-${this.device_id}`
            );
            if (value) {
                if (local_value == "open" || local_value == "hide") {
                    this.show_body = local_value == "open" ? true : false;
                } else {
                    this.show_body = value == "open" ? true : false;
                    localStorage.setItem(`device-${this.device_id}`, value);
                }
            } else {
                localStorage.removeItem(`device-${this.device_id}`);
            }
        },
    },
};
</script>
