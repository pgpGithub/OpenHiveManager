var lon = parseFloat($('#map').data('longitude'));
var lat = parseFloat($('#map').data('latitude'));

var coordinate = ol.proj.transform(
    [lon, lat],
    'EPSG:4326',
    'EPSG:3857'
  );

var map = new ol.Map({
  controls: ol.control.defaults().extend([
    new ol.control.OverviewMap()
  ]),
  layers: [
    new ol.layer.Tile({
      source: new ol.source.OSM()
    })
  ],
  target: 'map',
  view: new ol.View({
    center: coordinate,
    zoom: 13
  })
});

map.addOverlay(new ol.Overlay({
  position: coordinate,
  element: $('<img src="../../../location.png">')
  .css({marginTop: '-275%', marginLeft: '-50%'})
}));