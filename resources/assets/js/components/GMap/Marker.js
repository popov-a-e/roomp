function CustomMarker(map, latlng, args) {
  this.latlng = latlng;
  this.args = args;
  this.setMap(map);

  window.a = this;
}

CustomMarker.prototype = new google.maps.OverlayView();

CustomMarker.prototype.draw = () => {};


CustomMarker.prototype.onAdd = CustomMarker.prototype.draw = function () {
  let div = this.div;

  if (!div) {
    div = this.div = this.args.div;

    if (typeof(this.args.marker_id) !== 'undefined') {
      div.dataset.marker_id = this.args.marker_id;
    }

    let panes = this.getPanes();

    panes.floatPane.appendChild(div);
  }


  let point = this.getProjection().fromLatLngToDivPixel(this.latlng);

  if (point) {
    div.style.left = point.x + 'px';
    div.style.top = point.y + 'px';
  }
};

CustomMarker.prototype.remove = function () {
  if (this.div) {
    this.div.parentNode.removeChild(this.div);
    this.div = null;
  }
};

CustomMarker.prototype.getPosition = function () {
  return this.latlng;
};

CustomMarker.prototype.reposition = function (latlng) {
  "use strict";

  this.latlng = latlng;

  let point = this.getProjection().fromLatLngToDivPixel(this.latlng);

  if (point) {
    this.div.style.left = point.x + 'px';
    this.div.style.top = point.y + 'px';
  }
};

export default CustomMarker;