<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <!-- Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <!-- Leaflet Routing Machine -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    <!------ Include the above in your HEAD tag ---------->

    <style>
        @import url('https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');

        @media(min-width:768px) {
            body {
                margin-top: 50px;
            }

            /*html, body, #wrapper, #page-wrapper {height: 100%; overflow: hidden;}*/
        }

        #wrapper {
            padding-left: 0;
        }

        #page-wrapper {
            width: 100%;
            padding: 0;
            background-color: #fff;
        }

        @media(min-width:768px) {
            #wrapper {
                padding-left: 225px;
            }

            #page-wrapper {
                padding: 22px 10px;
            }
        }

        /* Top Navigation */

        .top-nav {
            padding: 0 15px;
        }

        .top-nav>li {
            display: inline-block;
            float: left;
        }

        .top-nav>li>a {
            padding-top: 20px;
            padding-bottom: 20px;
            line-height: 20px;
            color: #fff;
        }

        .top-nav>li>a:hover,
        .top-nav>li>a:focus,
        .top-nav>.open>a,
        .top-nav>.open>a:hover,
        .top-nav>.open>a:focus {
            color: #fff;
            background-color: #1a242f;
        }

        .top-nav>.open>.dropdown-menu {
            float: left;
            position: absolute;
            margin-top: 0;
            /*border: 1px solid rgba(0,0,0,.15);*/
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            background-color: #fff;
            -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, .175);
            box-shadow: 0 6px 12px rgba(0, 0, 0, .175);
        }

        .top-nav>.open>.dropdown-menu>li>a {
            white-space: normal;
        }

        /* Side Navigation */

        @media(min-width:768px) {
            .side-nav {
                position: fixed;
                top: 60px;
                left: 225px;
                width: 225px;
                margin-left: -225px;
                border: none;
                border-radius: 0;
                border-top: 1px rgba(0, 0, 0, .5) solid;
                overflow-y: auto;
                background-color: #37517e;
                /*background-color: #5A6B7D;*/
                bottom: 0;
                overflow-x: hidden;
                padding-bottom: 40px;
            }

            .side-nav>li>a {
                width: 225px;
                border-bottom: 1px rgba(0, 0, 0, .3) solid;
            }

            .side-nav li a:hover,
            .side-nav li a:focus {
                outline: none;
                background-color: #1a242f !important;
            }
        }

        .side-nav>li>ul {
            padding: 0;
            border-bottom: 1px rgba(0, 0, 0, .3) solid;
        }

        .side-nav>li>ul>li>a {
            display: block;
            padding: 10px 15px 10px 38px;
            text-decoration: none;
            /*color: #999;*/
            color: #fff;
        }

        .side-nav>li>ul>li>a:hover {
            color: #fff;
        }

        .navbar .nav>li>a>.label {
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            border-radius: 50%;
            position: absolute;
            top: 14px;
            right: 6px;
            font-size: 10px;
            font-weight: normal;
            min-width: 15px;
            min-height: 15px;
            line-height: 1.0em;
            text-align: center;
            padding: 2px;
        }

        .navbar .nav>li>a:hover>.label {
            top: 10px;
        }

        .navbar-brand {
            padding: 5px 15px;
        }

        .logo1 {
            border-radius: 50%;
        }

        .panel.panel-blue {
            border-radius: 0px;
            box-shadow: 0px 0px 10px #888;
            border-color: #266590;
        }

        .panel.panel-blue .panel-heading {
            border-radius: 0px;
            color: #FFF;
            background-color: #266590;
        }

        .panel.panel-blue .panel-body {
            background-color: #F2F2F2;
            color: #4D4D4D;
        }

        #collection-map {
            height: 500px;
            width: 100%;
            margin-bottom: 20px;
            border: 2px solid #37517e;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .map-card {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            border: 1px solid #ddd;
        }
    </style>
</head>

<body>
    <!-- Favicons -->
    <link href="adminlogin/Capture.PNG" rel="icon">

    <div id="throbber" style="display:none; min-height:120px;"></div>
    <div id="noty-holder"></div>
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" style='background-color:#37517e'>
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/index.html">
                    <img src="Capture.PNG" alt="LOGO" heigth='50' width='50' class='logo1'>
                </a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                <li>
                    <a href="#" data-placement="bottom" data-toggle="tooltip">Admin User
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li>
                        <a href="/index.html" data-toggle="collapse" data-target="#submenu-1"><i
                                class="fa fa-fw fa-search"></i><mark> Dashboard</mark> <i
                                class="fa fa-fw fa-angle-down pull-right"></i></a>

                    </li>
                    <li>
                        <a href="../new-password.php"><i class="fa fa-fw fa-paper-plane-o"></i> Change password</a>
                    </li>
                    <li>
                        <a href="logout.php"><i class="fa fa-fw fa fa-question-circle"></i> Logout</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row" id="main">
                    <div class="col-sm-12 col-md-12 well" id="content">
                        <h1>Welcome Admin!</h1>

                        <!-- MAP SECTION -->
                        <div class="map-card">
                            <h3><i class="fa fa-map-marker"></i> Garbage Collection Route Map</h3>
                            <p class="text-muted">Below is the optimized collection route for all <strong>Approved</strong> complaints, starting from the central station.</p>
                            <div id="collection-map"></div>
                            <div id="route-info" class="alert alert-info" style="display:none;">
                                <strong>Collection Plan:</strong> Generating optimal route between locations...
                            </div>
                        </div>

                        <!--table start  -->
                        <table cellspacing:="12" class='table'>
                            <tr class="panel-heading">
                                <th> Id </th>
                                <th> Images </th>
                                <th> Date </th>
                                <th> Name </th>
                                <th> Mobile </th>
                                <th> Email </th>
                                <th> Waste Category </th>
                                <th>Location</th>
                                <th>Location Description</th>
                                <th>Status</th>
                                <th>Action</th>
                                <th>Update status</th>
                            </tr>

                            <?php
                            // error_reporting(0);
                            
                            include("connection.php");
                            $hostForImage = "/phpGmailSMTP/upload/";
                            $query = "select * from garbageinfo";
                            $data = mysqli_query($db, $query);
                            $total = mysqli_num_rows($data);

                            if ($total != 0) {


                                while ($result = mysqli_fetch_assoc($data)) {


                                    echo "
           <tr class='panel panel-blue'>

               <td>   " . $result['Id'] . " </td>
               <td><a href = '" . $hostForImage . $result['file'] . "'><img src = '" . $hostForImage . $result['file'] . " 'height='200'width='200'/></a> </td>               
               <td>   " . $result['date'] . " </td>
               <td>   " . $result['name'] . " </td>
               <td>   " . $result['mobile'] . "  </td>
               <td>   " . $result['email'] . " </td>
               <td>   " . $result['wastetype'] . " </td>
               <td>   " . $result['location'] . " </td>
               <td>   " . $result['locationdescription'] . "  </td>
                <td>   " . $result['status'] . "  </td>
               <td><a href = 'admindelete.php?i=$result[Id] 'class='btn btn-danger'>Delete</a></td>
              <td> <a href = 'status.php?i=$result[Id]&s=$result[status] 'class='btn btn-success'>Status</a></td>

           </tr> ";

                                }


                            }
                            ?>
                        </table>

                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div><!-- /#wrapper -->
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
            $(".side-nav .collapse").on("hide.bs.collapse", function () {
                $(this).prev().find(".fa").eq(1).removeClass("fa-angle-right").addClass("fa-angle-down");
            });
            $('.side-nav .collapse').on("show.bs.collapse", function () {
                $(this).prev().find(".fa").eq(1).removeClass("fa-angle-down").addClass("fa-angle-right");
            });
        })

        var delId;
        function modalLauch(id) {
            delId = id;
            $('#toDeleteId').val(id);
        }
        function confirmDelete() {
            window.location.replace("/adminlogin/admindelete.php?i=" + delId);
        }

        // ==========================================
        // COLLECTION MAP LOGIC
        // ==========================================
        var map = L.map('collection-map').setView([23.0225, 72.5714], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        var locations = [];
        <?php
        // Reset pointer to fetch locations for JS
        mysqli_data_seek($data, 0);
        while ($locRow = mysqli_fetch_assoc($data)) {
            // Only show 'Approved' complaints on the map
            if ($locRow['status'] == 'Approved') {
                $safeLoc = addslashes($locRow['location']);
                $safeDesc = addslashes($locRow['locationdescription']);
                $safeName = addslashes($locRow['name']);
                $safeEmail = addslashes($locRow['email']);
                echo "locations.push({ id: " . $locRow['Id'] . ", address: '" . $safeLoc . "', description: '" . $safeDesc . "', type: '" . $locRow['wastetype'] . "', name: '" . $safeName . "', email: '" . $safeEmail . "' });\n";
            }
        }
        ?>

        if (locations.length > 0) {
            document.getElementById('route-info').style.display = 'block';
            var waypoints = [];
            var startLatLng = L.latLng(23.0225, 72.5714);

            // Sequential Geocoding to avoid rate limits
            async function resolveLocations() {
                document.getElementById('route-info').innerHTML = "<strong>Status:</strong> Locating " + locations.length + " collection points...";
                
                for (let i = 0; i < locations.length; i++) {
                    const loc = locations[i];
                    let lat, lon, found = false;
                    
                    // Multi-stage Geocoding Fallback
                    const searchQueries = [
                        loc.address, // Stage 1: Main address field
                        loc.address + " " + loc.description, // Stage 2: Combined
                        loc.description, // Stage 3: Just description (may contain specific road like 'G Road')
                        loc.address.split(',')[0] + ", Gandhinagar" // Stage 4: Simplified Gandhinagar search
                    ];

                    for (let query of searchQueries) {
                        if (!query || query.length < 3) continue;
                        try {
                            const response = await fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(query));
                            const data = await response.json();
                            if (data && data.length > 0) {
                                lat = data[0].lat;
                                lon = data[0].lon;
                                found = true;
                                break; 
                            }
                        } catch (err) { console.warn("Geocode attempt failed for:", query); }
                        await new Promise(resolve => setTimeout(resolve, 600)); // Small delay between fallbacks
                    }

                    if (found) {
                        // Add slight jitter for overlap
                        var finalLat = parseFloat(lat) + (Math.random() - 0.5) * 0.0005;
                        var finalLon = parseFloat(lon) + (Math.random() - 0.5) * 0.0005;
                        var pt = L.latLng(finalLat, finalLon);
                        
                        L.circleMarker(pt, {
                            radius: 7, fillColor: "#ff0000", color: "#fff", weight: 2, opacity: 1, fillOpacity: 0.9
                        }).addTo(map)
                            .bindPopup("<b>ID:</b> " + loc.id + 
                                      "<br><b>Name:</b> " + loc.name + 
                                      "<br><b>Email:</b> " + loc.email + 
                                      "<br><b>Type:</b> " + loc.type + 
                                      "<br><b>Address:</b> " + loc.address +
                                      "<br><b>Desc:</b> " + loc.description);
                        
                        waypoints.push(pt);
                    } else {
                        console.error("All geocoding stages failed for ID:", loc.id);
                    }
                    await new Promise(resolve => setTimeout(resolve, 800));
                }
                
                if (waypoints.length > 0) {
                    createRoute(waypoints);
                } else {
                    document.getElementById('route-info').innerHTML = "<strong>Error:</strong> Could not locate any approved complaints on the map.";
                }
            }

            resolveLocations();

            function createRoute(pts) {
                // Shortest Path Optimization: Nearest Neighbor Algorithm
                let orderedPts = [];
                let currentPos = startLatLng;
                let remainingPts = [...pts];

                while (remainingPts.length > 0) {
                    let nearestIdx = 0;
                    let minDist = currentPos.distanceTo(remainingPts[0]);

                    for (let j = 1; j < remainingPts.length; j++) {
                        let d = currentPos.distanceTo(remainingPts[j]);
                        if (d < minDist) {
                            minDist = d;
                            nearestIdx = j;
                        }
                    }

                    let nearest = remainingPts.splice(nearestIdx, 1)[0];
                    orderedPts.push(nearest);
                    currentPos = nearest;
                }

                // Circular route: Start -> Optimized Sequence -> Start
                var allWaypoints = [startLatLng].concat(orderedPts).concat([startLatLng]);

                // Marker for start point
                L.marker(startLatLng, {
                    icon: L.divIcon({
                        className: 'custom-div-icon',
                        html: "<div style='background-color:#266590; color:white; padding:5px; border-radius:50%; width:30px; height:30px; text-align:center; line-height:20px; border:2px solid white; box-shadow:0 2px 4px rgba(0,0,0,0.3);'><b>H</b></div>",
                        iconSize: [30, 30],
                        iconAnchor: [15, 15]
                    })
                }).addTo(map).bindPopup("<b>Headquarters (Collection Hub)</b>");

                var truckIcon = L.icon({
                    iconUrl: 'assets/3d_truck.png',
                    iconSize: [50, 50],
                    iconAnchor: [25, 25]
                });

                var truckMarker = L.marker(startLatLng, { icon: truckIcon, zIndexOffset: 2000 }).addTo(map);

                var control = L.Routing.control({
                    waypoints: allWaypoints,
                    routeWhileDragging: false,
                    addWaypoints: false,
                    draggableWaypoints: false,
                    createMarker: function () { return null; },
                    show: false, // Lean UI: Hide the instructions panel
                    lineOptions: {
                        styles: [{ color: '#37517e', opacity: 0.6, weight: 6 }]
                    }
                }).addTo(map);

                control.on('routesfound', function (e) {
                    var routes = e.routes;
                    var summary = routes[0].summary;
                    var dist = (summary.totalDistance / 1000).toFixed(2);
                    document.getElementById('route-info').innerHTML = "<strong>Collection Hub:</strong> Truck is stationed at Headquarters. Total path distance: " + dist + " km.";
                });

            }
        } else {
            document.getElementById('route-info').style.display = 'block';
            document.getElementById('route-info').className = "alert alert-warning";
            document.getElementById('route-info').innerHTML = "<strong>No Tasks:</strong> No approved complaints pending collection.";
        }
    </script>
</body>

</html>