<script setup>
import { ref, computed } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
// import { computed } from 'vue';
// import { usePage } from '@inertiajs/vue3';
import { usePermission } from '@/Composables/permissions.js';
import cartStore from '@/Pages/Catalogue/cart';

const showingNavigationDropdown = ref(false);
const { hasRole } = usePermission();
const isAdmin = hasRole('admin');

// Example cart items (replace with your data fetching)
const cartItems = cartStore.cart; 

const cartTotal = computed(() => {
    return cartItems.items.reduce((total, item) => {
        return total + (item.price * item.quantity);
    }, 0);
});

const removeFromCart = (itemId) => {
    // Find the index of the item to be removed
    console.log(itemId);
    const index = cartItems.items.findIndex(item => item.id === itemId);
    console.log(index);
    
    if (index !== -1) {
        // Remove the item from the cart
        cartItems.items.splice(index, 1);
    }
};

// const { props } = usePage()
// console.log(props.auth.user)

</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-200">
            <nav class="bg-[#06255b] border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Dashboard
                                </NavLink>
                                <NavLink :href="route('catalogue.index')"
                                    :active="route().current('catalogue.index')">
                                    Catalogue
                                </NavLink>                                
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <!-- Settings Dropdown -->
                            <div class="flex gap-4 ms-3 relative">
                                <Dropdown align="left" width="64">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 mr-1">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                                </svg>
                                                Cart
                                            </button>
                                        </span>
                                    </template>

                                    <!-- Dropdown Cart Content -->
                                    <template #content>
                                        <div class="flex flex-col items-center w-full" @click.stop>
                                            <!-- Check if cart is empty -->
                                            <p v-if="cartItems.items.length === 0" class="text-sm text-gray-500">Your cart is empty.</p>

                                            <div class="flex flex-col items-center w-full mt-2" v-else>
                                                <!-- Show items in the cart -->
                                                <div v-for="item in cartItems.items" :key="item.id" class="w-full border-b text-xs text-gray-600 flex items-center">
                                                    <div class="flex-1 text-left ml-2">
                                                        <span>{{ item.name }} (x{{ item.quantity }})</span>
                                                    </div>
                                                    <div class="flex-1 text-right ">
                                                        <span>${{ (item.price * item.quantity).toFixed(2) }}</span>
                                                    </div>
                                                    <button @click="removeFromCart(item.id)"
                                                            class="ml-2 mr-2 bg-red-500 text-white text-xs px-1 rounded-full">
                                                        x
                                                    </button>
                                                </div>
                                                <p class="mt-2 font-bold">Total: ${{ cartTotal.toFixed(2) }}</p>
                                                <div class="flex items-center mt-2">
                                                    <button class="bg-[#06255b] text-white text-xs px-2 py-1 rounded">
                                                        View Cart
                                                    </button>
                                                </div>
                                            </div>                               
                                        </div>
                                    </template>
                                </Dropdown>                                      

                                <!-- Profile Dropdown -->
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                {{ $page.props.auth.user.name }}
                                                <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')">
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{
                                        hidden: showingNavigationDropdown,
                                        'inline-flex': !showingNavigationDropdown,
                                    }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{
                                        hidden: !showingNavigationDropdown,
                                        'inline-flex': showingNavigationDropdown,
                                    }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('tickets.index')" :active="route().current('tickets.index')">
                            Tickets
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('administration')" :active="route().current('administration')">
                            Administration
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')"> Profile </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
