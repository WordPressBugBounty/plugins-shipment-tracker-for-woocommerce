
    async function getCoordinates(pinCode, baseCountry) {
        const url = `https://nominatim.openstreetmap.org/search?postalcode=${pinCode}&country=${baseCountry}&format=json&limit=1`;
        try {
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

    async function plotMap(dropoffPin, pickupPin, deliveryMessage,pickupMessage, base_country, deliveryCountry) {
        const dropoffLocation = await getCoordinates(dropoffPin, deliveryCountry);
        const pickupLocation = await getCoordinates(pickupPin, base_country);

        console.log("Dropoff Coordinates:", dropoffLocation);
        console.log("Pickup Coordinates:", pickupLocation);

        if (!dropoffLocation || !pickupLocation) {
            console.error("One or both locations could not be found.");
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

        // Add Dropoff Marker
        L.marker(dropoffLocation, { icon: dropoffIcon })
            .addTo(map)
            .bindTooltip(` ${deliveryMessage}`, { permanent: true, direction: "top" });

        // Add Pickup Marker
        L.marker(pickupLocation, { icon: pickupIcon })
            .addTo(map)
            .bindTooltip(pickupMessage, { permanent: true, direction: "top" });

        // Draw route line between points
        L.polyline([pickupLocation, dropoffLocation], { color: 'red', weight: 3 }).addTo(map);

        // Adjust map view to fit both markers
        map.fitBounds([pickupLocation, dropoffLocation], { padding: [50, 50] });

        // Disable dragging
        map.dragging.disable();
    }

    window.plotMap = plotMap;
    //plotMap(dropoffPin,pickupPin, estimatedDate, deliveryCountry);






















// document.addEventListener("DOMContentLoaded", function () {
//     // Function to get coordinates from PIN code using Nominatim API
//     async function getCoordinates(pinCode, baseCountry) {
//         const url = `https://nominatim.openstreetmap.org/search?postalcode=${pinCode}&country=${baseCountry}&format=json&limit=1`;
//         try {
//             const response = await fetch(url);
//             const data = await response.json();
//             if (data.length > 0) {
//                 const { lat, lon } = data[0];
//                 return [parseFloat(lat), parseFloat(lon)];
//             } else {
//                 console.error(`No coordinates found for PIN code ${pinCode}`);
//                 return null;
//             }
//         } catch (error) {
//             console.error(`Error fetching coordinates: ${error.message}`);
//             return null;
//         }
//     }

//     // Main function to plot the map with PIN codes
//     async function plotMap(dropoffPin, estimatedDate, deliveryCountry) {
//         const dropoffLocation = await getCoordinates(dropoffPin, deliveryCountry);

//         if (dropoffLocation) {
//             // Initialize the map and center it on the drop-off location
//             const map = L.map('bt_sst_leaflet_map_location', {
//                 center: dropoffLocation,
//                 zoom: 14, // Adjust zoom level as needed
//                 scrollWheelZoom: false, // Disable scroll wheel zoom
//                 touchZoom: true, // Enable pinch-to-zoom
//                 doubleClickZoom: true // Enable double-click zoom
//             });

//             // Use OpenStreetMap tiles
//             L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//                 maxZoom: 14,
//             }).addTo(map);

//             // Add a marker for the drop-off location
//             L.marker(dropoffLocation, {
//                 icon: L.divIcon({
//                     className: 'custom-icon',
//                     html: '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAACXBIWXMAAAsTAAALEwEAmpwYAAAEDElEQVR4nO2b34scRRDH61ARjRp/IFE4sts1KxfFB0F8ETQRkqiJYBJ/gT+e/PEH+KxEFO92qvaQoBjii2+Khz6YQ4gxAROffBASBXNeQPGyVeNxMXqnxsQfGendUw8vMTe93TOzt/uBgmVhp6u+U91dUz0L0KdPnz59+gTlptemL8NYNxnSOrKMI+sEsp4wJL9Zs5+R9AiS7kbWEWS91/4Gupo0HbCBIMuYIT2FrGlG+xVJ3olI77HXgm7CUPIQknzuEPTZjfQQsm6DsoPxsRsM6T5vgS8WYm80OlWDMmJIH0CWH4MFP2+GZQ5JHoXSkKYDhuWF0IEvEoK0XvzakKYDhmRn3sH/a/J6oSJge8sqKPi/M0FeKiT4KG4+UnTw/4jQkMcKWO3lp6IDX5AFszWajnITwITc6lyNdG8+wbM+WHiw5xRBtoYvb8ljhec/Cw4F3RWwVdt37OiHpiFPVePm0CpOVlirNGRNleRpm8adXr/akLsDCiBjHTg3YWK543xjVFnvRNKvXMcxpG8HCX4onrnc8anOFiwfV1755sqljrV65NurkOSA4zQ4ef12ubRM6T+RJfiFIhjWSadpQLLRuwCm1cxwSMklpP25iDhZ55gFwyHm/3gRe7NTzUH6PvgGHRYmu9p3Om7U0GccBDgCvjGsxzPPxbg51Om4dovMLDzpDPjGsJ7O6oiPxmaroZpdgFPgG9Pu3uYuQG3H8SsyC8B6GnyDtnVdxBSo640Oa8D34Bts9/K7YxFk+RJ8Y1g+yO6IftTxuCT7HQQYB98g6bCDAGkUJ3e5jlmNda3LmIblxQBTINns4oytH2xZm3W8wdGpqw3pUSfR7WmSb1ZxsgJZf3ETQQ5kEcEGj6wHHcf6OcjDkAVZ3nVyqr01TdraHs6DnTKud35+/o9BKKokG90dmxeCdJ9d2e32ZusEa/az/c5twfvv9ZvrQx+EfNGpkwHtcPCDkojk/hIEeva7Hyf3QR6YXm6LW1ZzYkp1MMIyVxlJqpAnyLoNSc4UHbz1ocrJw7kGX6bDUesDFPxuwJuFpT7pW4W/I3DrrvQiJN2Tf/Cyv7Zj8mIoA0P2zID1sxwFOIz1EyuhTNRe1mtd+/iZjOTrCk1fB2WkRtMRknwXcM7P+OgyBcWw3hakRiA9GTWat0M3gPa1WJbfPab9H1FDtkA3YUgf91IokZwxLE9CN4Kkz3vIgOegm0HSVzsI/g3oesbSCwzJe9mDl/G129MLYTkwODp1CZJ+kiH4T20PEpYTa4ab19gDiyXs9UdtUQXLkVr92KAhnfofAST35/q8QU5uRtIfFt95ma1Q8xboBSJO1i184ar136GGbIBeImq9bC1/tjs6+gT0Iob0WWtF+9GnD/QufwFmrtOpY71Z6gAAAABJRU5ErkJggg==" style="width:35px;" />',
//                     iconSize: [32, 32],
//                 }),
//             }).addTo(map).bindTooltip(estimatedDate, {
//                 permanent: true,
//                 direction: "center",
//                 offset: [0, -40]
//             }).openTooltip();

//             // Adjust map view to fit the drop-off marker
//             const bounds = L.latLngBounds([dropoffLocation]);
//             map.fitBounds(bounds, { padding: [50, 50] });

//             // Disable dragging
//             map.dragging.disable();
//         } else {
//             console.error("No coordinates found for the drop-off location.");
//         }
//     }

//     // Expose plotMap as a global function to use in your PHP script
//     window.plotMap = plotMap;
//     plotMap(dropoffPin, estimatedDate, deliveryCountry);

// });
