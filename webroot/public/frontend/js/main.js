(function ($) {
  // USE STRICT
  "use strict";

  $(window).on("load", function () {
    $("#page-loader").fadeOut("slow");
  });
  
  /* Header Stick */
  $(window).scroll(function (event) {
    /* Act on the event */
    var top = $(document).scrollTop();
    if (top > 82) {
      $('.header-stick').addClass('header-stick--show');
    }
    else {
      $('.header-stick').removeClass('header-stick--show');
    }
  });
  /* Menu Canvas */
  $('.canvas-menu-button').on('click', function () {
    if ($('#menu-canvas').hasClass('menu-canvas--hidden')) {
      $('#menu-canvas').removeClass('menu-canvas--hidden');
      $('#menu-canvas').addClass('menu-canvas--show');
    }
    else {
      $('#menu-canvas').removeClass('menu-canvas--show');
      $('#menu-canvas').addClass('menu-canvas--hidden');
    }
  });
  $('#btn-close').on('click', function () {
    $('#menu-canvas').removeClass('menu-canvas--show');
    $('#menu-canvas').addClass('menu-canvas--hidden');
  });
  
  /* Search */
  $('.search-button__btn').on('click', function () {
    if ($('#header-input').hasClass('form__input--hidden')) {
      $('#header-input').removeClass('form__input--hidden');
      $('#header-input').addClass('form__input--show');
      $('.menu-desktop').removeClass('menu-desktop--show');
      $('.menu-desktop').addClass('menu-desktop--hidden');
    }
    else {
      $('#header-input').removeClass('form__input--show');
      $('#header-input').addClass('form__input--hidden');
      $('.menu-desktop').removeClass('menu-desktop--hidden');
      $('.menu-desktop').addClass('menu-desktop--show');
    }

  });
  /* Menu Mobile */
  $('.menu-mobile__button').on('click', function () {
    if ($(this).hasClass('click')) {
      $(this).removeClass('click');
    }
    else {
      $(this).addClass('click');
    }
    $('nav.menu-mobile').slideToggle("400");
  });

  $('.menu-mobile__more').on('click', function () {

    var sub_list = $(this).parent().find('ul');
    $(this).toggleClass("fa-minus");
    sub_list.slideToggle("400");

  });
  

  // BACK TO TOP BUTTON
  $(window).scroll(function () {
    if ($(this).scrollTop() > 300) {
      $('#btn-to-top').fadeIn('slow');
    } else {
      $('#btn-to-top').fadeOut('slow');
    }
  });
  $('#btn-to-top').on('click', function () {
    $("html, body").animate({ scrollTop: 0 }, 600);
    return false;
  });
})(jQuery);