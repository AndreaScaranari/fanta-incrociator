<script setup></script>
<template>
    <div id="loader" role="alert">
        <div class="loader-container">
            <div class="spinner-border spinner-border-sm" role="status">
                <span class="visually-hidden">Caricamento...</span>
            </div>
            <p class="info-loading">Caricamento...</p>
        </div>
    </div>
</template>

<style lang="scss" scoped>
@use "@/assets/style/colors" as *;

#loader {
    height: calc(100vh - 80px - 110.39px);
    width: 100%;
    position: relative;

    .loader-container {
        position: absolute;
        width: 20%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .spinner-border {
        width: 100%;
        aspect-ratio: 1 / 1;
        height: auto;
        margin-bottom: 1rem;
        color: $purple;
        --bs-spinner-border-width: 0.35em;
    }

    .info-loading {
        text-transform: uppercase;
        font-weight: 500;
        font-size: 1.25rem;
    }
}
</style>