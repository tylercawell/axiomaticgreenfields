<script setup lang="ts">
defineProps<{
    title?: string
    description?: string
}>()
</script>

<template>
    <div class="rounded-2xl border border-gray-200 bg-black shadow-sm">
        <div
            v-if="title || description || $slots.header"
            class="border-b border-gray-200 px-4 py-3"
        >
            <slot name="header">
                <h2 v-if="title" class="text-sm font-semibold text-white-900">
                    {{ title }}
                </h2>

                <p v-if="description" class="mt-1 text-sm text-white-500">
                    {{ description }}
                </p>
            </slot>
        </div>

        <div class="p-4">
            <slot />
        </div>
    </div>
</template>