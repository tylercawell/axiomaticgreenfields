<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, BuildingIcon, FolderGit2, LayoutGrid } from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';
import { commissionNotes } from '@/routes';
import { companies } from '@/routes';

const page = usePage();

const allNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    
    {
        title: 'Companies',
        href: companies(),
        icon: BuildingIcon,
        permissions: ['view companies', 'manage companies'],
    },

    {
        title: 'Commission Notes',
        href: commissionNotes(),
        icon: BookOpen,
        permissions: ['view commission notes', 'manage commission notes'],
    },
];

const userPermissions = computed(() => page.props.auth.permissions || []);

const hasPermission = (requiredPermissions: string | string[] | undefined): boolean => {
    if (!requiredPermissions) return true;
    
    const permissions = Array.isArray(requiredPermissions) ? requiredPermissions : [requiredPermissions];
    return permissions.some(permission => userPermissions.value.includes(permission));
};

const mainNavItems = computed(() => 
    allNavItems.filter(item => hasPermission(item.permissions))
);

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: FolderGit2,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
