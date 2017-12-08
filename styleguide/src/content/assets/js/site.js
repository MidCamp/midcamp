/**
 * @file
 * A JavaScript file for the site.
 *
 * Our JavaScript must be wrapped in a closure.
 * @see https://drupal.org/node/1446420
 * @see http://www.adequatelygood.com/2010/3/JavaScript-Module-Pattern-In-Depth
 *
 * @copyright Copyright 2016 Palantir.net
 */

(function ($) {

  Drupal.behaviors.siteNav = {
    attach: function (context, settings) {
      // open and close mobile nav with hamburger menu
      $('.primary-nav__trigger').click(function (event) {
        $(this).toggleClass('is-active');
        $(this).blur();
        event.preventDefault();
        $(this).siblings('.primary-nav__list').toggleClass('is-active');
      });

      // open and close child menus in the main nav
      $('.primary-nav__sublist--trigger').click(function (event) {
        $(this).parents('.primary-nav__item').toggleClass('is-active');
        $(this).blur();
        event.preventDefault();
        $(this).parents().siblings('.primary-nav__item').removeClass('is-active');
      });
    }
  };

})(jQuery, Drupal);
