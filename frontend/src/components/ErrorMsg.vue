<script setup>
defineProps({
    error: {
        required: true,
        type: String
    }
})

// funzione per ricaricare la pagina
const refresh = () => {
    window.location.reload()
}
</script>

<template>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ error }}
        <button type="button" class="btn-close" @click="refresh()"></button>
    </div>
</template>

<style></style>