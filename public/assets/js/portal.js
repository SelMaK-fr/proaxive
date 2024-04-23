(function () {
    const buttonUserAside = document.getElementById('user-btn-mobile');
    if(buttonUserAside){
        const asideUser = document.getElementById('user-aside');
        const asideWrapper = document.querySelector('.portal-topbar-wrapper');
        buttonUserAside.addEventListener('click', function (e) {
            e.preventDefault();
            asideUser.classList.toggle('deployed');
            if(asideWrapper.classList.contains('aside-deployed')){
                asideWrapper.classList.remove('aside-deployed');
            } else {
                asideWrapper.classList.add('aside-deployed');
            }
        });
    }
})();