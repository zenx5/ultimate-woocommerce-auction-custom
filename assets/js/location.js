(function(){
    let put = true
    let map;
    let marker = null
    let markerEnd = null

    function toogleStyleInput(){
        const color1 = '#8c8f94'
        const color2 = '#7788ff'
        document.querySelector('#woo_ua_latitude').style.borderColor = !put ? color1 : color2
        document.querySelector('#woo_ua_longitude').style.borderColor = !put ? color1 : color2
        document.querySelector('#woo_ua_latitude_end').style.borderColor = put ? color1 : color2
        document.querySelector('#woo_ua_longitude_end').style.borderColor = put ? color1 : color2
    }

    function isPositioned(label){
        return (document.querySelector(`#woo_ua_latitude${label}`).value !== '' &&
        document.querySelector(`#woo_ua_longitude${label}`).value !== '')
    }

    navigator.geolocation.getCurrentPosition( position => {
        const { latitude, longitude } = position.coords
        document.querySelector('#woo_ua_latitude_current').value = latitude
        document.querySelector('#woo_ua_longitude_current').value = longitude
    })

    function calculateAndDisplayRoute(origin, destination, directionsService, directionsRenderer) { 
        directionsService
            .route({
                origin: origin,
                destination: destination,
                travelMode: google.maps.TravelMode["DRIVING"]
            })
            .then((response) => {
                directionsRenderer.setDirections(response);
            })
            .catch((e) => console.log("Directions request failed due to " + status));
    }

    function handlerClickMap(directionsService, directionsRenderer) {
        return function(mapsMouseEvent){
            if( put ){
                if( !marker ){
                    marker = new google.maps.Marker({
                        position: mapsMouseEvent.latLng,
                        title: "Hasta",
                    });
                }else{
                    marker.setPosition(mapsMouseEvent.latLng)	
                }
                document.querySelector('#woo_ua_latitude').value = mapsMouseEvent.latLng.lat()
                document.querySelector('#woo_ua_longitude').value = mapsMouseEvent.latLng.lng()
            }else{
                if( !markerEnd ){
                    markerEnd = new google.maps.Marker({
                        position: mapsMouseEvent.latLng,
                        title: "Desde",
                    });
                }else{
                    markerEnd.setPosition(mapsMouseEvent.latLng)	
                }
                document.querySelector('#woo_ua_latitude_end').value = mapsMouseEvent.latLng.lat()
                document.querySelector('#woo_ua_longitude_end').value = mapsMouseEvent.latLng.lng()
            }
            put = !put
            if( marker && markerEnd ){
                calculateAndDisplayRoute(
                    { lat: marker.getPosition().lat(), lng: marker.getPosition().lng() }, 
                    { lat: markerEnd.getPosition().lat(), lng: markerEnd.getPosition().lng() }, 
                    directionsService, 
                    directionsRenderer
                );
            }
            toogleStyleInput()
        }
    }

    function initMap() {        
        let latitude = document.querySelector('#woo_ua_latitude_current').value
        let longitude = document.querySelector('#woo_ua_longitude_current').value
        const directionsRenderer = new google.maps.DirectionsRenderer();
        const directionsService = new google.maps.DirectionsService();

        map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
            zoom: 13,
        });
        directionsRenderer.setOptions({ preserveViewport: true })
        directionsRenderer.setMap( map )
        
        

        new google.maps.Marker({
            position: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
            map,
            icon: {
                url: '../wp-content/plugins/ultimate-woocommerce-auction-custom/assets/here.png',
                size: new google.maps.Size(32,32),
                scaledSize: new google.maps.Size(32,32),
                //origin: new google.maps.Point(100, 0)
                //anchor: new google.maps.Point(0, 32),

            },
            title: "Mi posicion",
            color: 'yellow'
        });
        
        if( isPositioned('') && marker === null ){
            marker = new google.maps.Marker({
                position: { 
                    lat: parseFloat(document.querySelector('#woo_ua_latitude').value), 
                    lng: parseFloat(document.querySelector('#woo_ua_longitude').value) 
                },
                title: "Desde",
            });
            map.setCenter(
                {
                    lat: parseFloat(document.querySelector('#woo_ua_latitude').value),
                    lng: parseFloat(document.querySelector('#woo_ua_longitude').value)
                }
            )
        }
        if( isPositioned('_end') && markerEnd === null ){
            markerEnd = new google.maps.Marker({
                position: { 
                    lat: parseFloat(document.querySelector('#woo_ua_latitude_end').value), 
                    lng: parseFloat(document.querySelector('#woo_ua_longitude_end').value) 
                },
                title: "Hasta",
            });
        }
        map.addListener("click", handlerClickMap(directionsService, directionsRenderer));

        if( marker && markerEnd ){
            calculateAndDisplayRoute(
                { lat: marker.getPosition().lat(), lng: marker.getPosition().lng() }, 
                { lat: markerEnd.getPosition().lat(), lng: markerEnd.getPosition().lng() }, 
                directionsService, 
                directionsRenderer
            );
        }
        toogleStyleInput()
    }
    window.initMap = initMap;
})()