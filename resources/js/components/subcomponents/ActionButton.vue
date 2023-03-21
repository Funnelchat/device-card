<template>
  <div class="w-full px-1">
    <button
      @click="action"
      @mouseover="hover = true"
      @mouseleave="hover = false"
      v-bind:class="[
        {
          'opacity-50': is_expired,
          'cursor-not-allowed': is_expired,
          'hover:bg-gray-400': !is_expired,
        },
        'text-xs',
        'w-full',
        'focus:outline-none',
        'bg-gray-300',        
        'text-gray-800',
        'py-2',
        'px-4',
        'rounded',
        'inline-flex',
        'items-center',
        'justify-center',
      ]"
    >
      <!-- Icons -->
      <history-icon v-if="icon == 'history-icon'" class="actionButton" />
      <cellphone-message-icon
        v-else-if="icon == 'cellphone-message-icon'"
        class="actionButton"
      />
      <stackoverflow-icon
        v-else-if="icon == 'stackoverflow-icon'"
        class="actionButton"
      />
      <camera-icon v-else-if="icon == 'camera-icon'" class="actionButton" />
      <contacts-icon v-else-if="icon == 'contacts-icon'" class="actionButton" />
      <cellphone-icon
        v-else-if="icon == 'cellphone-icon'"
        class="actionButton"
      />
      <logout-icon v-else-if="icon == 'logout-icon'" class="actionButton" />
      <reload-icon v-else-if="icon == 'reload-icon'" class="actionButton" />

      <chevron-up-icon
        v-else-if="icon == 'chevron-up-icon'"
        class="arrow-icon actionButton"
      />

      <chevron-down-icon
        v-else-if="icon == 'chevron-down-icon'"
        class="arrow-icon actionButton"
      />

      <!-- Title -->
      <span
        v-if="title"
        v-bind:class="[
          {
            'md:truncate ': !hover,
          },
          'hidden',
          'lg:w-full',
          'md:w-20',
          'md:block',
        ]"
      >
        {{ __(title) }}
      </span>
      <!-- Queue Messages -->
      <span v-if="+cant_queue_messages > 0" class="icon-cant-queue"
        >{{ cant_queue_messages }}
      </span>
    </button>
  </div>
</template>
<script>
import HistoryIcon from "vue-material-design-icons/History.vue";
import StackoverflowIcon from "vue-material-design-icons/StackOverflow.vue";
import CameraIcon from "vue-material-design-icons/Camera.vue";
import LogoutIcon from "vue-material-design-icons/Logout.vue";
import ReloadIcon from "vue-material-design-icons/Reload.vue";
import CellphoneIcon from "vue-material-design-icons/CellphoneKey.vue";
import ContactsIcon from "vue-material-design-icons/AccountMultipleCheck.vue";
import CellphoneMessageIcon from "vue-material-design-icons/CellphoneMessage.vue";
import ChevronDownIcon from "vue-material-design-icons/ChevronDown.vue";
import ChevronUpIcon from "vue-material-design-icons/ChevronUp.vue";

export default {
  props: ["title", "action", "icon", "cant_queue_messages", "is_expired"],
  data() {
    return {
      hover: false,
    };
  },
  methods: {},
  components: {
    ContactsIcon,
    CellphoneMessageIcon,
    CellphoneIcon,
    HistoryIcon,
    StackoverflowIcon,
    CameraIcon,
    LogoutIcon,
    ReloadIcon,
    ChevronDownIcon,
    ChevronUpIcon,
  },
};
</script>
<style lang="css" scoped>
</style>
