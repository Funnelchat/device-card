<template>
    <div class="modal-small modal-window fixed ">
        <div v-if="!loading" class="absolute p-8 bg-white">
            <div class="modal-body-c pb-8">
                <h3 class="mb-4 font-semibold">{{ __("Sync messages") }}</h3>
                <div class="mb-6">
                    <label class="w-full block text-gray-500 font-bold">
                        <input class="mr-2 leading-tight" type="checkbox" />
                        <span class="text-sm">
                            {{ __("Run automations?") }}
                        </span>
                    </label>
                </div>
            </div>
            <div v-if="!loading" class="modal-footer flex justify-end">
                <button
                    class="btn btn-icon btn-default btn-white cursor-pointer mr-2"
                    @click="hideModal"
                >
                    {{ __("Cancel") }}
                </button>
                <button
                    class=" cursor-pointer btn btn-default btn-primary"
                    @click="changeNumber"
                >
                    {{ __("Do it!") }}
                </button>
            </div>
        </div>

        <div v-else class="absolute p-8 bg-white">
            <div
                class="modal-loading relative w-full text-center flex justify-center items-center"
            >
                <div class="lds-dual-ring inline-block"></div>
            </div>
        </div>
    </div>
</template>
<script>
import CloseIcon from "vue-material-design-icons/Close.vue";

export default {
    props: ["device_id"],
    data() {
        return {
            loading: false,
            run_automations: ""
        };
    },
    mounted() {
        this.loading = false;
        this.run_automations = false;
    },
    methods: {
        hideModal() {
            this.$emit("hideModal");
        },

        async changeNumber() {
            /**
             * solicitud de eliminar device.
             */
            this.loading = true;

            Nova.request()
                .post(
                    `/nova-vendor/wppstatus/sync-messages/${this.device_id}`,
                    { runAutomations: this.run_automations }
                )
                .then(({ data }) => {
                    this.$toasted.show(data, {
                        type: "success"
                    });
                    this.loading = false;
                    this.hideModal();
                })
                .catch(({ response }) => {
                    this.loading = false;
                    this.$toasted.show(response.data, {
                        type: "error"
                    });
                });
        }
    },
    components: {
        CloseIcon
    }
};
</script>
<style lang="css" scoped>
/* Modal styles*/
.modal-window {
    background-color: rgba(27, 27, 27, 0.332);
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    z-index: 999;
    transition: all 0.3s;
}

.modal-window > div {
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border-radius: 5px;
}
/* Demo Styles */

.modal-small .modal-body-c {
    overflow: visible;
}
.modal-loading {
    background: #ffffff9e;
    height: 85%;
    margin-top: 70px;
}
.modal-small .modal-loading {
    position: relative !important;
    margin: 2em 0 2em 0;
}

.lds-dual-ring {
    /* display: inline-block; */
    width: 80px;
    height: 80px;
}
.lds-dual-ring:after {
    content: " ";
    display: block;
    width: 64px;
    height: 64px;
    margin: 8px;
    border-radius: 50%;
    border: 6px solid rgba(156, 156, 156, 0.795);
    border-color: rgba(156, 156, 156, 0.795) transparent
        rgba(156, 156, 156, 0.795) transparent;
    animation: lds-dual-ring 1.2s linear infinite;
}
@keyframes lds-dual-ring {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
