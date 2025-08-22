
    // async function getCoordinates(pinCode, baseCountry) {
    //     const url = `https://nominatim.openstreetmap.org/search?postalcode=${pinCode}&country=${baseCountry}&format=json&limit=1`;
    //     try {
    //         const response = await fetch(url);
    //         const data = await response.json();
    //         if (data.length > 0) {
    //             return [parseFloat(data[0].lat), parseFloat(data[0].lon)];
    //         } else {
    //             console.error(`No coordinates found for PIN code ${pinCode}`);
    //             return null;
    //         }
    //     } catch (error) {
    //         console.error(`Error fetching coordinates: ${error.message}`);
    //         return null;
    //     }
    // }

    // async function plotMap(dropoffPin, pickupPin, deliveryMessage,pickupMessage, base_country, deliveryCountry) {
    //     const dropoffLocation = await getCoordinates(dropoffPin, deliveryCountry);
    //     const pickupLocation = await getCoordinates(pickupPin, base_country);

    //     console.log("Dropoff Coordinates:", dropoffLocation);
    //     console.log("Pickup Coordinates:", pickupLocation);

    //     if (!dropoffLocation || !pickupLocation) {
    //         console.error("One or both locations could not be found.");
    //         return;
    //     }

    //     // Initialize the map
    //     const map = L.map('bt_sst_leaflet_map_location', {
    //         center: dropoffLocation,
    //         zoom: 10,
    //         scrollWheelZoom: false,
    //         touchZoom: true,
    //         doubleClickZoom: true
    //     });

    //     L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    //         maxZoom: 14,
    //     }).addTo(map);

    //     // Custom marker icons
    //     const dropoffIcon = L.divIcon({
    //         className: 'custom-icon',
    //         html: '<img src="https://cdn-icons-png.flaticon.com/512/684/684908.png" style="width:35px;" />',
    //         iconSize: [32, 32],
    //     });

    //     const pickupIcon = L.divIcon({
    //         className: 'custom-icon',
    //         html: '<img src="https://cdn-icons-png.flaticon.com/512/854/854878.png" style="width:35px;" />',
    //         iconSize: [32, 32],
    //     });

    //     // Add Dropoff Marker
    //     L.marker(dropoffLocation, { icon: dropoffIcon })
    //         .addTo(map)
    //         .bindTooltip(` ${deliveryMessage}`, { permanent: true, direction: "top" });

    //     // Add Pickup Marker
    //     L.marker(pickupLocation, { icon: pickupIcon })
    //         .addTo(map)
    //         .bindTooltip(pickupMessage, { permanent: true, direction: "top" });

    //     // Draw route line between points
    //     L.polyline([pickupLocation, dropoffLocation], { color: 'red', weight: 3 }).addTo(map);

    //     // Adjust map view to fit both markers
    //     map.fitBounds([pickupLocation, dropoffLocation], { padding: [50, 50] });

    //     // Disable dragging
    //     map.dragging.disable();
    // }

    // window.plotMap = plotMap;
    


    
async function getCoordinates(pinCode, baseCountry) {
    const url = `https://nominatim.openstreetmap.org/search?postalcode=${pinCode}&country=${baseCountry}&format=json&limit=1`;
    try {
        console.log(url);
        const response = await fetch(url);
        const data = await response.json();
        if (data.length > 0) {
            return [parseFloat(data[0].lat), parseFloat(data[0].lon)];
        } else {
            console.error(`No coordinates found for PIN code ${pinCode}`);
            return null;
        }
    } catch (error) {
        console.error(`Error fetching coordinates: ${error.message}`);
        return null;
    }
}

// async function plotMap(dropoffPin, pickupPin, deliveryMessage, pickupMessage, base_country, deliveryCountry, currentPin, currentMessage, currentCountry) {
//     const dropoffLocation = await getCoordinates(dropoffPin, deliveryCountry);
//     const pickupLocation = await getCoordinates(pickupPin, base_country);
//     const currentLocation = await getCoordinates(currentPin, currentCountry);

//     console.log("Dropoff Coordinates:", dropoffLocation);
//     console.log("Pickup Coordinates:", pickupLocation);
//     console.log("Current Coordinates:", currentLocation);

//     if (!dropoffLocation || !pickupLocation || !currentLocation) {
//         console.error("One or both locations could not be found.");
//         return;
//     }

//     // Initialize the map
//     const map = L.map('bt_sst_leaflet_map_location', {
//         center: dropoffLocation,
//         zoom: 10,
//         scrollWheelZoom: false,
//         touchZoom: true,
//         doubleClickZoom: true
//     });

//     L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//         maxZoom: 14,
//     }).addTo(map);

//     // Custom marker icons
//     const dropoffIcon = L.divIcon({
//         className: 'custom-icon',
//         html: '<img src="https://cdn-icons-png.flaticon.com/512/684/684908.png" style="width:35px;" />',
//         iconSize: [32, 32],
//     });

//     const pickupIcon = L.divIcon({
//         className: 'custom-icon',
//         html: '<img src="https://cdn-icons-png.flaticon.com/512/854/854878.png" style="width:35px;" />',
//         iconSize: [32, 32],
//     });

//     const currentIcon = L.divIcon({
//         className: 'custom-icon',
//         html: '<img src="https://cdn-icons-png.flaticon.com/512/684/684913.png" style="width:35px;" />',
//         iconSize: [32, 32],
//     });

//     // Add Dropoff Marker
//     L.marker(dropoffLocation, { icon: dropoffIcon })
//         .addTo(map)
//         .bindTooltip(` ${deliveryMessage}`, { permanent: true, direction: "top" });

//     // Add Pickup Marker
//     L.marker(pickupLocation, { icon: pickupIcon })
//         .addTo(map)
//         .bindTooltip(pickupMessage, { permanent: true, direction: "top" });

//     // Add Current Location Marker
//     L.marker(currentLocation, { icon: currentIcon })
//         .addTo(map)
//         .bindTooltip(currentMessage, { permanent: true, direction: "top" });


//     // Draw route lines passing through the current location
//     L.polyline([pickupLocation, currentLocation, dropoffLocation], { color: 'red', weight: 3 }).addTo(map);

//     // Adjust map view to fit all markers
//     map.fitBounds([pickupLocation, dropoffLocation, currentLocation], { padding: [50, 50] });

//     // // Draw route line between points
//     // L.polyline([pickupLocation, dropoffLocation], { color: 'red', weight: 3 }).addTo(map);

//     // // Adjust map view to fit both markers
//     // map.fitBounds([pickupLocation, dropoffLocation], { padding: [50, 50] });

//     // Disable dragging
//     map.dragging.disable();
// }

// window.plotMap = plotMap;



async function plotMap(dropoffPin, pickupPin, deliveryMessage, pickupMessage, base_country, deliveryCountry, currentPin = '', currentMessage = '', currentCountry = '') {
    const dropoffLocation = await getCoordinates(dropoffPin, deliveryCountry);
    const pickupLocation = await getCoordinates(pickupPin, base_country);
    let currentLocation = null;

    if (currentPin) {
        currentLocation = await getCoordinates(currentPin, currentCountry);
    }

    console.log("Dropoff Coordinates:", dropoffLocation);
    console.log("Pickup Coordinates:", pickupLocation);
    console.log("Current Coordinates:", currentLocation);

    if (!dropoffLocation || !pickupLocation || (currentPin && !currentLocation)) {
        console.error("One or more locations could not be found.");
        return;
    }

    // Initialize the map
    const map = L.map('bt_sst_leaflet_map_location', {
        center: dropoffLocation,
        zoom: 10,
        scrollWheelZoom: false,
        touchZoom: true,
        doubleClickZoom: true
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 14,
    }).addTo(map);

    // Custom marker icons
    const dropoffIcon = L.divIcon({
        className: 'custom-icon',
        html: '<img src="https://cdn-icons-png.flaticon.com/512/684/684908.png" style="width:35px;" />',
        iconSize: [32, 32],
    });

    const pickupIcon = L.divIcon({
        className: 'custom-icon',
        html: '<img src="https://cdn-icons-png.flaticon.com/512/854/854878.png" style="width:35px;" />',
        iconSize: [32, 32],
    });

    const currentIcon = L.divIcon({
        className: 'custom-icon',
        html: '<img src="https://cdn-icons-png.flaticon.com/512/684/684913.png" style="width:35px;" />',
        iconSize: [32, 32],
    });

    // Add Dropoff Marker
    L.marker(dropoffLocation, { icon: dropoffIcon })
        .addTo(map)
        .bindTooltip(` ${deliveryMessage}`, { permanent: true, direction: "top" });

    // Add Pickup Marker
    L.marker(pickupLocation, { icon: pickupIcon })
        .addTo(map)
        .bindTooltip(pickupMessage, { permanent: true, direction: "top" });

    if (currentLocation) {
        // Add Current Location Marker
        L.marker(currentLocation, { icon: currentIcon })
            .addTo(map)
            .bindTooltip(currentMessage, { permanent: true, direction: "top" });

        // Draw route lines passing through the current location
        L.polyline([pickupLocation, currentLocation, dropoffLocation], { color: 'red', weight: 3 }).addTo(map);
    } else {
        // Draw direct route line between pickup and dropoff
        L.polyline([pickupLocation, dropoffLocation], { color: 'red', weight: 3 }).addTo(map);
    }

    // Adjust map view to fit all markers
    const bounds = currentLocation ? [pickupLocation, dropoffLocation, currentLocation] : [pickupLocation, dropoffLocation];
    map.fitBounds(bounds, { padding: [50, 50] });

    // Disable dragging
    map.dragging.disable();
}

window.plotMap = plotMap;
