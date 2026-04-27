<script setup lang="ts">
import { computed, watch, ref } from 'vue'
import { usePage } from '@inertiajs/vue3'
import ContextBar from '@/components/commission-notes/ContextBar.vue'
import NoteFormPanel from '@/components/commission-notes/NoteFormPanel.vue'
import SearchBar from '@/components/ui/SearchBar.vue'
import AppTable, { type TableColumn } from '@/components/ui/AppTable.vue'
import EmptyState from '@/components/ui/EmptyState.vue'
import { useCommissionNotesUiStore } from '@/stores/commissionNotesUi'
import type { CommissionNotesPageProps, CommissionNote } from '@/types/commission-notes'

const page = usePage()
const props = computed(() => page.props as unknown as CommissionNotesPageProps)

const ui = useCommissionNotesUiStore()
const search = ref('')

const columns: TableColumn[] = [
    { key: 'employee.full_name', label: 'Employee', sortable: true },
    { key: 'amount', label: 'Amount', align: 'right', sortable: true },
    { key: 'note', label: 'Note', sortable: true },
    { key: 'author.name', label: 'Author', sortable: true },
    { key: 'created_at', label: 'Created', sortable: true },
]

watch(
    () => props.value.filters,
    (filters) => {
        ui.setContext(filters.company_id, filters.branch_id)
    },
    { immediate: true }
)

const selectedNote = computed<CommissionNote | null>(() => {
    if (!ui.selectedNoteId) return null

    return (props.value.commissionNotes ?? []).find(
        note => note.id === ui.selectedNoteId
    ) ?? null
})

const filteredNotes = computed(() => {
    const notes = props.value.commissionNotes ?? []

    if (!search.value) return notes

    const term = search.value.toLowerCase()

    return notes.filter(note =>
        note.employee.full_name.toLowerCase().includes(term) ||
        String(note.amount).includes(term) ||
        (note.note ?? '').toLowerCase().includes(term) ||
        note.author.name.toLowerCase().includes(term)
    )
})
</script>

<template>
    <div class="min-h-screen space-y-6 bg-black p-6">
        <div class="ml-5 pt-5">
            <h1 class="text-2xl font-semibold text-white">
                Commission Notes
            </h1>

            <p class="mt-1 text-sm text-gray-300">
                Capture and manage commission notes by company and branch.
            </p>
        </div>

        <ContextBar
            :companies="props.companies"
            :branches="props.branches"
            :can-manage="props.can?.manage ?? false"
        />

        <SearchBar
            v-model="search"
            placeholder="Search notes..."
        />

        <div class="rounded bg-black p-2 text-sm">
            Filtered: {{ filteredNotes.length }}
        </div>
       <AppTable
            :key="`${props.filters.company_id ?? 'all'}-${props.filters.branch_id ?? 'all'}-${search}-${filteredNotes.length}`"
            :columns="columns"
            :rows="filteredNotes"
            clickable-rows
            @row-click="(row) => row.can_update ? ui.openEdit(row.id) : null"
        >
            <template #amount="{ row }">
                R {{ Number(row.amount).toLocaleString() }}
            </template>

            <template #note="{ row }">
                {{ row.note || '—' }}
            </template>

            <template #actions="{ row }">
                <button
                    v-if="row.can_update ?? props.can?.manage"
                    type="button"
                    class="text-sm font-medium text-white-900 hover:underline"
                    @click="ui.openEdit(row.id)"
                >
                    Edit
                </button>
            </template>

            <template #empty>
                <EmptyState
                    title="No commission notes found"
                    description="Select a company and branch or adjust your search."
                />
            </template>
        </AppTable>

        <NoteFormPanel
            :open="ui.isFormOpen"
            :mode="ui.mode"
            :note="selectedNote"
            :companies="props.companies"
            :branches="props.branches"
            :employees="props.employees"
            @close="ui.closeForm"
        />
    </div>
</template>