<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle functionality
    const sidebarToggle = document.getElementById('sidebarToggle');
    const adminSidebar = document.getElementById('adminSidebar');
    const mainContent = document.querySelector('.main-content');
    
    if (sidebarToggle && adminSidebar) {
        sidebarToggle.addEventListener('click', function() {
            adminSidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            
            // Save state in localStorage
            localStorage.setItem('sidebarCollapsed', adminSidebar.classList.contains('collapsed'));
        });
        
        // Initialize from localStorage
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            adminSidebar.classList.add('collapsed');
            mainContent.classList.add('expanded');
        }
    }
    
    // Dropdown menu functionality
    const menuToggles = document.querySelectorAll('.menu-toggle');
    
    menuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const parentItem = this.closest('.menu-item');
            
            if (parentItem) {
                parentItem.classList.toggle('active');
                
                // Close other open menus
                document.querySelectorAll('.menu-item').forEach(item => {
                    if (item !== parentItem) {
                        item.classList.remove('active');
                    }
                });
            }
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.menu-item')) {
            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('active');
            });
        }
    });
});
</script>