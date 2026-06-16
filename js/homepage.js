const shopBtn = document.getElementById("shopBtn");
const dropdown = document.querySelector(".dropdown");

if (shopBtn && dropdown) {
    shopBtn.addEventListener("click", (e) => {
        e.preventDefault();
        dropdown.classList.toggle("active");
    });

    document.addEventListener("click", (e) => {
        if (!dropdown.contains(e.target)) {
            dropdown.classList.remove("active");
        }
    });
}

let index = 0;
let cart = 0;

const slider = document.getElementById("slider");
const nextBtn = document.querySelector(".next");
const prevBtn = document.querySelector(".prev");
const addBtns = document.querySelectorAll(".add-btn");
const cartCount = document.getElementById("cartCount");


if (slider && nextBtn && prevBtn) {
    function getItemWidth() {
        if (!slider.children.length) return 280;
        const style = window.getComputedStyle(slider.children[0]);
        return slider.children[0].offsetWidth + parseFloat(style.marginLeft) + parseFloat(style.marginRight);
    }

    let index = 0;

    function canSlide() {
        const visibleWidth = slider.parentElement.offsetWidth;
        const visibleItems = Math.floor(visibleWidth / getItemWidth());
        return slider.children.length > visibleItems;
    }

    function move(step) {
        if (!canSlide()) return;

        const total = slider.children.length;
        const visibleWidth = slider.parentElement.offsetWidth;
        const currentItemWidth = getItemWidth();
        const visibleItems = Math.floor(visibleWidth / currentItemWidth);

        let maxIndex = total - visibleItems;
        if (maxIndex < 0) maxIndex = 0;

        index += step;

        if (index < 0) index = 0;
        if (index > maxIndex) index = maxIndex;

        slider.style.transform = `translateX(-${index * currentItemWidth}px)`;
    }
    
    window.addEventListener("resize", () => {
        index = 0;
        slider.style.transform = `translateX(0px)`;
    });

    nextBtn.addEventListener("click", () => move(1));
    prevBtn.addEventListener("click", () => move(-1));

    setInterval(() => {
        move(1);
    }, 3000);

    // Optional: disable buttons visually
    function updateButtons() {
        const disabled = !canSlide();
        nextBtn.disabled = disabled;
        prevBtn.disabled = disabled;
    }

    updateButtons();
    window.addEventListener("resize", updateButtons);
}

// Add to cart works on any page with .add-btn + #cartCount
if (addBtns.length && cartCount) {
    addBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            cart++;
            cartCount.innerText = cart;
        });
    });
}

document.addEventListener("DOMContentLoaded", () => {
    const elements = document.querySelectorAll(".fade-in");

    if (!elements.length) return;

    function revealOnView() {
        elements.forEach((el) => {
            const pos = el.getBoundingClientRect().top;
            if (pos < window.innerHeight - 100) {
                el.classList.add("show");
            }
        });
    }

    revealOnView();
    window.addEventListener("scroll", revealOnView, { passive: true });
});


