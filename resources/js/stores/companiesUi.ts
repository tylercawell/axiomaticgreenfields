import { defineStore} from 'pinia';
import { ref } from 'vue';

type FormMode = 'create' | 'edit';

type Form = {
    name: string;
};

export const useCompaniesUiStore = defineStore('companiesUi', () => {

    const isFormOpen = ref(false);
    const mode = ref<FormMode>('create');
    const selectedCompanyId = ref<number | null>(null);
    const form = ref<Form>({
        name: '',
    });

    function openCreate() {
        mode.value = 'create';
        selectedCompanyId.value = null;
        isFormOpen.value = true;
    }

    function openEdit(companyId: number, companyName: string) {
        mode.value = 'edit';
        selectedCompanyId.value = companyId;
        isFormOpen.value = true;
    }

    function closeForm() {
        isFormOpen.value = false;
    }

    return {
        isFormOpen,
        mode,
        selectedCompanyId,
        form,
        openCreate,
        openEdit,
        closeForm
    }
})