Number.prototype.toKmH = function () {
  return Math.round(this * 3600 / 10) / 100;
};

var hisinfowindow = null;
var hismap = null;
var hiszoomlevel = 15;
var hismarkersArray = [];

function clearOverlays() {
  for (var i = 0; i < hismarkersArray.length; i++) {
    hismarkersArray[i].setMap(null);
  }
  hismarkersArray = [];
}

function fetchAddress(lat, lon) {
  return fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`)
    .then(response => response.json())
    .then(data => data.display_name || "Address not found")
    .catch(() => "Address fetch error");
}

$("#track").on("submit", function (e) {
  e.preventDefault();

  const vehicle = $("#t_vechicle").val();
  if (!vehicle) return alert("Please select a vehicle");

  const fromDateTime = $("#fromdate").val();
  const toDateTime = $("#todate").val();
  if (!fromDateTime || !toDateTime) return alert("Please select both From and To date/time");

  const [from_date, from_time] = fromDateTime.split("T");
  const [to_date, to_time] = toDateTime.split("T");

  var hismyLatlng = new google.maps.LatLng(52.696361078274485, -111.4453125);
  hismap = new google.maps.Map(document.getElementById("map_canvas"), {
    zoom: hiszoomlevel,
    center: hismyLatlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    scrollwheel: true,
    gestureHandling: "cooperative",
  });

  hisinfowindow = new google.maps.InfoWindow();
  hiszoomlevel = hismap.getZoom();
  clearOverlays();

  var path = $("#base").val();
  const iconBase = path + "/admin/assets/images/vehicle_marker_icons/";

  const postData = {
    device_name: vehicle,
    from_date: from_date,
    from_time: from_time,
    to_date: to_date,
    to_time: to_time,
    _token: $("input[name=_token]").val()
  };

  $.ajax({
    type: "POST",
    url: path + "/api/vts-history-vehicle",
    data: postData,
    dataType: "json",
    success: function (result) {
      var locations = result.data?.data;
      if (!Array.isArray(locations) || locations.length === 0)
        return alert("No data to show on the map.");

      var flightPlanCoordinates = [];
      var bounds = new google.maps.LatLngBounds();
      var lastPosition = null;

      locations.forEach(function (vehicle) {
        var vehicleName = vehicle.name || "Unknown Vehicle";
        var topSpeed = vehicle.top_speed || 0;

        if (Array.isArray(vehicle.history)) {
          vehicle.history.forEach(function (historyItem) {
            if (Array.isArray(historyItem.items)) {
              historyItem.items.forEach(function (item, index) {
                if (item.latitude && item.longitude) {
                  var position = new google.maps.LatLng(item.latitude, item.longitude);

                  let iconUrl = iconBase + "move.svg";
                  if (index === 0 && lastPosition === null) iconUrl = iconBase + "start.svg";
                  else if (index === historyItem.items.length - 1) iconUrl = iconBase + "end.svg";
                  else if (item.speed === 0) iconUrl = iconBase + "stop.svg";

                  var marker = new google.maps.Marker({
                    position: position,
                    map: hismap,
                    icon: {
                      url: iconUrl,
                      scaledSize: new google.maps.Size(32, 32),
                    },
                  });

                  google.maps.event.addListener(marker, "click", function () {
                    const datetime = historyItem.raw_time || "";
                    const speed = item.speed > 0 ? item.speed.toKmH() : topSpeed;
                    const engineStatus = item.engine_status === 1;
                    const gsmStatus = item.gsm_status || "N/A";
                    const stopDuration = item.stop_duration || "N/A";
                    const distance = item.distance || "N/A";

                    hisinfowindow.setContent(`<div>Loading info...</div>`);
                    hisinfowindow.open(hismap, marker);

                    fetchAddress(item.latitude, item.longitude).then(address => {
                      const html = `
                      <div style="
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                        font-size: 14px;
                        line-height: 1.5;
                        color: #333;
                        width: 280px;
                        max-height: 260px;
                        overflow-y: auto;
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
                          <span style="color: #007BFF; font-weight: bold;">${vehicleName}</span>
                        </div>

                        <!-- Box Body -->
                        <div style="padding: 12px 14px;">
                          <div style="margin-bottom: 10px;">
                            <label>📍 Address:</label><br>
                            <span style="color: #555;">${address}</span>
                          </div>
                          <div style="margin-bottom: 8px;">
                            <label>💨 Speed:</label>
                            <span style="color: #444;">${Math.round(speed)} Km/h</span>
                          </div>
                          <div style="margin-bottom: 8px;">
                            <label>🕒 Time:</label>
                            <span style="color: #444;">${datetime}</span>
                          </div>
                          <div style="margin-bottom: 8px;">
                            <label>⏱️ Stop Duration:</label>
                            <span style="color: #444;">${stopDuration}</span>
                          </div>
                          <div>
                            <label>🛣️ Distance Travelled:</label>
                            <span style="color: #444;">${distance} km</span>
                          </div>
                        </div>
                      </div>`;

                      hisinfowindow.setContent(html);
                    });
                  });

                  bounds.extend(position);
                  hismarkersArray.push(marker);
                  flightPlanCoordinates.push(position);
                  lastPosition = position;
                }
              });
            }
          });
        }
      });

      if (flightPlanCoordinates.length > 1) {
        hismap.fitBounds(bounds);
        new google.maps.Polyline({
          map: hismap,
          path: flightPlanCoordinates,
          strokeColor: "#0052cc",
          strokeOpacity: 1,
          strokeWeight: 3,
        });
      } else {
        alert("Not enough valid data to draw a path on the map.");
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Unexpected error occurred:", textStatus, errorThrown);
      alert("Failed to fetch vehicle history data.");
    },
  });
});
