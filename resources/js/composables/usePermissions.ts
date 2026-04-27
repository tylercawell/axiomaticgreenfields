import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

export const usePermissions = () => {
    const page = usePage();

    const userPermissions = computed(() => page.props.auth.permissions || []);

    const hasPermission = (permission: string | string[]): boolean => {
        const permissions = Array.isArray(permission) ? permission : [permission];
        return permissions.some(p => userPermissions.value.includes(p));
    };

    const hasAllPermissions = (permissions: string[]): boolean => {
        return permissions.every(permission => userPermissions.value.includes(permission));
    };

    const hasAnyPermission = (permissions: string[]): boolean => {
        return permissions.some(permission => userPermissions.value.includes(permission));
    };

    return {
        userPermissions,
        hasPermission,
        hasAllPermissions,
        hasAnyPermission,
    };
};
