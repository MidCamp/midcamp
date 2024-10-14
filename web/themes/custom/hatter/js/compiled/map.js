
(function ($) {

  Drupal.behaviors.map = {
    attach: function (context, settings) {
      mapboxgl.accessToken = 'pk.eyJ1IjoibWlkY2FtcCIsImEiOiJjaXV2czRteHQwNGhkMm9zNWp4bWhndTk0In0.F5jwcKus8Rzx5r8Ga-CRQA';
      var map = new mapboxgl.Map({
        container: 'map', // container id
        style: 'mapbox://styles/midcamp/ciuvsjxje00312ipqrfyxm4di', //stylesheet location
        center: [-87.66, 41.924], // starting position
        zoom: 15 // starting zoom
      });
// disable map zoom when using scroll
      map.scrollZoom.disable();
    }
  };

})(jQuery, Drupal);

//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiIiwic291cmNlcyI6WyJtYXAuanMiXSwic291cmNlc0NvbnRlbnQiOlsiXG4oZnVuY3Rpb24gKCQpIHtcblxuICBEcnVwYWwuYmVoYXZpb3JzLm1hcCA9IHtcbiAgICBhdHRhY2g6IGZ1bmN0aW9uIChjb250ZXh0LCBzZXR0aW5ncykge1xuICAgICAgbWFwYm94Z2wuYWNjZXNzVG9rZW4gPSAncGsuZXlKMUlqb2liV2xrWTJGdGNDSXNJbUVpT2lKamFYVjJjelJ0ZUhRd05HaGtNbTl6TldwNGJXaG5kVGswSW4wLkY1andjS3VzOFJ6eDVyOEdhLUNSUUEnO1xuICAgICAgdmFyIG1hcCA9IG5ldyBtYXBib3hnbC5NYXAoe1xuICAgICAgICBjb250YWluZXI6ICdtYXAnLCAvLyBjb250YWluZXIgaWRcbiAgICAgICAgc3R5bGU6ICdtYXBib3g6Ly9zdHlsZXMvbWlkY2FtcC9jaXV2c2p4amUwMDMxMmlwcXJmeXhtNGRpJywgLy9zdHlsZXNoZWV0IGxvY2F0aW9uXG4gICAgICAgIGNlbnRlcjogWy04Ny42NiwgNDEuOTI0XSwgLy8gc3RhcnRpbmcgcG9zaXRpb25cbiAgICAgICAgem9vbTogMTUgLy8gc3RhcnRpbmcgem9vbVxuICAgICAgfSk7XG4vLyBkaXNhYmxlIG1hcCB6b29tIHdoZW4gdXNpbmcgc2Nyb2xsXG4gICAgICBtYXAuc2Nyb2xsWm9vbS5kaXNhYmxlKCk7XG4gICAgfVxuICB9O1xuXG59KShqUXVlcnksIERydXBhbCk7XG4iXSwiZmlsZSI6Im1hcC5qcyJ9
