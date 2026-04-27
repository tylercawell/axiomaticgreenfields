import { defineStore } from 'pinia'
import { ref } from 'vue'

type FormMode = 'create' | 'edit'

export const useCommissionNotesUiStore = defineStore('commissionNotesUi', () => {
    const selectedCompanyId = ref<number | null>(null)
    const selectedBranchId = ref<number | null>(null)
    const isFormOpen = ref(false)
    const selectedNoteId = ref<number | null>(null)
    const mode = ref<FormMode>('create')

    function openCreate() {
        mode.value = 'create'
        selectedNoteId.value = null
        isFormOpen.value = true
    }

    function openEdit(noteId: number) {
        mode.value = 'edit'
        selectedNoteId.value = noteId
        isFormOpen.value = true
    }

    function closeForm() {
        isFormOpen.value = false
    }

    function setContext(companyId: number | null, branchId: number | null) {
        selectedCompanyId.value = companyId
        selectedBranchId.value = branchId
    }

    return {
        selectedCompanyId,
        selectedBranchId,
        isFormOpen,
        selectedNoteId,
        mode,
        openCreate,
        openEdit,
        closeForm,
        setContext
    }
})