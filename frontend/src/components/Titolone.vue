<script setup>
defineProps({
    title: {
        required: true,
        type: String
    },
    subtitle: {
        required: true,
        type: String
    }
})
</script>

<template>
    <div class="h1container">
        <div class="container">
            <h1>{{ title }}</h1>
            <p>{{ subtitle }}</p>
        </div>
    </div>
</template>

<style scoped lang="scss">
@use "@/assets/style/colors" as *;

.h1container {
    background-color: $purple;
    padding: 1rem 0;

    h1 {
        margin-bottom: 0;
        font-size: 2.5rem;
        font-weight: 700;
        text-transform: uppercase;
        color: $white;
    }

    p {
        margin-bottom: 0.4rem;
        color: $white;
    }
}
</style>