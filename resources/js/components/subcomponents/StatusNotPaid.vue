<template>
  <div
    v-bind:class="[
      {
        'status-auth-battery': status.accountStatus != 'Expired plan',
        'bg-gray-300': status.accountStatus == 'Expired plan',
      },
      'status-auth',
      'p-4',
      'flex',
      'm-auto',
      'font-medium',
    ]"
    class=""
  >
    <div class="w-full">
      <p class="text-center p-4">{{ __(status.statusData.msg) }}.</p>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    status: Object,
  },
};
</script>
<style lang="css" scoped>
.status-auth {
  border-radius: 5px;
}

.status-auth-battery {
  background-color: #ff676740;
  color: #d81818;
}

.status-auth-battery-img .material-design-icon svg,
.status-auth-battery-img .material-design-icon {
  width: 80px !important;
  height: 80px !important;
}
</style>
