<template>
    <div class="w-full px-1">
        <button
            @click="showModalChoose(true)"
            class="cursor-pointer font-bold btn-default rounded btn-primary shadow-none text-xs md:text-sm"
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
