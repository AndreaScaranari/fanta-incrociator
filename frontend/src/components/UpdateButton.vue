<script setup>
defineProps({
    changedCount: {
        required: true,
        type: Number
    },
    isLoading: {
        required: true,
        type: Boolean
    }
})

const emit = defineEmits(['update'])

const handleClick = () => {
    emit('update')  // Invia l'evento al genitore
}
</script>

<template>
    <button 
        @click="handleClick"
        class="btn"
        :disabled="changedCount === 0 || isLoading"
    >
        <span v-if="isLoading" class="spinner-border spinner-border-sm me-2" role="status">
            <span class="visually-hidden">Salvataggio...</span>
        </span>
        Aggiorna {{ changedCount > 0 ? `(${changedCount})` : '' }}
    </button>
</template>

<style scoped lang="scss">
@use "@/assets/style/colors" as *;

.btn {
    min-width: 150px;
    background-color: $blue;
    color: $white;
    font-weight: 500;
    transition: all 0.25s ease-in-out;

    &:hover:not(:disabled) {
        filter: brightness(1.1);
        scale: 1.01;
    }

    &:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
}
</style>
