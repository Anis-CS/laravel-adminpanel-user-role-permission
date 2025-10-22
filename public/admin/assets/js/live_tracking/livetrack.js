var map = infoWindow = null;
var liveMarkersMap = {}; // deviceName -> { marker, label, lastCourse, stopCircle }
var liveTrailsMap = {};  // deviceName -> google.maps.Polyline for trail
var rotatedIconsCache = {}; // angle -> icon
var hasCenteredMap = false;

$(document).ready(function () {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function (position) {
      document.cookie = "latitude=" + position.coords.latitude;
      document.cookie = "longitude=" + position.coords.longitude;
    });
  }

  map = new google.maps.Map(document.getElementById("map_canvas"), {
    center: new google.maps.LatLng(23.7818612, 90.4031873),
    zoom: 6,
    mapTypeId: 'roadmap',
    gestureHandling: 'greedy',
    maxZoom: 21
  });

  infoWindow = new google.maps.InfoWindow();

  livetracking();
  window.setInterval(livetracking, 15000);
});

function livetracking() {
  var bounds = new google.maps.LatLngBounds();
  const activeDevices = [];

  vehicleNames.forEach(function (deviceName) {
    $.ajax({
      type: "POST",
      url: $('#base').val() + "/api/vts-live-tracking",
      data: { device_name: deviceName },
      success: function (response) {
        if (response.status === 1 && response.data?.data?.[0]) {
          const item = response.data.data[0];
          const engineStatus = item.sensors?.find(sensor => sensor.type === "ignition")?.value === "On";
          const gsmStatus = item.sensors?.find(sensor => sensor.type === "gsm")?.value || "N/A";
          const stopDuration = item.stop_duration || "N/A";
          const course = item.course || 0;
          const latLng = new google.maps.LatLng(item.latitude, item.longitude);
          const speed = item.speed || 0;

          updateOrAddMarker(
            item.latitude,
            item.longitude,
            deviceName,
            speed,
            item.time,
            "#FF0000",
            "Generic",
            engineStatus,
            gsmStatus,
            stopDuration,
            0,
            course
          );

          bounds.extend(latLng);
          activeDevices.push(deviceName);

          if (!hasCenteredMap) {
            map.fitBounds(bounds);
            hasCenteredMap = true;
          }
        }
      },
      error: function (xhr) {
        console.warn(`Failed for ${deviceName}`, xhr.responseText);
      }
    });
  });

  // NOTE: Do NOT remove markers here to avoid flickering and reloading
}

function updateOrAddMarker(latitude, longitude, name, speed, time, color, type, engineStatus, gsmStatus, stopDuration, distance, course) {
  const newPos = new google.maps.LatLng(parseFloat(latitude), parseFloat(longitude));

  // Function to fetch address from lat/lon using Nominatim API
  function fetchAddress(lat, lon) {
    return fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`)
      .then(response => response.json())
      .then(data => {
        // Sometimes address.display_name might be undefined, fallback to empty string
        return data.display_name || "Address not found";
      })
      .catch(() => "Address fetch error");
  }

  // Common logic to create/update marker info window content once address is fetched
    function setMarkerInfo(marker, name, speed, time, stopDuration, engineStatus, gsmStatus, distance, address) {
  const formattedAddress = address || "Address not available";

  const html = `
    <div style="
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      font-size: 14px;
      line-height: 1.5;
      color: #333;
      width: 280px;
      max-height: 260px;
      overflow-y: auto;
      padding: 0;
      box-sizing: border-box;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      border-radius: 8px;
      background: #fff;
      border: 1px solid #ccc;
    ">

      <!-- Box Header -->
      <div style="
        background: #f1f1f1;
        padding: 10px 14px;
        border-bottom: 1px solid #ddd;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
      ">
        <label style="color: #333; font-size: 15px; font-weight: bold;">
          🚗 Number :
        </label>
        <span style="color: #007BFF; font-weight: bold;">${name}</span>
      </div>

      <!-- Box Body -->
      <div style="padding: 12px 14px;">

        <div style="margin-bottom: 10px;">
          <label>📍 Address:</label><br>
          <span style="color: #555;">${formattedAddress}</span>
        </div>

        <div style="margin-bottom: 8px;">
          <label>💨 Speed:</label>
          <span style="color: #444;">${Math.round(speed)} Km/h</span>
        </div>

        <div style="margin-bottom: 8px;">
          <label>🕒 Time:</label>
          <span style="color: #444;">${time}</span>
        </div>

        <div style="margin-bottom: 8px;">
          <label>⏱️ Stop Duration:</label>
          <span style="color: #444;">${stopDuration}</span>
        </div>

        <div style="margin-bottom: 8px;">
          <label>🛠️ Engine:</label>
          <span style="color: #444;">${engineStatus ? "On" : "Off"}</span>
        </div>

        <div style="margin-bottom: 8px;">
          <label>📶 GSM:</label>
          <span style="color: #444;">${gsmStatus}</span>
        </div>

        <div>
          <label>🛣️ Distance Travelled:</label>
          <span style="color: #444;">${distance} km</span>
        </div>

      </div>
    </div>
  `;

  bindInfoWindow(marker, map, infoWindow, html);
}




  if (liveMarkersMap[name]) {
    // Existing marker, animate and update
    const markerData = liveMarkersMap[name];
    animateMarker(markerData.marker, newPos);

    if (Math.abs(markerData.lastCourse - course) > 10) {
      rotateIcon("/admin/assets/images/car_icon/mordern_car.png", course).then((carIcon) => {
        markerData.marker.setIcon(carIcon);
      });
      markerData.lastCourse = course;
    }

    if (markerData.label && markerData.label.div) {
      markerData.label.div.innerHTML = `${name} | ${Math.round(speed)} Km/h`;
      markerData.label.draw();
    }

    updateStopCircle(name, speed, newPos);
    updateTrail(name, newPos);

    // Fetch address and update info window content
    fetchAddress(latitude, longitude).then(address => {
      setMarkerInfo(markerData.marker, name, speed, time, stopDuration, engineStatus, gsmStatus, distance, address);
    });

  } else {
    // New marker, rotate icon and create marker
    rotateIcon("/admin/assets/images/car_icon/mordern_car.png", course).then((carIcon) => {
      const marker = new google.maps.Marker({
        map: map,
        position: newPos,
        icon: carIcon,
        name: name,
      });

      const label = createCustomLabel(marker, name, speed);

      // Fetch address then bind info window with address included
      fetchAddress(latitude, longitude).then(address => {
        setMarkerInfo(marker, name, speed, time, stopDuration, engineStatus, gsmStatus, distance, address);
      });

      liveMarkersMap[name] = { marker, label, lastCourse: course, stopCircle: null };

      updateStopCircle(name, speed, newPos);
      updateTrail(name, newPos);
    });
  }
}


function animateMarker(marker, newPosition) {
  const oldPosition = marker.getPosition();
  const frames = 30;
  const duration = 500;
  let frame = 0;

  if (!oldPosition) {
    marker.setPosition(newPosition);
    return;
  }

  const deltaLat = (newPosition.lat() - oldPosition.lat()) / frames;
  const deltaLng = (newPosition.lng() - oldPosition.lng()) / frames;

  const interval = setInterval(() => {
    frame++;
    const lat = oldPosition.lat() + deltaLat * frame;
    const lng = oldPosition.lng() + deltaLng * frame;
    marker.setPosition(new google.maps.LatLng(lat, lng));
    if (frame >= frames) clearInterval(interval);
  }, duration / frames);
}

function rotateIcon(imageUrl, angle) {
  const roundedAngle = Math.round(angle / 5) * 5;

  if (rotatedIconsCache[roundedAngle]) {
    return Promise.resolve(rotatedIconsCache[roundedAngle]);
  }

  return new Promise((resolve) => {
    const canvas = document.createElement("canvas");
    const ctx = canvas.getContext("2d");
    const img = new Image();
    img.src = imageUrl;

    img.onload = function () {
      const size = 64;
      canvas.width = size;
      canvas.height = size;

      ctx.clearRect(0, 0, size, size);
      ctx.translate(size / 2, size / 2);
      ctx.rotate((roundedAngle * Math.PI) / 180);
      ctx.drawImage(img, -size / 2, -size / 2, size, size);

      const icon = {
        url: canvas.toDataURL(),
        scaledSize: new google.maps.Size(size, size),
        anchor: new google.maps.Point(size / 2, size / 2),
      };

      rotatedIconsCache[roundedAngle] = icon;
      resolve(icon);
    };
  });
}

function createCustomLabel(marker, name, speed) {
  const label = new google.maps.OverlayView();

  label.onAdd = function () {
    const div = document.createElement('div');
    div.style.position = 'absolute';
    div.style.fontSize = '14px';
    div.style.fontWeight = 'bold';
    div.style.color = '#000';
    div.style.backgroundColor = 'white';
    div.style.padding = '4px 6px';
    div.style.borderRadius = '4px';
    div.style.border = '1px solid #ccc';
    div.innerHTML = `${name} | ${Math.round(speed)} Km/h`;

    this.div = div;
    this.getPanes().overlayLayer.appendChild(div);
  };

  label.draw = function () {
    const position = marker.getPosition();
    const projection = this.getProjection();
    const point = projection.fromLatLngToDivPixel(position);

    if (this.div) {
      this.div.style.left = point.x - (this.div.offsetWidth / 2) + 'px';
      this.div.style.top = point.y - this.div.offsetHeight - 40 + 'px';
    }
  };

  label.onRemove = function () {
    if (this.div) {
      this.div.parentNode.removeChild(this.div);
      this.div = null;
    }
  };

  label.setMap(map);
  return label;
}

function bindInfoWindow(marker, map, infoWindow, html) {
  google.maps.event.addListener(marker, "click", function () {
    infoWindow.setContent(html);
    infoWindow.open(map, marker);
    map.setCenter(marker.getPosition());
  });
}

function updateStopCircle(name, speed, position) {
  if (speed < 1) {
    if (!liveMarkersMap[name].stopCircle) {
      liveMarkersMap[name].stopCircle = new google.maps.Circle({
        strokeColor: '#FF0000',
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: '#FF0000',
        fillOpacity: 0.2,
        map: map,
        center: position,
        radius: 20
      });
    } else {
      liveMarkersMap[name].stopCircle.setCenter(position);
    }
  } else {
    if (liveMarkersMap[name].stopCircle) {
      liveMarkersMap[name].stopCircle.setMap(null);
      liveMarkersMap[name].stopCircle = null;
    }
  }
}

function updateTrail(name, newPos) {
  if (!liveTrailsMap[name]) {
    liveTrailsMap[name] = new google.maps.Polyline({
      map: map,
      path: [newPos],
      strokeColor: '#0080ff',
      strokeOpacity: 0.7,
      strokeWeight: 3
    });
  } else {
    const path = liveTrailsMap[name].getPath();
    path.push(newPos);
  }
}
