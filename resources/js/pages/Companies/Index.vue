<script setup lang="ts">
import { computed, ref } from 'vue'
import { usePage } from '@inertiajs/vue3'
import PageHeader from '@/components/ui/PageHeader.vue'
import AppCard from '@/components/ui/AppCard.vue'
import SearchBar from '@/components/ui/SearchBar.vue'
import EmptyState from '@/components/ui/EmptyState.vue'
import AppTable, { TableColumn } from '@/components/ui/AppTable.vue'
import type { CompaniesIndexProps } from '@/types/companies'
import { Search } from 'lucide-vue-next'

const page = usePage<CompaniesIndexProps>()
const props = computed(() => page.props as unknown as CompaniesIndexProps)
const search = ref('')

const filteredCompanies = computed(() => {
    if (!search.value) return props.value.companies;

    const term = search.value.toLowerCase();

    return props.value.companies.filter(company =>
        company.name.toLowerCase().includes(term)
    );
});

const columns: TableColumn[] = [
    { key: 'name', label: 'Company Name', sortable: true },
    { key: 'branches_count', label: 'Branches', align: 'center', sortable: true },
]  
</script>

<template>
    <div class="min-h-screen space-y-6 bg-black p-6">
        <PageHeader
            title="Companies"
            description="Manage your companies and their branches."
            create-url="#"
            can-create="props.can.create"
        />

        <SearchBar
            v-model="search"
            placeholder="Search companies..."
        />

       <AppTable
            :columns="columns"
            :rows="filteredCompanies"
        >
            <template #empty>
                <EmptyState
                    icon="building"
                    title="No Companies"
                    description="You haven't added any companies yet."
                    :action-url="'/companies/create'"
                    action-text="Add Company"
                />
            </template>
        </AppTable>
    </div>
</template>
