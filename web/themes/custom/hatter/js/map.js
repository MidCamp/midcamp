
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
