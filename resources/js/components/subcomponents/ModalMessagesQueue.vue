<template>
    <div class="modal-small modal-window fixed">
        <div class="absolute p-8 bg-white">
            <div class="modal-body-c pb-8">
                <h3 class="mb-4 font-semibold text-base">
                    {{ __("Messages Queue!") }}
                </h3>
                <p class="text-justify">
                    <span v-if="cant_queue_messages < 1"
                        >{{ __("You don't have any messages in queue") }}.
                    </span>
                    <span v-else>
                        {{ __("You have") }} <b>{{ cant_queue_messages }}</b>
                        {{ __("in queue") }}.
                    </span>
                </p>
            </div>
            <div class="modal-footer flex justify-end">
                <button
                    class="btn btn-icon btn-default btn-white cursor-pointer mr-2"
                    @click="showModalMessagesQueue(false)"
                >
                    {{ __("Cancel") }}
                </button>
                <button
                    v-if="cant_queue_messages > 0"
                    class="cursor-pointer btn btn-default btn-primary"
                    @click="clearMessagesQueue()"
                >
                    {{ __("Clear") }}
                </button>
            </div>
        </div>
    </div>
</template>
<script>
import CloseIcon from "vue-material-design-icons/Close.vue";

export default {
    props: [
        "cant_queue_messages",
        "showModalMessagesQueue",
        "clearMessagesQueue",
    ],
    components: {
        CloseIcon,
    },
   
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
    width: 400px;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border-radius: 5px;
}
/* Demo Styles */

.modal-body {
    max-height: 300px;
}

.modal-status-history table,
.modal-regular table {
    font-size: 12px;
}
.modal-status-history table th,
.modal-status-history table td,
.modal-regular table th,
.modal-regular table td {
    text-align: left;
    border-bottom: #dedede 1px solid;
    padding: 15px 5px;
    text-overflow: ellipsis;
    overflow: hidden;
}
.modal-regular .modal-footer {
    padding: 10px;
    border-top: #cccccc 1px solid;
}
</style>
