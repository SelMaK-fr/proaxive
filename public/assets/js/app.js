
(function () {
    window.addEventListener("load", function() {
        // store tabs variable
        var myTabs = document.querySelectorAll("ul.js-tabs > li a");
        function myTabClicks(tabClickEvent) {
            for (var i = 0; i < myTabs.length; i++) {
                myTabs[i].classList.remove("selected");
            }
            var clickedTab = tabClickEvent.currentTarget;
            clickedTab.classList.add("selected");
            tabClickEvent.preventDefault();
            var myContentPanes = document.querySelectorAll(".tab-pane");
            for (i = 0; i < myContentPanes.length; i++) {
                myContentPanes[i].classList.remove("selected");
            }
            var anchorReference = tabClickEvent.target;
            var activePaneId = anchorReference.getAttribute("href");
            var activePane = document.querySelector(activePaneId);
            activePane.classList.add("selected");
        }
        for (i = 0; i < myTabs.length; i++) {
            myTabs[i].addEventListener("click", myTabClicks)
        }
    });
})();
