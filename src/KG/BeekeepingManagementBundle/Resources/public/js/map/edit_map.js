/* 
 * Copyright (C) 2015 Kévin Grenèche < kevin.greneche at openhivemanager.org >
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

var overlay = new ol.Overlay({
    element: $('<img src="../../../../../../web/location.png">')
    .css({marginTop: '-275%', marginLeft: '-50%'})
});

    
/**
 * Create the map.
 */
var map = new ol.Map({
  controls: ol.control.defaults().extend([
    new ol.control.OverviewMap()
  ]),
  layers: [
    new ol.layer.Tile({
      source: new ol.source.OSM()
    })
  ],
  overlays: [overlay],
  target: 'map',
  view: new ol.View({
    center: [500000, 6000000],
    zoom: 2
  })
});

/**
 * Init overlay 
 */
var lat = parseFloat(document.getElementById($('#map').data('latitude')).value);
var lon = parseFloat(document.getElementById($('#map').data('longitude')).value);
if (!isNaN(lat) && !isNaN(lon)){
    overlay.setPosition(ol.proj.transform([lon, lat], 'EPSG:4326', 'EPSG:3857'));
}
    
/**
 * Add a click handler to the map to render the popup.
 */
map.on('singleclick', function(evt) {
    var coordinate = evt.coordinate;
    var coordinate_conv = ol.proj.transform(coordinate, 'EPSG:3857', 'EPSG:4326');

    var xy = ol.coordinate.toStringXY(coordinate_conv, 6);
    overlay.setPosition(coordinate);

    var latitude  = '{y}';
    var longitude = '{x}';

    var form_latitude = document.getElementById($('#map').data('latitude'));
    var form_longitude = document.getElementById($('#map').data('longitude'));
    form_latitude.setAttribute('value', ol.coordinate.format(coordinate_conv, latitude, 6));
    form_longitude.setAttribute('value',ol.coordinate.format(coordinate_conv, longitude, 6));
});