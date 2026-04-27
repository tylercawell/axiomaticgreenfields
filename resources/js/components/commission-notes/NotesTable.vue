<script setup lang="ts">
    import type { CommissionNote } from '@/types/commission-notes'

    defineProps<{
            notes: CommissionNote[]
            canManage: boolean
        }>()

    defineEmits<{
            edit: [noteId: number]
        }>()
</script>

<template>
    <div class="overflow-hidden rounded-2xl border border-white-200 bg-black shadow-sm">
        <div class="border-b border-white-200 px-4 py-3">
            <h2 class="text-sm font-semibold text-white-900">Notes</h2>
        </div>

        <div v-if="notes.length === 0" class="px-4 py-8 text-sm text-white-500">
            No commission notes found for the selected context.
        </div>

        <div v-else class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white-200">
                <thead class="bg-white-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-white-500">Employee</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-white-500">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-white-500">Note</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-white-500">Author</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-white-500">Created</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-white-200 bg-black">
                    <tr v-for="note in notes" :key="note.id">
                        <td class="px-4 py-3 text-sm text-white-900">
                            {{ note.employee.full_name }}
                        </td>
                        <td class="px-4 py-3 text-sm text-white-900">
                            R {{ note.amount }}
                        </td>
                        <td class="px-4 py-3 text-sm text-white-600">
                            {{ note.note || '—' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-white-600">
                            {{ note.author.name }}
                        </td>
                        <td class="px-4 py-3 text-sm text-white-600">
                            {{ note.created_at }}
                        </td>
                        <td class="px-4 py-3 text-right text-sm">
                            <button
                                v-if="canManage"
                                type="button"
                                class="font-medium text-white-900 hover:underline"
                                @click="$emit('edit', note.id)"
                            >
                                Edit
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>