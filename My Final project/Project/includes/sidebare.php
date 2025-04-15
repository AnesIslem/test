
<!-- Admin Sidebar -->
<aside class="admin-sidebar" id="sidebar">
    <div class="sidebar-header">
        <h3>Navigation</h3>
    </div>
    
    <ul class="sidebar-menu">
        <!-- Clients Section -->
        <li class="menu-item">
            <a href="#" class="menu-toggle">
                <i class="fas fa-users"></i>
                <span>Gestion des Clients</span>
                <i class="dropdown-icon fas fa-angle-down"></i>
            </a>
            <ul class="submenu">
                <li><a href="list_clients.php"><i class="fas fa-list"></i> Liste des Clients</a></li>
                <li><a href="formulaire_client.php"><i class="fas fa-plus"></i> Ajouter Client</a></li>
                <li><a href="type_de_recherch.php"><i class="fas fa-search"></i> Rechercher Client</a></li>
                <li><a href="type_de_recherch.php"><i class="fas fa-minus"></i> Supprimer Client</a></li>
                <li><a href="type_de_recherch.php"><i class="fas fa-edit"></i> Modifier Client</a></li>
            </ul>
        </li>
        
        <!-- Apartments Section -->
        <li class="menu-item">
            <a href="#" class="menu-toggle">
                <i class="fas fa-building"></i>
                <span>Gestion des Appartements</span>
                <i class="dropdown-icon fas fa-angle-down"></i>
            </a>
            <ul class="submenu">
                <li><a href="list_app.php"><i class="fas fa-list"></i> Liste des Appartements</a></li>
                <li><a href="ajouter_app.php"><i class="fas fa-plus"></i> Ajouter Appartement</a></li>
                <li><a href="#"><i class="fas fa-search"></i> Rechercher Appartement</a></li>
            </ul>
        </li>
        
        <!-- Other Links -->
        <li class="menu-item">
            <a href="#">
                <i class="fas fa-envelope"></i>
                <span>Contact</span>
            </a>
        </li>
        
        <li class="menu-item logout">
            <a href="logout.php">
                <i class="fas fa-sign-out-alt"></i>
                <span>Déconnexion</span>
            </a>
        </li>
    </ul>
</aside>

<style>
    /* Sidebar Styles */
    .admin-sidebar {
        width: 250px;
        background: #34495e;
        color: white;
        position: fixed;
        height: calc(100vh - 60px);
        top: 60px;
        transition: transform 0.3s;
        z-index: 999;
    }
    
    .sidebar-header {
        padding: 15px 20px;
        border-bottom: 1px solid #2c3e50;
    }
    
    .sidebar-header h3 {
        margin: 0;
        font-size: 1.1rem;
    }
    
    .sidebar-menu {
        list-style: none;
        padding: 10px 0;
        overflow-y: auto;
        height: calc(100% - 51px);
    }
    
    .menu-item a {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        color: #ecf0f1;
        text-decoration: none;
        transition: background 0.3s;
    }
    
    .menu-item a:hover {
        background: #2c3e50;
    }
    
    .menu-item i:first-child {
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }
    
    .dropdown-icon {
        margin-left: auto;
        transition: transform 0.3s;
    }
    
    .submenu {
        list-style: none;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
        background: #2c3e50;
    }
    
    .submenu li a {
        padding-left: 40px;
        font-size: 0.9rem;
    }
    
    .menu-item.active .dropdown-icon {
        transform: rotate(180deg);
    }
    
    .menu-item.active .submenu {
        max-height: 500px;
    }
    
    .logout {
        border-top: 1px solid #2c3e50;
        margin-top: 10px;
    }
</style>