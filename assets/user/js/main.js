$(function ($) {
    "use strict";

    
    $('#sidemenu .nav .nav-item.dropdown').on('click', function (event) {
        event.preventDefault();

        $(this).find('.dropdown-menu').toggle();
        $(this).find('.toggle-icon').toggleClass('active');

    });

});