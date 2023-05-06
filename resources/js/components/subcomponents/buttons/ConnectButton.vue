<template>
    <div class="w-full px-1">
        <button
            @click="showModalChoose(true)"
            class="cursor-pointer btn-default btn-primary shadow-none"
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
