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
      /* position: relative;
      right: 0; */
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

<html>

<body class="overflow-x-hidden">
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

  
  

  <!-- <input type="text" name="" id="search-input" placeholder="Search a place..."> -->

    
    <div class="parent bg-sky-200 w-screen h-screen bg-opacity-0 flex overflow-x-hidden">

    <div class="second__card--parent bg-sky-200 w-[50%] m-auto bg-opacity-0">
        <?php
            if(isset($_GET['vendor'])){
                $vendor_id = $_GET['vendor'];
                $query = mysqli_query($conn, "SELECT * FROM `markers` WHERE ID = '$vendor_id'") or die('Query Failed!');
            }
            if(mysqli_num_rows($query) > 0){
                $fetch_vendor = mysqli_fetch_assoc($query);
            }
        ?>
      <div class="second__card m-6 bg-white border w-[80%] m-auto shadow-md">
        <div class="second__card--video p-2 relative w-full">
          <div
            class="back-to-fcard rounded-full bg-gray-300 w-8 h-8 top-3 absolute left-4 grid justify-center items-center"
          >
          <a href="map.php"><img src="img/arrow.jgp" alt="" width="15px" /></a>
          </div>
          <video
            src="img/car__animation.mp4"
            class="w-80"
            autoplay
            muted
            loop
          ></video>
        </div>
        <div class="card__content--first flex justify-between">
          <div class="card__location_name">
            <h1 class="px-2 font-semibold text-md">
            <?php echo $fetch_vendor['name']; ?>
            </h1>
            <h2 class="px-2 text-gray-300 text-sm"><?php echo $fetch_vendor['address']; ?></h2>
            <p class="open__status p-2">
              <span class="text-green-400 text-sm pr-2">Open Now </span>00:00 To
              23:59
            </p>
            <h1 class="charger__type font-semibold p-2">
            <?php echo $fetch_vendor['name'] . ', ' . $fetch_vendor['city'] . ', ' . $fetch_vendor['state'] . '.'; ?>
            </h1>
          </div>
        </div>
        <div class="charge__box p-2 flex justify-between">
          <div
            class="border border-gray-300 text-gray-400 w-30 rounded-sm px-2"
          >
            DC | 29kw
          </div>
          <div class="text-sm text-gray-400">&#8377; 15.99/Kwh</div>
        </div>

        <div class="charger-avail_data">
          <div
            class="charger__avail border border-gray-300 w-[90%] flex justify-between m-2 p-2 rounded-md mb-10"
          >
            <div class="avail px-1">
              <h1>Connector 1</h1>
              <p class="text-sm text-gray-500">CCS-2</p>
            </div>
            <div class="avail-status text-green-400 px-1 flex items-center">
              Available
            </div>
            <div class="charger__type">
              <img src="img/ccs-2.jpg" alt="" class="w-10" />
            </div>
          </div>
        </div>
      </div>
    </div>

      <div id="map" class="h-screen  w-[70%]"></div>
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

    window.onload = function initMap() {
      //  input = document.getElementById('search-input')
      var map = new google.maps.Map(document.getElementById('map'), {
        center: new google.maps.LatLng(<?php echo $fetch_vendor['lat']; ?>, <?php echo $fetch_vendor['lng']; ?>),
        zoom: 20
      });
      var infoWindow = new google.maps.InfoWindow;

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
  <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDkm_qb9EcN8RdQ5dI10oHd6eA_tXk26Uk&libraries=places&callback=initMap"
    async defer></script>
</body>

</html>