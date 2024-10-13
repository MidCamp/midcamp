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

//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiIiwic291cmNlcyI6WyJzaXRlLmpzIl0sInNvdXJjZXNDb250ZW50IjpbIi8qKlxuICogQGZpbGVcbiAqIEEgSmF2YVNjcmlwdCBmaWxlIGZvciB0aGUgc2l0ZS5cbiAqXG4gKiBPdXIgSmF2YVNjcmlwdCBtdXN0IGJlIHdyYXBwZWQgaW4gYSBjbG9zdXJlLlxuICogQHNlZSBodHRwczovL2RydXBhbC5vcmcvbm9kZS8xNDQ2NDIwXG4gKiBAc2VlIGh0dHA6Ly93d3cuYWRlcXVhdGVseWdvb2QuY29tLzIwMTAvMy9KYXZhU2NyaXB0LU1vZHVsZS1QYXR0ZXJuLUluLURlcHRoXG4gKlxuICogQGNvcHlyaWdodCBDb3B5cmlnaHQgMjAxNiBQYWxhbnRpci5uZXRcbiAqL1xuXG4oZnVuY3Rpb24gKCQpIHtcblxuICBEcnVwYWwuYmVoYXZpb3JzLnNpdGVOYXYgPSB7XG4gICAgYXR0YWNoOiBmdW5jdGlvbiAoY29udGV4dCkge1xuICAgICAgLy8gb3BlbiBhbmQgY2xvc2UgbW9iaWxlIG5hdiB3aXRoIGhhbWJ1cmdlciBtZW51XG4gICAgICBjb25zdCAkbmF2VHJpZ2dlcnMgPSAkKCcucHJpbWFyeS1uYXZfX3RyaWdnZXInLCBjb250ZXh0KTtcbiAgICAgICRuYXZUcmlnZ2Vycy5lYWNoKChpbmRleCwgbmF2VHJpZ2dlcikgPT4ge1xuICAgICAgICBjb25zdCAkbmF2VHJpZ2dlciA9ICQobmF2VHJpZ2dlcik7XG4gICAgICAgIGlmICghJG5hdlRyaWdnZXIuaGFzQ2xhc3MoJ3NpdGVOYXYtLXByb2Nlc3NlZCcpKSB7XG4gICAgICAgICAgJG5hdlRyaWdnZXIuYWRkQ2xhc3MoJ3NpdGVOYXYtLXByb2Nlc3NlZCcpO1xuICAgICAgICAgICRuYXZUcmlnZ2VyLmNsaWNrKGZ1bmN0aW9uIChldmVudCkge1xuICAgICAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgIGNvbnN0ICR0aGlzID0gJCh0aGlzKTtcbiAgICAgICAgICAgICR0aGlzLnRvZ2dsZUNsYXNzKCdpcy1hY3RpdmUnKTtcbiAgICAgICAgICAgICR0aGlzLmJsdXIoKTtcbiAgICAgICAgICAgICR0aGlzLnNpYmxpbmdzKCcucHJpbWFyeS1uYXZfX2xpc3QnKS50b2dnbGVDbGFzcygnaXMtYWN0aXZlJyk7XG4gICAgICAgICAgfSk7XG4gICAgICAgIH1cbiAgICAgIH0pO1xuXG4gICAgICAvLyBvcGVuIGFuZCBjbG9zZSBjaGlsZCBtZW51cyBpbiB0aGUgbWFpbiBuYXZcbiAgICAgIGNvbnN0IG5hdlRyaWdnZXJTdWJsaXN0cyA9ICQoJy5wcmltYXJ5LW5hdl9fc3VibGlzdC0tdHJpZ2dlcicsIGNvbnRleHQpO1xuICAgICAgbmF2VHJpZ2dlclN1Ymxpc3RzLmVhY2goKGluZGV4LCBuYXZUcmlnZ2VyU3VibGlzdCkgPT4ge1xuICAgICAgICBjb25zdCAkbmF2VHJpZ2dlclN1Ymxpc3QgPSAkKG5hdlRyaWdnZXJTdWJsaXN0KTtcbiAgICAgICAgaWYgKCEkbmF2VHJpZ2dlclN1Ymxpc3QuaGFzQ2xhc3MoJ3NpdGVOYXYtLXByb2Nlc3NlZCcpKSB7XG4gICAgICAgICAgJG5hdlRyaWdnZXJTdWJsaXN0LmFkZENsYXNzKCdzaXRlTmF2LS1wcm9jZXNzZWQnKTtcbiAgICAgICAgICAkbmF2VHJpZ2dlclN1Ymxpc3QuY2xpY2soZnVuY3Rpb24gKGV2ZW50KSB7XG4gICAgICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgY29uc3QgJHRoaXMgPSAkKHRoaXMpO1xuICAgICAgICAgICAgJHRoaXMucGFyZW50cygnLnByaW1hcnktbmF2X19pdGVtJykudG9nZ2xlQ2xhc3MoJ2lzLWFjdGl2ZScpO1xuICAgICAgICAgICAgJHRoaXMuYmx1cigpO1xuICAgICAgICAgICAgJHRoaXMucGFyZW50cygpLnNpYmxpbmdzKCcucHJpbWFyeS1uYXZfX2l0ZW0nKS5yZW1vdmVDbGFzcygnaXMtYWN0aXZlJyk7XG4gICAgICAgICAgfSk7XG4gICAgICAgIH1cbiAgICAgIH0pO1xuICAgIH1cbiAgfVxufSkoalF1ZXJ5LCBEcnVwYWwpO1xuIl0sImZpbGUiOiJzaXRlLmpzIn0=
