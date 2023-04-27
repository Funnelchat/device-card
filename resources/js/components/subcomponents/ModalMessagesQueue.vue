<template>
    <div class="modal-small modal-window fixed">
        <div class="relative modal-padding bg-white" style="width: 595px">
            <div class="mb-6">
                <h3 class="font-semibold text-base text-black">
                    {{ __("You have") }} {{ cant_queue_messages }}
                    {{ __("in queue") }}
                </h3>
                <Close :close="showModalMessagesQueue" />
            </div>

            <div
                class="modal-body-c mb-8"
                style="height: 392px; overflow: auto"
            >
                <p
                    v-for="(item, index) in queue_messages"
                    :key="index"
                    v-bind:class="[
                        {
                            'bg-white': index % 2 > 0,
                        },
                        'text-justify',
                        'p-queue',
                        'text-black',
                        'text-sm'
                    ]"
                >
                    {{ item }}
                </p>
            </div>
            <div class="modal-footer flex justify-end">
                <button
                    class="cursor-pointer close-button hover:shadow"
                    @click="showModalMessagesQueue(false)"
                >
                    {{ __("Cancel") }}
                </button>
                <button
                    class="cursor-pointer btn-default btn-primary shadow-none"
                    @click="change()"
                >
                    <span v-if="!press_button">{{ __("Clear") }}</span>
                    <Loading v-else color="white" />
                </button>
            </div>
        </div>
    </div>
</template>
<script>
import Close from "./svg/close.vue";
import Loading from "./Loading.vue";

export default {
    props: [
        "cant_queue_messages",
        "showModalMessagesQueue",
        "clearMessagesQueue",
        "queue_messages",
    ],
    components: {
        Close,
        Loading,
    },

    data() {
        return {
            press_button: false,
        };
    },

    methods: {
        change() {
            if (!this.press_button) this.clearMessagesQueue();
            this.press_button = true;
        },
    },
};
</script>
