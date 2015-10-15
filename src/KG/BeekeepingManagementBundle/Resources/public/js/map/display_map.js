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
  element: $('<img src="../../../../location.png">')
  .css({marginTop: '-275%', marginLeft: '-50%'})
}));