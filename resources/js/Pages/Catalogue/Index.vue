<<template>
    <Toast />
    <ConfirmDialog></ConfirmDialog>

    <Head title="Chocolate" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Chocolate Map</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg text-gray-800">
                    <div class="p-6 bg-white border-b border-2">
                        <div class="relative">
                            <!-- Main Image of Chocolates -->
                            <img src="/storage/chocolates.jpg" alt="Chocolates" class="w-full h-auto" />

                            <!-- Hotspots for each chocolate -->
                            <div v-for="chocolate in chocolates" 
                                :key="chocolate.id"
                                :style="{ top: chocolate.top, left: chocolate.left }" 
                                class="absolute w-8 h-8 rounded-full bg-transparent cursor-pointer hover-overlay"
                                @mouseover="showPopup(chocolate.id, chocolate.top, chocolate.left)">
                            </div>

                            <!-- Pop-up that stays open until clicking outside -->
                            <div v-if="popUpOpen" 
                                class="flex flex-col items-center absolute w-40 p-2 bg-white border border-gray-200 rounded shadow-lg popup-container"
                                :style="{ top: popupTop, left: popupLeft }">
                                <img :src="getChocolateDetails(hoveredChocolate).image" alt="Chocolate Image" class="w-16 h-auto mb-2">
                                <h5 class="text-lg font-bold">{{ getChocolateDetails(hoveredChocolate).name }}</h5>
                                <p class="text-xs text-justify text-gray-600">{{ getChocolateDetails(hoveredChocolate).description }}</p>
                                <ul class="list-disc text-xs text-justify text-gray-600 pl-4">
                                    <li>Energy: {{ getChocolateDetails(hoveredChocolate).facts[0] }} cal</li>
                                    <li>Fat: {{ getChocolateDetails(hoveredChocolate).facts[1] }}g</li>
                                    <li>Carbohydrates: {{ getChocolateDetails(hoveredChocolate).facts[2] }}g</li>
                                </ul>

                                <!-- Add to Cart Button -->
                                <div class="flex items-center mt-2">
                                    <button @click="decrementQuantity(hoveredChocolate)"
                                            class="bg-red-500 text-white px-2 rounded-full" 
                                            :disabled="quantities[hoveredChocolate] <= 0">
                                        -
                                    </button>
                                    <span class="mx-2">{{ quantities[hoveredChocolate] || 0 }}</span>
                                    <button @click="incrementQuantity(hoveredChocolate)"
                                            class="bg-[#06255b] text-white px-2 rounded-full">
                                        +
                                    </button>                                    
                                </div>
                                <div class="flex items-center mt-2">
                                    <button @click="addToCart(getChocolateDetails(hoveredChocolate))"
                                            class="bg-[#FFFDD0] text-[#06255b] text-xs px-2 py-1 rounded">
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>                
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Head, Link, usePage } from '@inertiajs/vue3';
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";
import { usePermission } from '@/Composables/permissions.js';
import { ref, onMounted, onBeforeUnmount, computed, reactive} from 'vue';
import cartStore from '@/Pages/Catalogue/cart';

// Chocolate data
const chocolates = ref([
    { id: 1, name: 'Advocaat Dark', description: 'Dark chocolate, advocaat-flavoured creme confiseur', top: '30%', left: '25%', price: 5.99, image: '/storage/advocaat_dark.png', facts: ['613', '7', '16']},
    { id: 2, name: 'Alexandre le Grand', description: 'Dark chocolate, caramel cream', top: '10%', left: '55%', price: 5.99, image: '/storage/alexandre_le_grand.png', facts: ['550', '7', '13']},
    { id: 3, name: 'Cerise Dark', description: 'Dark chocolate, whole morello cherry in liqueur cream', top: '80%', left: '18%', price: 5.99, image: '/storage/cerise_dark.png', facts: ['298', '3', '8']},
    { id: 4, name: 'Avelanne', description: 'Milk chocolate, milk chocolate ganche with hazlenut cream', top: '30%', left: '48%', price: 5.99, image: '/storage/avelanne.png', facts: ['577', '8', '14']},
    { id: 5, name: 'Bergamote', description: 'Milk chocolate, bergamot-flavoured caramel cream', top: '50%', left: '70%', price: 5.99, image: '/storage/bergamote.png', facts: ['475 ', '6', '12']},
    { id: 6, name: 'Buche white', description: 'White chocolate, tender praline', top: '75%', left: '45%', price: 5.99, image: '/storage/buche_white.png', facts: ['613', '7', '16']}
]);

// Reactive state
const hoveredChocolate = ref(null);
const popUpOpen = ref(false);
const quantities = ref({});

const incrementQuantity = (chocolateId) => {
    quantities.value[chocolateId] = (quantities.value[chocolateId] || 0) + 1;
};

// Function to decrement quantity
const decrementQuantity = (chocolateId) => {
    if (quantities.value[chocolateId] > 0) {
        quantities.value[chocolateId] -= 1;
    }
};

// Coordinates for pop-up positioning
const popupTop = ref('0px');
const popupLeft = ref('0px');

// Function to get details of the hovered chocolate
const getChocolateDetails = (id) => chocolates.value.find(chocolate => chocolate.id === id);

// Function to handle clicking outside the pop-up
const handleClickOutside = (event) => {
    const popUpElement = document.querySelector('.popup-container');
    if (popUpOpen.value && popUpElement && !popUpElement.contains(event.target)) {
        popUpOpen.value = false;
        hoveredChocolate.value = null;
    }
};

// Add event listeners on mount and remove them when component is destroyed
onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});

// Function to show pop-up on hover or manually trigger it
const showPopup = (chocolateId, top, left) => {
    hoveredChocolate.value = chocolateId;
    popUpOpen.value = true;

    if (top >= '50%') {
        console.log('top');
        popupTop.value = `calc(${top} - 200px)`;
    } else {
        console.log('bottom');
        popupTop.value = `calc(${top} + 50px)`;
    }
    popupLeft.value = `calc(${left} + 5px)`;
};

// Function to close the pop-up
const closePopup = () => {
    popUpOpen.value = false;
    hoveredChocolate.value = null;
};


// Reactive cart
const cart = reactive({
    items: []
});

const addToCart = (chocolate) => {
    const quantity = quantities.value[chocolate.id] || 0;
    if (quantity > 0) {
        // Use cartStore to add the item to the cart
        cartStore.addToCart({
            ...chocolate,
            quantity // Add the quantity here
        });
        
        console.log(`Added ${quantity} of ${chocolate.name} to the cart.`);
        quantities.value[chocolate.id] = 0; // Reset quantity after adding to cart
    }
};

// Compute total items in cart
const cartItems = computed(() => {
    return cart.value.reduce((total, item) => total + item.quantity, 0);
});
</script>
<style>
/* CSS for smooth transitions */
.hover-overlay {
    position: absolute;
    transition: background-color 0.3s ease;
}

.hover-overlay:hover {
    background-color: rgba(255, 255, 255, 0.5); /* Light background on hover */
}

.popup {
    z-index: 10000;
    transition: opacity 0.3s ease-in-out;
}
.popup-container {
    z-index: 1000;
}

</style>