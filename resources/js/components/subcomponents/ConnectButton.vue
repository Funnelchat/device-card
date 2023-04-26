<template>
    <div class="w-full px-1">
        <button
            @click="showModalChoose(true)"
            class="cursor-pointer btn btn-default btn-primary"
        >
            {{ __("Connect") }}
        </button>
    </div>
</template>
<script>
export default {
    props: ["showModalChoose"],
};
</script>
