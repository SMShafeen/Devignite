<?php

include "config.php";

?>

<!DOCTYPE html>

<head>
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Using MySQL and PHP with Google Maps</title>
  <style>
    /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
    #map {
      height: 100%;
      width: 100%;
    }
    .parent{
      overflow: scroll;
    }

    /* Optional: Makes the sample page fill the window. */
    html,
    body {
      height: 100%;
      margin: 0;
      padding: 0;
    }
  </style>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>

<body class="overflow-x-hidden" onload="initMap()">
  <!-- Navbar goes here... -->
  <nav class="flex justify-between bg-gray-900">
    <div class="website_name pl-28 pt-6 pb-6 text-[1.5rem] text-white text-3xl font-semibold">
      Green Express Path
    </div>
    <div class="links pt-6 space-x-8 text-white">
      <a href="#" class="home pr-12 text-[1.15rem]">Home</a>
      <a href="#" class="about pr-12 text-[1.15rem]">About</a>
      <a href="#" class="contact pr-60 text-[1.15rem]">Contact</a>
      <!-- <a
              href="#"
              class="login px-4 py-0.5 rounded-[10px] text-[1.3rem] border border-green-500 border-"
              >login</a
            >
            <a
              href="#"
              class="signup bg-green-500 pt-1 pb-1 pr-3 pl-3 rounded-[10px] text-[1.3rem]"
              >SignUp</a
            > -->
      <a href="map.php"
        class="contact text-[1.25rem] bg-gradient-to-r from-green-700 via-green-500 to-gray-200 text-transparent" style="
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
          ">Locate Charger</a>
    </div>
  </nav>

    
    <div class="parent bg-sky-200 w-screen h-screen bg-opacity-0 flex overflow-x-hidden">
      <div class="h-screen overflow-y-scroll w-[40%]">

      <div class="searchbar">
        <input
          type="text" id="source"
          class="border w-[88%] rounded-lg h-[40px] placeholder:px-3 placeholder:text-gray-400 p-2  m-6 mb-2 shadow-md"
          placeholder="Enter your location..."
        />
        <input
          type="text" id="destination"
          class="border w-[88%] rounded-lg h-[40px] placeholder:px-3 placeholder:text-gray-400 p-2  m-6 shadow-md"
          placeholder="Enter your destination..."
        />
        <div class="button mt-2">
            <a href="map.php" class="btn bg-white text-blue-950 p-2 ml-[100px] rounded-lg hover:bg-blue-950 hover:text-white hover: border border-blue-950 cursor-pointer">Go Back</a>
            <a onclick="calcRoute()" class="btn bg-blue-950 text-white p-2 ml-[30px] rounded-lg hover:bg-white hover:text-blue-950 hover: border border-blue-950 cursor-pointer">Get Route</a>
        </div>
      </div>

      <div class="card-wrap w-[400px]">

      <?php
            $select_locations = mysqli_query($conn, "SELECT * FROM `markers`") or die('Query Failed!');
            while($fetch_locations = mysqli_fetch_assoc($select_locations)){
        ?>
        <div class="card bg-white h-[150px] m-6 rounded-md shadow-md w-[88%] cursor-pointer hover:bg-rgba(0,0,0,0.3)">
          <div class="card__content p-4">
            <div class="card__content--first flex justify-between">
              <div class="card__location_name">
                <h1 class="px-2 font-semibold text-md">
                <?php echo $fetch_locations['name'] ?>
                </h1>
                <h2 class="px-2 text-gray-300 text-sm"><?php echo $fetch_locations['address'] ?></h2>
              </div>
              <div class="card__star">
                <span
                  ><ion-icon
                    name="star"
                    class="text-orange-400"
                  ></ion-icon></span
                >4.1
              </div>
            </div>
            <div class="card__content--second flex justify-between">
              <div class="card__avail--stats text-green-400 text-sm px-2 mt-2">
                Available
              </div>
              <div class="connect__type bg-gray-300 p-1 rounded-md">DC</div>
            </div>
          </div>
        </div>
        <?php
            }
        ?>

      </div>
      </div>

      <div id="map" class="h-screen  w-[80%]"></div>
    </div>

  
    <!-- footer goes here... -->
  
  
  
    <footer class="h-32 bg-gray-900 text-white z-10">
    <div class="footer__data">
      <div class="footerlinks m-5 mt-0 p-3">
        <a href="#" class="text-lg">Privacy Policy</a> <span>|</span>
        <a href="#" class="text-lg">Terms & Condition</a>
        <div class="footer__copy">
          <span class="text-sm">Copyright @2022 Green Express Path.</span>
        </div>
      </div>
    </div>
  </footer>


  

  <script>

// window.onload = function(){
    //   let input = document.getElementById('search-input')
    //   // let map = new google.maps.Map
    //   let marker = new google.maps.Marker({
    //             map:map
    //           })
    //           let options = {
    //             types:['(cities)'],
    //             componentRestrictions:{
    //               country:['us']
    //             }
    //           }
    //           let autocomplete = new google.maps.places.Autocomplete(input, options)
    //           autocomplete.bindTo('bounds', map)
    //           autocomplete.addListener('place_changed', function(){
    //             marker.setVisible(false);
    //             var place = autocomplete.getPlace();
    //           })
    // }

    var customLabel = {
      // restaurant: {
      //   label: 'EV'
      // },
      // bar: {
      //   label: 'EV'
      // }
      station: {
        label: 'EV'
      }
    };

    let directionsService, directionsRenderer
    let sourceAutocomplete, destAutocomplete
    window.onload = function initMap() {
      //  input = document.getElementById('search-input')
      var map = new google.maps.Map(document.getElementById('map'), {
        center: new google.maps.LatLng(18.9733536, 72.82810492),
        zoom: 10
      });
      var infoWindow = new google.maps.InfoWindow;
      google.maps.event.addListener(map, "click", function(event){

      })
      directionsService = new google.maps.DirectionsService()
      directionsRenderer = new google.maps.DirectionsRenderer()
      directionsRenderer.setMap(map)

      sourceAutocomplete = new google.maps.places.Autocomplete(
          document.getElementById('source')
      )
      destAutocomplete = new google.maps.places.Autocomplete(
          document.getElementById('destination')
      )

      // Change this depending on the name of your PHP or XML file
      downloadUrl('http://localhost/hackathon/ero/xml.php', function (data) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName('marker');
        Array.prototype.forEach.call(markers, function (markerElem) {
          var id = markerElem.getAttribute('id');
          var name = markerElem.getAttribute('name');
          var address = markerElem.getAttribute('address');
          var type = markerElem.getAttribute('type');
          var point = new google.maps.LatLng(
            parseFloat(markerElem.getAttribute('lat')),
            parseFloat(markerElem.getAttribute('lng')));

          var infowincontent = document.createElement('div');
          var strong = document.createElement('strong');
          strong.textContent = name
          infowincontent.appendChild(strong);
          infowincontent.appendChild(document.createElement('br'));

          var text = document.createElement('text');
          text.textContent = address
          infowincontent.appendChild(text);
          var icon = customLabel[type] || {};
          var marker = new google.maps.Marker({
            map: map,
            position: point,
            label: icon.label
          });
          marker.addListener('click', function () {
            infoWindow.setContent(infowincontent);
            infoWindow.open(map, marker);
          });
        });
      });

      let input = document.getElementById('search-input')
      // let map = new google.maps.Map
      let tmpmarker = new google.maps.Marker({
        map: map,
        // position: place.geometry.location,
      })
      let options = {
        types: ['(cities)'],
        componentRestrictions: {
          country: []
        }
      }
      let autocomplete = new google.maps.places.Autocomplete(input)
      autocomplete.bindTo('bounds', map)
      autocomplete.addListener('place_changed', function(){
        tmpmarker.setVisible(false);
        var place = autocomplete.getPlace();

        map.panTo(place.geometry.location);
      })

    }

    function calcRoute(){
        var source = document.getElementById('source').value
        var destination = document.getElementById('destination').value

        let request = {
            origin:source,
            destination:destination,
            travelMode:'DRIVING'
        }
        directionsService.route(request, function(result, status){
            if(status == "OK"){
                directionsRenderer.setDirections(result)
            }
        })
    }



    function downloadUrl(url, callback) {
      var request = window.ActiveXObject ?
        new ActiveXObject('Microsoft.XMLHTTP') :
        new XMLHttpRequest;

      request.onreadystatechange = function () {
        if (request.readyState == 4) {
          request.onreadystatechange = doNothing;
          callback(request, request.status);
        }
      };

      request.open('GET', url, true);
      request.send(null);
    }

    function doNothing() { }

  </script>
  <!-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-dFHYjTqEVLndbN2gdvXsx09jfJHmNc8&callback=initMap"></script> -->
 >
 <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDkm_qb9EcN8RdQ5dI10oHd6eA_tXk26Uk&libraries=places,marker&callback=initMap"
    async defer></script>
</body>

</html>