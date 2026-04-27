<script setup lang="ts">
withDefaults(
    defineProps<{
        type?: 'button' | 'submit'
        disabled?: boolean
    }>(),
    {
        type: 'button',
        disabled: false,
    }
)
</script>

<template>
    <button
        :type="type"
        :disabled="disabled"
        class="inline-flex items-center justify-center rounded-xl bg-gray-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-50"
    >
        <slot />
    </button>
</template>