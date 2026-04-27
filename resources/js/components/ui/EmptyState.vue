<script setup lang="ts">
defineProps<{
    title: string
    description?: string
}>()
</script>

<template>
    <div class="rounded-xl border border-dashed border-gray-300 px-6 py-10 text-center">
        <h3 class="text-sm font-semibold text-gray-900">
            {{ title }}
        </h3>

        <p v-if="description" class="mt-1 text-sm text-gray-500">
            {{ description }}
        </p>

        <div v-if="$slots.actions" class="mt-4">
            <slot name="actions" />
        </div>
    </div>
</template>