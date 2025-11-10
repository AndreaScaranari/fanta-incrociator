<script setup>
defineProps({
    msg: {
        required: true,
        type: String
    }
})
</script>

<template>
    <div class="alert alert-warning" role="alert">
        {{ msg }}
    </div>
</template>

<style></style>