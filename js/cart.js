let cart = [];

function addToCart(name, price) {
    cart.push({name, price});
    updateCart();
}

function updateCart() {
    const cartDiv = document.getElementById("cartItems");
    const totalSpan = document.getElementById("total");

    cartDiv.innerHTML = "";
    let total = 0;

    cart.forEach(item => {
        const div = document.createElement("div");
        div.textContent = `${item.name} - LKR ${item.price}`;
        cartDiv.appendChild(div);

        total += item.price;
    });

    totalSpan.textContent = total;
}