<header class="main-header">
    <div class="logo">
        <a href="../pages/homepage.php">Lanka DigiMart</a>
    </div>
    <button class="menu-toggle" id="menuToggle">☰</button>
<nav class="main-nav" id="mainNav">
        <ul>
            <li><a href="../pages/homepage.php">Home</a></li>
            <li><a href="../pages/shop.php?category=all">Shop All</a></li>
            <li><a href="../pages/about.php">About</a></li>
            <li><a href="../pages/contact.php">Contact</a></li>
            <li><a href="../pages/faq.php">FAQ</a></li>
        </ul>
    </nav>
    <div class="header-actions">
        <a href="../pages/cart.php" class="nav-icon">Cart</a>
        
        <?php 
        if (isset($_SESSION['user_id'])) { 
            
            // IF the user is an admin, show a special gear icon link to the dashboard
            if ($_SESSION['user_role'] === 'admin') {
                echo '<a href="../admin/admin_dashboard.php" style="color: var(--accent-color); margin-right: 15px; font-weight: bold;">⚙️ Admin</a>';
            }
            
            echo '<a href="../pages/profile.php" style="color: var(--text-secondary); margin-right: 15px; font-weight: bold;">Hello, ' . htmlspecialchars($_SESSION['user_name']) . '</a>';
            echo '<a href="../pages/logout.php" class="btn-primary" style="background-color: #e74c3c;">Logout</a>';
        } else {
            echo '<a href="../pages/login.php" class="btn-primary">Login</a>';
        }
        ?>
    </div>
</header>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const menuToggle = document.getElementById("menuToggle");
    const mainNav = document.getElementById("mainNav");
    
    if (menuToggle && mainNav) {
        menuToggle.addEventListener("click", (e) => {
            e.stopPropagation();
            mainNav.classList.toggle("active");
        });
        
        const navLinks = mainNav.querySelectorAll("a");
        navLinks.forEach(link => {
            link.addEventListener("click", () => {
                mainNav.classList.remove("active");
            });
        });
        
        document.addEventListener("click", (e) => {
            if (!e.target.closest(".main-header")) {
                mainNav.classList.remove("active");
            }
        });
    }
});
</script>