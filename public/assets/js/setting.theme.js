/****
 By SelMaK
 */

// Theme Switcher
function setTheme(styleName){
    localStorage.setItem('data-layout-mode', styleName);
    document.documentElement.setAttribute("data-layout-mode", styleName)
}
// function to toggle between light and dark theme
function toggleTheme() {
    if (localStorage.getItem('data-layout-mode') === 'default'){
        setTheme('light');
    } else {
        setTheme('default');
    }
}
// Immediately invoked function to set the theme on initial load
(function () {
    if (localStorage.getItem('data-layout-mode') === 'default') {
        setTheme('default');
    } else {
        setTheme('light');
    }
})();
// Aside Mobile Device
document.querySelector('.aside-deploy').addEventListener('click', function(e){
    if (document.documentElement.getAttribute('data-sidebar-mobile') === 'inactive'){
        document.documentElement.setAttribute("data-sidebar-mobile", "active")
    } else {
        document.documentElement.setAttribute("data-sidebar-mobile", "inactive")

    }
});
// Global aside
(function (){
    if(document.querySelector('.aside') != null){
        document.querySelector('.aside').addEventListener('click', function(e){
            document.querySelector('.aside').classList.toggle('deployed');
        })
    }
})();
// Sidebar Style
// function to set a given theme/color-scheme
function setSidebar(sidebarStatus) {
    localStorage.setItem('data-sidebar-width', sidebarStatus);
    document.documentElement.setAttribute("data-sidebar-width", sidebarStatus)
}
// function to toggle between lg and sm aside navigation
function toggleSidebarStatus() {
    if (localStorage.getItem('data-sidebar-width') === 'lg'){
        setSidebar('sm');
    } else {
        setSidebar('lg');
    }
}
// Immediately invoked function to set the theme on initial load
(function () {
    if (localStorage.getItem('data-sidebar-width') === 'lg') {
        setSidebar('lg');
    } else {
        setSidebar('sm');
    }
})();

/** Menu **/
submenu_theme('.dropdown', '.submenu-dropdown', 'open');

/**
 * Modal
 */
(function () {
    document.addEventListener('click', function (e) {
        let target = e.target;

        if (target.hasAttribute('data-toggle') && target.getAttribute('data-toggle') === 'modal') {
            if (target.hasAttribute('data-target')) {
                let m_ID = target.getAttribute('data-target');
                document.getElementById(m_ID).classList.add('deployed');
                let body = document.getElementById("body-wrapper");
                body.style.overflow = 'hidden';
                e.preventDefault();
            }
        }

        // Close modal window with 'data-dismiss' attribute or when the backdrop is clicked
        if ((target.hasAttribute('data-dismiss') && target.getAttribute('data-dismiss') === 'modal') || target.classList.contains('modal')) {
            let modal = document.querySelector('[class="modal deployed"]');
            modal.classList.remove('deployed');
            let body = document.getElementById("body-wrapper");
            body.style.overflow = '';
            e.preventDefault();
        }
    }, false);
})()

/* Tools settings */
submenu_theme('.admin-tools', '.table-submenu', 'open');

/**
 * SubMenu
 * @param classContainer
 * @param classSubmenu
 * @param classOpen
 * @param stopPropage
 */
function submenu_theme(classContainer, classSubmenu, classOpen, stopPropage = false) {
    (function() {
        let cm = document.querySelectorAll(classContainer);

        cm.forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                let sm = btn.querySelector(classSubmenu);
                let shouldOpen = !sm.classList.contains(classOpen);
                let om = document.querySelectorAll(classSubmenu);
                om.forEach(function(menus) {
                    menus.classList.remove(classOpen);
                });
                if (shouldOpen) {
                    sm.classList.add(classOpen);
                }
                if (stopPropage === true){
                    e.preventDefault();
                }
            });
        });
    })();
}
setTimeout( function(){
    const oMsg = document.getElementById('message-flash');
    if(oMsg){
        oMsg.style.display = 'none';
    }
}, 4000);



