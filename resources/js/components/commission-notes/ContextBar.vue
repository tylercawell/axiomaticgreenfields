<script setup lang="ts">
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'
import PrimaryButton from '@/components/ui/PrimaryButton.vue'
import { useCommissionNotesUiStore } from '@/stores/commissionNotesUi'
import type { Company, Branch } from '@/types/commission-notes'

const props = defineProps<{
    companies: Company[]
    branches: Branch[]
    canManage: boolean
}>()

const ui = useCommissionNotesUiStore()

const filteredBranches = computed(() => {
    if (!ui.selectedCompanyId) return []

    return props.branches.filter(
        branch => branch.company_id === ui.selectedCompanyId
    )
})

function reload(companyId: number | null, branchId: number | null) {
    router.get(
        '/commission-notes',
        {
            company_id: companyId,
            branch_id: branchId,
        },
        {
            preserveState: false,
            preserveScroll: true,
            replace: true,
        }
    )
}

function onCompanyChange(event: Event) {
    const target = event.target as HTMLSelectElement
    const companyId = target.value ? Number(target.value) : null

    ui.setContext(companyId, null)
    reload(companyId, null)
}

function onBranchChange(event: Event) {
    const target = event.target as HTMLSelectElement
    const branchId = target.value ? Number(target.value) : null

    ui.setContext(ui.selectedCompanyId, branchId)
    reload(ui.selectedCompanyId, branchId)
}
</script>

<template>
    <div class="rounded-2xl border border-gray-200 bg-black p-4 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div class="grid flex-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Company
                    </label>

                    <select
                        class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm"
                        :value="ui.selectedCompanyId ?? ''"
                        @change="onCompanyChange"
                    >
                        <option value="">Select a company</option>

                        <option
                            v-for="company in companies"
                            :key="company.id"
                            :value="company.id"
                        >
                            {{ company.name }}
                        </option>
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Branch
                    </label>

                    <select
                        class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm"
                        :value="ui.selectedBranchId ?? ''"
                        :disabled="!ui.selectedCompanyId"
                        @change="onBranchChange"
                    >
                        <option value="">Select a branch</option>

                        <option
                            v-for="branch in filteredBranches"
                            :key="branch.id"
                            :value="branch.id"
                        >
                            {{ branch.name }}
                        </option>
                    </select>
                </div>
            </div>

            <PrimaryButton
                v-if="canManage"
                type="button"
                @click="ui.openCreate"
            >
                Add Note
            </PrimaryButton>
        </div>
    </div>
</template>