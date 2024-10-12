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
    attach: function (context) {
      // open and close mobile nav with hamburger menu
      const $navTriggers = $('.primary-nav__trigger', context);
      $navTriggers.each((index, navTrigger) => {
        const $navTrigger = $(navTrigger);
        if (!$navTrigger.hasClass('siteNav--processed')) {
          $navTrigger.addClass('siteNav--processed');
          $navTrigger.click(function (event) {
            event.preventDefault();
            const $this = $(this);
            $this.toggleClass('is-active');
            $this.blur();
            $this.siblings('.primary-nav__list').toggleClass('is-active');
          });
        }
      });

      // open and close child menus in the main nav
      const navTriggerSublists = $('.primary-nav__sublist--trigger', context);
      navTriggerSublists.each((index, navTriggerSublist) => {
        const $navTriggerSublist = $(navTriggerSublist);
        if (!$navTriggerSublist.hasClass('siteNav--processed')) {
          $navTriggerSublist.addClass('siteNav--processed');
          $navTriggerSublist.click(function (event) {
            event.preventDefault();
            const $this = $(this);
            $this.parents('.primary-nav__item').toggleClass('is-active');
            $this.blur();
            $this.parents().siblings('.primary-nav__item').removeClass('is-active');
          });
        }
      });
    }
  }
})(jQuery, Drupal);
