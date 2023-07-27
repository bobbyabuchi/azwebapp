function initOpenStreetMap(t){jQuery(".sppb-addon-openstreetmap",t).each(function(t){var a,i=jQuery(this).attr("id"),o=jQuery(this).attr("data-mapzoom"),e=1===Number(jQuery(this).attr("data-zoomcontrol")),r=1===Number(jQuery(this).attr("data-dragging")),n=1===Number(jQuery(this).attr("data-mousescroll")),s=1===Number(jQuery(this).attr("data-attribution")),p=jQuery(this).attr("data-mapstyle"),u=JSON.parse(jQuery(this).attr("data-location")),l=u[0].latitude,m=u[0].longitude,d=L.map(i,{center:[l,m],zoom:o,zoomControl:e,scrollWheelZoom:n,dragging:r,maxZoom:100,attributionControl:s});for(L.tileLayer.provider(p).addTo(d),jQuery('a[data-toggle="sppb-tab"]').on("click",function(t){var a=this;setTimeout(function(){jQuery(a).parent("li").hasClass("active")&&d.invalidateSize()},200)}),jQuery(".sppb-panel-heading").on("click",function(t){var a=this;setTimeout(function(){jQuery(a).hasClass("active")&&d.invalidateSize()},200)}),a=0;a<u.length;a++){for(var c=L.Icon.extend({options:{iconAnchor:[30,65],popupAnchor:[-5,-65]}}),h=u[a].custom_icon,b="",y=0;y<h.length;y++)b={icon:new c({iconUrl:h})};L.marker([u[a].latitude,u[a].longitude],b).addTo(d).bindPopup(u[a].address)}})}!function(t,a){"function"==typeof define&&define.amd?define(["leaflet"],a):"object"==typeof modules&&module.exports?module.exports=a(require("leaflet")):a(L)}(0,function(t){"use strict";return t.TileLayer.Provider=t.TileLayer.extend({initialize:function(a,i){var o=t.TileLayer.Provider.providers,e=a.split("."),r=e[0],n=e[1];if(!o[r])throw"No such provider ("+r+")";var s={url:o[r].url,options:o[r].options};if(n&&"variants"in o[r]){if(!(n in o[r].variants))throw"No such variant of "+r+" ("+n+")";var p,u=o[r].variants[n];p="string"==typeof u?{variant:u}:u.options,s={url:u.url||s.url,options:t.Util.extend({},s.options,p)}}var l=function(t){return-1===t.indexOf("{attribution.")?t:t.replace(/\{attribution.(\w*)\}/,function(t,a){return l(o[a].options.attribution)})};s.options.attribution=l(s.options.attribution);var m=t.Util.extend({},s.options,i);t.TileLayer.prototype.initialize.call(this,s.url,m)}}),t.TileLayer.Provider.providers={OpenStreetMap:{url:"https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",options:{maxZoom:19,attribution:'&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'},variants:{Mapnik:{},BlackAndWhite:{url:"https://{s}.tiles.wmflabs.org/bw-mapnik/{z}/{x}/{y}.png",options:{maxZoom:18}},HOT:{url:"https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png",options:{attribution:'{attribution.OpenStreetMap}, Tiles courtesy of <a href="https://hot.openstreetmap.org/" target="_blank">Humanitarian OpenStreetMap Team</a>'}}}},Hydda:{url:"https://{s}.tile.openstreetmap.se/hydda/{variant}/{z}/{x}/{y}.png",options:{maxZoom:18,variant:"full",attribution:'Tiles courtesy of <a href="https://openstreetmap.se/" target="_blank">OpenStreetMap Sweden</a> &mdash; Map data {attribution.OpenStreetMap}'},variants:{Full:"full"}},Stamen:{url:"https://stamen-tiles-{s}.a.ssl.fastly.net/{variant}/{z}/{x}/{y}{r}.{ext}",options:{attribution:'Map tiles by <a href="https://stamen.com">Stamen Design</a>, <a href="https://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> &mdash; Map data {attribution.OpenStreetMap}',subdomains:"abcd",minZoom:0,maxZoom:20,variant:"toner",ext:"png"},variants:{Toner:"toner",TonerHybrid:"toner-hybrid",TonerLite:"toner-lite",Terrain:{options:{variant:"terrain",minZoom:0,maxZoom:18}}}},Esri:{url:"https://server.arcgisonline.com/ArcGIS/rest/services/{variant}/MapServer/tile/{z}/{y}/{x}",options:{variant:"World_Street_Map",attribution:"Tiles &copy; Esri"},variants:{WorldStreetMap:{options:{attribution:"{attribution.Esri} &mdash; Source: Esri, DeLorme, NAVTEQ, USGS, Intermap, iPC, NRCAN, Esri Japan, METI, Esri China (Hong Kong), Esri (Thailand), TomTom, 2012"}},DeLorme:{options:{variant:"Specialty/DeLorme_World_Base_Map",minZoom:1,maxZoom:11,attribution:"{attribution.Esri} &mdash; Copyright: &copy;2012 DeLorme"}},WorldTopoMap:{options:{variant:"World_Topo_Map",attribution:"{attribution.Esri} &mdash; Esri, DeLorme, NAVTEQ, TomTom, Intermap, iPC, USGS, FAO, NPS, NRCAN, GeoBase, Kadaster NL, Ordnance Survey, Esri Japan, METI, Esri China (Hong Kong), and the GIS User Community"}},OceanBasemap:{options:{variant:"Ocean_Basemap",maxZoom:13,attribution:"{attribution.Esri} &mdash; Sources: GEBCO, NOAA, CHS, OSU, UNH, CSUMB, National Geographic, DeLorme, NAVTEQ, and Esri"}},NatGeoWorldMap:{options:{variant:"NatGeo_World_Map",maxZoom:16,attribution:"{attribution.Esri} &mdash; National Geographic, Esri, DeLorme, NAVTEQ, UNEP-WCMC, USGS, NASA, ESA, METI, NRCAN, GEBCO, NOAA, iPC"}},WorldGrayCanvas:{options:{variant:"Canvas/World_Light_Gray_Base",maxZoom:16,attribution:"{attribution.Esri} &mdash; Esri, DeLorme, NAVTEQ"}}}},CartoDB:{url:"https://cartodb-basemaps-{s}.global.ssl.fastly.net/{variant}/{z}/{x}/{y}{r}.png",options:{attribution:'{attribution.OpenStreetMap} &copy; <a href="https://cartodb.com/attributions">CartoDB</a>',subdomains:"abcd",maxZoom:19,variant:"light_all"},variants:{Positron:"light_all",DarkMatter:"dark_all",Voyager:"rastertiles/voyager"}},HikeBike:{url:"https://{s}.tiles.wmflabs.org/{variant}/{z}/{x}/{y}.png",options:{maxZoom:19,attribution:"{attribution.OpenStreetMap}",variant:"hikebike"},variants:{HikeBike:{}}},NASAGIBS:{url:"https://map1.vis.earthdata.nasa.gov/wmts-webmerc/{variant}/default/{time}/{tilematrixset}{maxZoom}/{z}/{y}/{x}.{format}",options:{attribution:'Imagery provided by services from the Global Imagery Browse Services (GIBS), operated by the NASA/GSFC/Earth Science Data and Information System (<a href="https://earthdata.nasa.gov">ESDIS</a>) with funding provided by NASA/HQ.',bounds:[[-85.0511287776,-179.999999975],[85.0511287776,179.999999975]],minZoom:1,maxZoom:9,format:"jpg",time:"",tilematrixset:"GoogleMapsCompatible_Level"},variants:{ViirsEarthAtNight2012:{options:{variant:"VIIRS_CityLights_2012",maxZoom:8}}}},Wikimedia:{url:"https://maps.wikimedia.org/osm-intl/{z}/{x}/{y}{r}.png",options:{attribution:'<a href="https://wikimediafoundation.org/wiki/Maps_Terms_of_Use">Wikimedia</a>',minZoom:1,maxZoom:19}}},t.tileLayer.provider=function(a,i){return new t.TileLayer.Provider(a,i)},t}),jQuery(window).on("load",function(){initOpenStreetMap(document);new MutationObserver(function(t){t.forEach(function(t){var a=t.addedNodes;null!==a&&jQuery(a).each(function(){jQuery(this).find(".sppb-addon-openstreetmap").each(function(){var t,a=jQuery(this).attr("id"),i=jQuery(this).attr("data-mapzoom"),o=1===Number(jQuery(this).attr("data-zoomcontrol")),e=1===Number(jQuery(this).attr("data-dragging")),r=1===Number(jQuery(this).attr("data-mousescroll")),n=1===Number(jQuery(this).attr("data-attribution")),s=jQuery(this).attr("data-mapstyle"),p=JSON.parse(jQuery(this).attr("data-location")),u=p[0].latitude,l=p[0].longitude,m=L.map(a,{center:[u,l],zoom:i,zoomControl:o,scrollWheelZoom:r,dragging:e,maxZoom:100,attributionControl:n});for(L.tileLayer.provider(s).addTo(m),jQuery('a[data-toggle="sppb-tab"]').on("click",function(t){var a=this;setTimeout(function(){jQuery(a).parent("li").hasClass("active")&&m.invalidateSize()},200)}),jQuery(".sppb-panel-heading").on("click",function(t){var a=this;setTimeout(function(){jQuery(a).hasClass("active")&&m.invalidateSize()},200)}),t=0;t<p.length;t++){for(var d=L.Icon.extend({options:{iconAnchor:[30,65],popupAnchor:[-5,-65]}}),c=p[t].custom_icon,h="",b=0;b<c.length;b++)h={icon:new d({iconUrl:c})};L.marker([p[t].latitude,p[t].longitude],h).addTo(m).bindPopup(p[t].address)}})})})}).observe(document.body,{childList:!0,subtree:!0})});