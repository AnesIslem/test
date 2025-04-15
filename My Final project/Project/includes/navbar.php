<?php
// Check if session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- Navbar -->
<nav class="admin-navbar">
    <div class="navbar-container">
        <!-- Sidebar Toggle -->
        <button id="sidebarToggle" class="navbar-toggle">
    <i class="fas fa-bars"></i>
</button>
        
        <!-- Brand/Logo -->
        <div class="navbar-brand">
            <span>Admin Panel</span>
        </div>
        
        <!-- User Menu -->
        <div class="navbar-user">
            <span class="username"><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></span>
            <div class="user-dropdown">
                <i class="fas fa-user-circle"></i>
                <ul class="dropdown-menu">
                    <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<style>
    /* Navbar Styles */
    .admin-navbar {
        background-color: #2c3e50;
        color: white;
        padding: 0 20px;
        height: 60px;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .navbar-container {
        display: flex;
        align-items: center;
        height: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .navbar-toggle {
        background: none;
        border: none;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        margin-right: 20px;
    }
    
    .navbar-brand {
        flex-grow: 1;
        font-size: 1.2rem;
        font-weight: bold;
    }
    
    .navbar-user {
        position: relative;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .user-dropdown {
        position: relative;
        cursor: pointer;
    }
    
    .user-dropdown i {
        font-size: 1.5rem;
    }
    
    .dropdown-menu {
        position: absolute;
        right: 0;
        top: 100%;
        background: white;
        min-width: 150px;
        border-radius: 4px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        list-style: none;
        padding: 10px 0;
        margin: 5px 0 0 0;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s;
    }
    
    .user-dropdown:hover .dropdown-menu {
        opacity: 1;
        visibility: visible;
    }
    
    .dropdown-menu a {
        display: flex;
        align-items: center;
        padding: 8px 15px;
        color: #333;
        text-decoration: none;
        transition: background 0.2s;
    }
    
    .dropdown-menu a:hover {
        background: #f5f5f5;
    }
    
    .dropdown-menu i {
        margin-right: 10px;
        font-size: 1rem;
        color: #555;
        width: 20px;
    }
</style>

<script>
// Toggle user dropdown (optional)
document.addEventListener('DOMContentLoaded', function() {
    const userDropdown = document.querySelector('.user-dropdown');
    if (userDropdown) {
        userDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
            const menu = this.querySelector('.dropdown-menu');
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
        });
        
        // Close when clicking elsewhere
        document.addEventListener('click', function() {
            const menus = document.querySelectorAll('.dropdown-menu');
            menus.forEach(menu => {
                menu.style.display = 'none';
            });
        });
    }
});
</script>