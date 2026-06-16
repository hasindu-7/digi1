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
    const itemWidth = 280;
    let index = 0;

    function canSlide() {
        const visibleWidth = slider.parentElement.offsetWidth;
        const visibleItems = Math.floor(visibleWidth / itemWidth);
        return slider.children.length > visibleItems;
    }

    function move(step) {
    if (!canSlide()) return;

    const total = slider.children.length;

    const visibleWidth = slider.parentElement.offsetWidth;
    const visibleItems = Math.floor(visibleWidth / itemWidth);

    const maxIndex = total - visibleItems;

    index += step;

    if (index < 0) index = 0;
    if (index > maxIndex) index = maxIndex;

    slider.style.transform = `translateX(-${index * itemWidth}px)`;
    }

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

/* --- MOBILE MENU TOGGLE --- */
document.addEventListener("DOMContentLoaded", () => {
    const menuToggle = document.getElementById("menuToggle");
    const mainNav = document.getElementById("mainNav");
    
    if (menuToggle && mainNav) {
        menuToggle.addEventListener("click", (e) => {
            e.stopPropagation();
            mainNav.classList.toggle("active");
        });
        
        // Close menu when clicking on a link
        const navLinks = mainNav.querySelectorAll("a");
        navLinks.forEach(link => {
            link.addEventListener("click", () => {
                mainNav.classList.remove("active");
            });
        });
        
        // Close menu when clicking outside
        document.addEventListener("click", (e) => {
            if (!e.target.closest(".main-header")) {
                mainNav.classList.remove("active");
            }
        });
    }
});

