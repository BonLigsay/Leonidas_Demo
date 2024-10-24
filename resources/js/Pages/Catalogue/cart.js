import { reactive } from 'vue';

const cart = reactive({
    items: []
});

const addToCart = (item) => {
    // Check if the item already exists in the cart
    const existingItem = cart.items.find(cartItem => cartItem.id === item.id);
    if (existingItem) {
        // If it exists, update the quantity
        existingItem.quantity += item.quantity;
    } else {
        // If it doesn't exist, add a new item to the cart
        cart.items.push(item);
    }
};

// Exporting the cart store
export default {
    cart,
    addToCart,
};
