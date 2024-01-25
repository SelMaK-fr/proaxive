let navbarToggler = document.querySelector('.aside-deploy');
let navbarDropdown = document.querySelector('#navbarNavDropdown');
let navbarDropdownExpanded = document.documentElement.getAttribute('data-sidebar-mobile');

// Mobile menu toggle

navbarToggler.addEventListener('click', function(){

    if(navbarDropdownExpanded === "active") {
        navbarDropdownExpanded = "inactive";
        document.querySelector('.overlay').style.display = '';
    } else {
        navbarDropdownExpanded = "active";
        document.querySelector('.overlay').style.display = 'block';
        navbarDropdown.addEventListener('click', function() {
            document.documentElement.setAttribute("data-sidebar-mobile", "inactive");
            document.querySelector('.overlay').style.display = '';
        })
    }
    document.documentElement.setAttribute("data-sidebar-mobile", navbarDropdownExpanded);
});

// Dropdown toggle

function getTogglerId(className, event, fn) {
    let list = document.querySelectorAll(className);
    for (let i = 0, len = list.length; i < len; i++) {
        list[i].addEventListener(event, fn, false);
    }
}

getTogglerId('.dropdown-toggle', 'click', toggleDropdown);

let dropdownMenus = document.querySelectorAll('.dropdown-menu');
let dropdownTogglers = document.querySelectorAll('.dropdown-toggle');

function closeMenus(){
    for (let j = 0; j < dropdownMenus.length; j++) {
        dropdownMenus[j].classList.remove('show');
    }
    for (let k = 0; k < dropdownTogglers.length; k++) {
        dropdownTogglers[k].classList.remove('show');
        dropdownTogglers[k].setAttribute('aria-expanded','false');
    }
}

function toggleDropdown(e) {
    let isOpen = this.classList.contains('show');

    if (!isOpen) {
        closeMenus();
        document.querySelector(`[aria-labelledby=${this.id}]`).classList.add('show');
        this.classList.add('show');
        this.setAttribute('aria-expanded','true');
    } else if (isOpen) {
        closeMenus();
    }

    e.preventDefault();
}

// Close dropdowns on focusout

let navbar = document.querySelector('.navbar');

if(navbar){
    navbar.addEventListener('focusout', function() {

        window.onclick = function(event){
            if (document.querySelector('.navbar').contains(event.target)){
                return;
            } else{
                closeMenus();
            }
        };
    });
}