<script setup lang="ts">
import { computed, watch, ref } from 'vue'

export interface TableColumn {
    key: string
    label: string
    align?: 'left' | 'right' | 'center'
    sortable?: boolean
}

const props = withDefaults(
    defineProps<{
        columns: TableColumn[]
        rows?: Record<string, any>[]
        clickableRows?: boolean
    }>(),
    {
        rows: () => [],
        clickableRows: false,
    }
)

const emit = defineEmits<{
    rowClick: [row: Record<string, any>]
}>()

const sortKey = ref<string | null>(null)
const sortDirection = ref<'asc' | 'desc'>('asc')

watch(
    () => props.rows,
    () => {
        sortKey.value = null
        sortDirection.value = 'asc'
    },

    { deep: true }
)

const tableRows = computed(() => props.rows ?? [])

const sortedRows = computed(() => {
    const rows = [...tableRows.value]

    if (!sortKey.value) {
        return rows
    }

    return rows.sort((a, b) => {
        const aValue = getNestedValue(a, sortKey.value!)
        const bValue = getNestedValue(b, sortKey.value!)

        if (aValue === null || aValue === undefined) return 1
        if (bValue === null || bValue === undefined) return -1

        const aNumber = Number(aValue)
        const bNumber = Number(bValue)

        if (!Number.isNaN(aNumber) && !Number.isNaN(bNumber)) {
            return sortDirection.value === 'asc'
                ? aNumber - bNumber
                : bNumber - aNumber
        }

        return sortDirection.value === 'asc'
            ? String(aValue).localeCompare(String(bValue))
            : String(bValue).localeCompare(String(aValue))
    })
})

function sortBy(column: TableColumn): void {
    if (!column.sortable) return

    if (sortKey.value === column.key) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
        return
    }

    sortKey.value = column.key
    sortDirection.value = 'asc'
}

function getNestedValue(row: Record<string, any>, key: string): any {
    return key.split('.').reduce((value, part) => value?.[part], row)
}

function handleRowClick(row: Record<string, any>): void {
    if (!props.clickableRows) return

    emit('rowClick', row)
}
</script>

<template>
    <div class="overflow-hidden rounded-2xl border border-black-200 bg-black shadow-sm">
        <div v-if="sortedRows.length === 0" class="px-4 py-8 text-sm text-black-500">
            <slot name="empty">
                No records found.
            </slot>
        </div>

        <div v-else class="overflow-x-auto">
            <table class="min-w-full divide-y divide-black-200">
                <thead class="bg-black-50">
                    <tr>
                        <th
                            v-for="column in columns"
                            :key="column.key"
                            class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-black-500"
                            :class="{
                                'text-left': column.align !== 'right' && column.align !== 'center',
                                'text-right': column.align === 'right',
                                'text-center': column.align === 'center',
                                'cursor-pointer select-none hover:text-black-900': column.sortable,
                            }"
                            @click="sortBy(column)"
                        >
                            <span class="inline-flex items-center gap-1">
                                {{ column.label }}

                                <span v-if="column.sortable && sortKey === column.key">
                                    {{ sortDirection === 'asc' ? '↑' : '↓' }}
                                </span>
                            </span>
                        </th>

                        <th v-if="$slots.actions" class="px-4 py-3 text-right" />
                    </tr>
                </thead>

                <tbody class="divide-y divide-black-200 bg-black">
                    <tr
                        v-for="row in sortedRows"
                        :key="row.id"
                        :class="{
                            'cursor-pointer transition hover:bg-black-50': clickableRows,
                        }"
                        @click="handleRowClick(row)"
                    >
                        <td
                            v-for="column in columns"
                            :key="column.key"
                            class="px-4 py-3 text-sm text-black-900"
                            :class="{
                                'text-left': column.align !== 'right' && column.align !== 'center',
                                'text-right': column.align === 'right',
                                'text-center': column.align === 'center',
                            }"
                        >
                            <slot
                                :name="column.key"
                                :row="row"
                                :value="getNestedValue(row, column.key)"
                            >
                                {{ getNestedValue(row, column.key) }}
                            </slot>
                        </td>

                        <td
                            v-if="$slots.actions"
                            class="px-4 py-3 text-right"
                            @click.stop
                        >
                            <slot name="actions" :row="row" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>