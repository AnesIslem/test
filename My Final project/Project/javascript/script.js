document.addEventListener("DOMContentLoaded", function () {
    const submenuToggles = document.querySelectorAll(".submenu-toggle");

    submenuToggles.forEach(toggle => {
        toggle.addEventListener("click", function (e) {
            e.preventDefault();
            let submenu = this.nextElementSibling;
            submenu.style.display = submenu.style.display === "block" ? "none" : "block";
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const menuItems = document.querySelectorAll('.menu-item');
    
    menuItems.forEach(item => {
        const toggle = item.querySelector('.menu-toggle');
        
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            item.classList.toggle('active');
            
            // Close other open menus
            menuItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.classList.remove('active');
                }
            });
        });
    });
    
    // Toggle sidebar collapse (optional)
    const collapseBtn = document.createElement('div');
    collapseBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
    collapseBtn.style.position = 'absolute';
    collapseBtn.style.right = '10px';
    collapseBtn.style.top = '20px';
    collapseBtn.style.cursor = 'pointer';
    collapseBtn.addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('collapsed');
        this.querySelector('i').classList.toggle('fa-chevron-left');
        this.querySelector('i').classList.toggle('fa-chevron-right');
    });
    document.querySelector('.sidebar-header').appendChild(collapseBtn);
});