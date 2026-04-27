<script setup lang="ts">
    defineProps<{
        modelValue: string
        placeholder?: string
    }>()

    defineEmits<{
        'update:modelValue': [value: string]
    }>()
</script>

<template>
    <div class="relative">
        <input
            :value="modelValue"
            type="search"
            :placeholder="placeholder ?? 'Search...'"
            class="w-full rounded-xl border border-gray-300 bg-black px-3 py-2 pl-9 text-sm text-gray-300 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-gray-900 focus:ring-1 focus:ring-gray-900"
            @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
        />

            <svg
                class="pointer-events-none absolute left-3 top-2.5 h-4 w-4 text-gray-400"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z"
                />
            </svg>
    </div>
</template>