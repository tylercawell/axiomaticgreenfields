<script setup lang="ts">
import { computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3';
import { useCommissionNotesUiStore } from '@/stores/commissionNotesUi'
import type { Branch, Company, CommissionNote, Employee } from '@/types/commission-notes'

const props = defineProps<{
    open: boolean
    mode: 'create' | 'edit'
    note: CommissionNote | null
    companies: Company[]
    branches: Branch[]
    employees: Employee[]
}>()

const emit = defineEmits<{
    close: []
}>()

const ui = useCommissionNotesUiStore()
const form = useForm({
    company_id: null as number | null,
    branch_id: null as number | null,
    employee_id: null as number | null,
    amount: '',
    note: '',
})

const availableEmployees = computed(() => {
    if (!form.branch_id) return []

    return props.employees.filter(
        employee => employee.branch_id === form.branch_id
    )
})

watch(
    () => [props.open, props.note],
    () => {
        if (!props.open) return

        form.clearErrors()

        form.company_id = props.note?.company_id ?? ui.selectedCompanyId
        form.branch_id = props.note?.branch_id ?? ui.selectedBranchId
        form.employee_id = props.note?.employee_id ?? null
        form.amount = props.note?.amount?.toString() ?? '0'
        form.note = props.note?.note ?? ''
    },

    { immediate: true }
)

watch(
    () => form.branch_id,
    (newBranchId, oldBranchId) => {
        if (!props.open) return
        if (props.mode === 'edit' && newBranchId === props.note?.branch_id) return
        if (newBranchId !== oldBranchId) {
            form.employee_id = null
        }
    }
)

function submit() {
    if (props.mode === 'create') {
        form.post('/commission-notes', {
            preserveScroll: true,
            onSuccess: () => emit('close'),
        })
    } else if (props.mode === 'edit' && props.note) {
        form.put(`/commission-notes/${props.note.id}`, {
            preserveScroll: true,
            onSuccess: () =>
                emit('close'),
        })
    }
}   
</script>

<template>
    <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-end bg-black/30">
        <div class="h-full w-full max-w-xl bg-black p-6 shadow-xl">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-white-900">
                    {{ mode === 'create' ? 'Add Commission Note' : 'Edit Commission Note' }}
                </h2>

                <button type="button" class="text-sm text-white-500 hover:text-white-900" @click="$emit('close')">
                    Close
                </button>
            </div>

            <div class="rounded-xl border border-dashed border-white-300 p-4 text-sm text-white-500">
                <div>
                    <label class="mb-1 block text-sm font-medium text-white-700">
                        Employee
                    </label>

                    <select v-model="form.employee_id"
                        class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm pt-5" 
                        :disabled="availableEmployees.length === 0">
                        <option :value="null">
                            Select an employee
                        </option>

                        <option v-for="employee in availableEmployees" :key="employee.id" :value="employee.id">
                            {{ employee.full_name }}
                        </option>
                    </select>

                    <p v-if="form.errors.employee_id" class="mt-1 text-sm text-red-600">
                        {{ form.errors.employee_id }}
                    </p>

                    <p v-if="form.branch_id && availableEmployees.length === 0" class="mt-1 text-sm text-gray-500">
                        No employees found for this branch.
                    </p>
                </div>

                <div class="pt-5">
                    <label>Amount</label>
                    <input v-model="form.amount" type="number" class="w-full border p-2" />
                </div>

                <div class="pt-5">
                    <label>Note</label>
                    <textarea v-model="form.note" class="w-full border p-2"></textarea>
                </div>

                 <button type="button" @click="submit" class="mt-4 rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                Save Note
            </button>
            </div>
        </div>
    </div>
</template>