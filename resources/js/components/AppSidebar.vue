<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { Activity, BadgeCheck, BookOpen, BookUser, CreditCard, Folder, Layers, LayoutGrid, MapPin, Medal, User, Wallet } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Panel de control',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Usuarios',
        href: '/panel/users',
        icon: User,
    },
    {
        title: 'Locales',
        href: '/panel/locals',
        icon: MapPin,
    },
    {
        title: 'Clientes',
        href: '/panel/customers',
        icon: BookUser,
    },
    {
        title: 'Tipo de membresias',
        href: '/panel/typeMemberships',
        icon: Layers,
    },
    {
        title: 'Método de pago',
        href: '/panel/paymentMethods',
        icon: CreditCard,
    },
    {
        title: 'Entrenadores',
        href: '/dashboard',
        icon: Medal,
    },
    {
        title: 'Registro de sesiones',
        href: '/dashboard',
        icon: Activity,
    },
    {
        title: 'Mensualidades',
        href: '/dashboard',
        icon: Wallet,
    },
];

</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
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
            <NavUser />
        </SidebarFooter>
    </Sidebar>
</template>
