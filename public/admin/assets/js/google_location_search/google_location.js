let originInput = document.getElementById('pickup_location');
let destinationInput = document.getElementById('drop_location');
let totalDistanceInput = document.getElementById('total_kilometers');

let originPlace = null;
let destinationPlace = null;

function calculateDistanceMatrix() {
  const service = new google.maps.DistanceMatrixService();
  service.getDistanceMatrix(
    {
      origins: [originPlace.geometry.location],
      destinations: [destinationPlace.geometry.location],
      travelMode: google.maps.TravelMode.DRIVING,
      unitSystem: google.maps.UnitSystem.METRIC,
    },
    (response, status) => {
      if (status !== 'OK') {
        alert('Error with distance matrix service: ' + status);
        return;
      }

      const result = response.rows[0].elements[0];
      if (result.status === 'OK') {
        const distanceInMeters = result.distance.value;
        const distanceInKm = (distanceInMeters / 1000).toFixed(2);
        totalDistanceInput.value = distanceInKm;
      } else {
        alert('Could not calculate distance: ' + result.status);
      }
    }
  );
}

function setupMainAutocomplete() {
  const originAutocomplete = new google.maps.places.Autocomplete(originInput);
  const destinationAutocomplete = new google.maps.places.Autocomplete(destinationInput);

  originAutocomplete.addListener('place_changed', () => {
    originPlace = originAutocomplete.getPlace();
    if (!originPlace.geometry) {
      alert("Please select a valid pickup location.");
      return;
    }
    if (originPlace && destinationPlace) {
      calculateDistanceMatrix();
    }
  });

  destinationAutocomplete.addListener('place_changed', () => {
    destinationPlace = destinationAutocomplete.getPlace();
    if (!destinationPlace.geometry) {
      alert("Please select a valid drop location.");
      return;
    }
    if (originPlace && destinationPlace) {
      calculateDistanceMatrix();
    }
  });
}

function setupDynamicAutocomplete() {
  const appliedInputs = new WeakSet();

  const setupWatcher = (input) => {
    if (!input || appliedInputs.has(input)) return;

    let initialized = false;

    const onInput = () => {
      if (input.value.length >= 3 && !initialized) {
        initialized = true;
        applyAutocomplete(input);
      }
    };

    input.addEventListener('input', onInput);
    appliedInputs.add(input);
  };

  const applyAutocomplete = (input) => {
    if (!input || input.dataset.autocompleteApplied) return;

    const autocomplete = new google.maps.places.Autocomplete(input, {
      types: ['geocode','establishment'],
      componentRestrictions: { country: "bd" },
    });

    autocomplete.addListener("place_changed", function () {
      const place = autocomplete.getPlace();
      const lat = place.geometry?.location?.lat();
      const lng = place.geometry?.location?.lng();

      if (lat && lng) {
        const parent = input.closest('.dropoff-row') || input.closest('.col-md-6') || input.closest('form') || document;

        let latInput, lngInput;
        if (input.name === "pickup_location") {
          latInput = parent.querySelector('input[name="pickup_lat"]');
          lngInput = parent.querySelector('input[name="pickup_long"]');
        } else if (input.name === "drop_off_location[]") {
          latInput = parent.querySelector('input[name="drop_off_lat[]"]');
          lngInput = parent.querySelector('input[name="drop_off_long[]"]');
        }

        if (latInput) latInput.value = lat;
        if (lngInput) lngInput.value = lng;

        // Trigger recalculation after coordinates are set
        calculateTotalKilometers();
      }
    });

    input.dataset.autocompleteApplied = "true";
  };

  document.querySelectorAll('input[name="pickup_location"], input[name="drop_off_location[]"]').forEach(setupWatcher);

  const observer = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
      mutation.addedNodes.forEach((node) => {
        if (!(node instanceof HTMLElement)) return;

        if (node.matches?.('input[name="drop_off_location[]"], input[name="pickup_location"]')) {
          setupWatcher(node);
        }

        const inputs = node.querySelectorAll?.('input[name="drop_off_location[]"], input[name="pickup_location"]');
        inputs?.forEach(setupWatcher);
      });
    });
  });

  observer.observe(document.body, {
    childList: true,
    subtree: true
  });
}

// ✅ Add this missing function to calculate total kilometers with multiple drop-off points
function calculateTotalKilometers() {
  const pickupLat = parseFloat(document.querySelector('input[name="pickup_lat"]')?.value);
  const pickupLng = parseFloat(document.querySelector('input[name="pickup_long"]')?.value);

  const dropLatInputs = document.querySelectorAll('input[name="drop_off_lat[]"]');
  const dropLngInputs = document.querySelectorAll('input[name="drop_off_long[]"]');

  const points = [];

  if (!isNaN(pickupLat) && !isNaN(pickupLng)) {
    points.push({ lat: pickupLat, lng: pickupLng });
  }

  for (let i = 0; i < dropLatInputs.length; i++) {
    const lat = parseFloat(dropLatInputs[i].value);
    const lng = parseFloat(dropLngInputs[i].value);

    if (!isNaN(lat) && !isNaN(lng)) {
      points.push({ lat, lng });
    }
  }

  if (points.length < 2) return;

  let totalDistance = 0;
  const service = new google.maps.DistanceMatrixService();

  const calculateNextLeg = (i) => {
    if (i >= points.length - 1) {
      totalDistanceInput.value = (totalDistance / 1000).toFixed(2); // km
      return;
    }

    service.getDistanceMatrix(
      {
        origins: [points[i]],
        destinations: [points[i + 1]],
        travelMode: google.maps.TravelMode.DRIVING,
        unitSystem: google.maps.UnitSystem.METRIC,
      },
      (response, status) => {
        if (status === 'OK' && response.rows[0].elements[0].status === 'OK') {
          totalDistance += response.rows[0].elements[0].distance.value;
        }
        calculateNextLeg(i + 1);
      }
    );
  };

  calculateNextLeg(0);
}

// 👇 Main init
window.initAutocomplete = function () {
  setupMainAutocomplete();
  setupDynamicAutocomplete();
};
