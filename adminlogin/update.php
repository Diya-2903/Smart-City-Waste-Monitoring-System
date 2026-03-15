<?php
include_once('connection.php');

// error_reporting(0);
$id = $_GET['i'];
$n = $_GET['n'];
$mbl = $_GET['mbl'];
$em = $_GET['em'];
$wt = $_GET['wt'];
$lo = $_GET['lo'];
$lod = $_GET['lod'];
$f = $_GET['f'];
$d = $_GET['d'];

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $checkbox1 = $_POST['wastetype'];
    $chk = "";
    foreach ($checkbox1 as $chk1) {
        $chk .= $chk1 . ",";
    }

    $email = $_POST['email'];
    $location = $_POST['location'];
    $locationdescription = $_POST['locationdescription'];
    $date = $_POST['date'];
    // @unlink('upload/'.$f[0]['file']) ;

    $file = $_FILES['file']['name'];
    $target_dir = "upload/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);

    // Select file type
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Valid file extensions
    $extensions_arr = array("jpg", "jpeg", "png", "gif", "tif", "tiff");

    //validate file size 
    //   $filesize = $_FILES["file"]["size"] < 5 * 1024 ;

    // Check extension
    if (in_array($imageFileType, $extensions_arr)) {


        // Upload file
        move_uploaded_file($image = $_FILES['file']['tmp_name'], $target_dir . $file);

    }
    $query = "update garbageinfo set name='$name',mobile='$mobile',email='$email',wastetype='$chk',location='$location',locationdescription='$locationdescription',file= '$file',date='$date' WHERE Id='$id'";

    $data = mysqli_query($db, $query);


    if ($data) {

        echo " <span style='color:red'>Record Updated!</span>";

        header("Location: welcome.php", TRUE, 301);
        exit();

    } else {
        echo "Failed to Update!";
    }



}
?>
<!DOCTYPE html>
<html>

<head>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- LEAFLET CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" type="text/css" href="styleupdate.css">
    <title>Edit || Update</title>
    <style>
        #map {
            height: 300px;
            width: 100%;
            margin-top: 10px;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>

    <?php
    $error = '';
    echo $error;
    ?>
    <form method="post" action="update.php" enctype="multipart/form-data">
        <div class="container contact">
            <div class="row">
                <div class="col-md-3">
                    <div class="contact-info">
                        <img src="images.jfif" alt="image" />
                        <h2>Edit Complain</h2>
                        <h4>Please provide valid Information !</h4>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="contact-form">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="fname"> Name:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" placeholder="Enter Your Name"
                                    name="name" required value="<?php echo "$n" ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="lname">Mobile:</label>
                            <div class="col-sm-10">
                                <input value="<?php echo $id ?>" name="id" style="display:none">
                                <input type="number" class="form-control" id="mobile"
                                    placeholder="Enter Your Mobile Number" name="mobile" required
                                    value="<?php echo "$mbl" ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10">
                                <input type="hidden" class="form-control" id="email" placeholder="Enter Your email"
                                    name="email" value="<?php echo "$em" ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="option">Category:</label>
                            <div class="col-sm-10">
                                <input type="checkbox" name="wastetype[]" value="organic">Organic
                                <input type="checkbox" name="wastetype[]" value="inorganic">Inorganic
                                <input type="checkbox" name="wastetype[]" value="Household">Metallic
                                <input type="checkbox" name="wastetype[]" value="mixed" checked>All
                            </div>
                        </div>

                        <!-- LOCATION SECTION -->
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="lname">Location:</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="location" name="location"
                                        value="<?php echo "$lo" ?>" placeholder="Enter Location" required
                                        onblur="updateMap()">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="updateMap()"
                                            title="Search on Map"><i class="fa fa-map-marker"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-10">
                                <div id="map-message"></div>
                                <div id="map"></div>
                                <small class="form-text text-muted">You can also click on the map to set your
                                    location.</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-10">
                                <input type="comment" class="form-control" rows="5" id="locationdescription"
                                    placeholder="Enter Location details..." name="locationdescription"
                                    value="<?php echo "$lod" ?>" required onblur="updateMap()">
                            </div>
                        </div>

                        <!-- CAMERA/PICTURE SECTION -->
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="lname">Pictures:</label>
                            <div class="col-sm-10">
                                <div style="display: flex; align-items: center;">
                                    <!-- Camera Trigger -->
                                    <div onclick="openCameraModal()" style="cursor: pointer; margin-right: 15px;"
                                        title="Tap to Open Live Camera">
                                        <i class="fa fa-camera" style="font-size: 30px; color: #37517e;"></i>
                                    </div>
                                    <!-- Existing value display/hidden input not handled for file, just new file upload -->
                                    <input type="file" class="form-control" id="file" name="file" accept="image/*"
                                        capture="environment" onchange="previewImage(this)">
                                </div>
                                <small class="text-muted">Current File: <?php echo "$f" ?></small>
                                <div id="image-preview" style="margin-top: 10px;"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="hidden" class="form-control" id="date" name="date"
                                    value="<?php $timezone = date_default_timezone_set("Asia/Kathmandu");
                                    echo date("g:ia ,\n l jS F Y"); ?>">
                                <button type="submit" class="btn btn-default" name="update" id="update">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    </div>

    <!-- LEAFLET MAP SCRIPTS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([23.0225, 72.5714], 12); // Default
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        var marker;

        // Initialize map with existing location if available
        window.onload = function () {
            var existingLoc = document.getElementById('location').value;
            if (existingLoc) {
                // Try to geocode existing location text to place marker
                fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(existingLoc))
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            setMarker(data[0].lat, data[0].lon, existingLoc);
                        }
                    });
            }
        };

        function showMessage(msg, type) {
            var msgDiv = document.getElementById('map-message');
            msgDiv.innerHTML = '<div class="alert alert-' + type + '">' + msg + '</div>';
            setTimeout(() => { msgDiv.innerHTML = ''; }, 5000);
        }

        function updateMap() {
            var locationInput = document.getElementById('location').value;
            var descInput = document.getElementById('locationdescription').value;
            var query = locationInput;
            // if(descInput) query += " " + descInput; // Use description only if location is vague? Let's stick to location field primarily

            if (query) {
                fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            setMarker(data[0].lat, data[0].lon, query);
                        }
                    });
            }
        }

        function locateUser() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var lat = position.coords.latitude;
                    var lon = position.coords.longitude;
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
                        .then(response => response.json())
                        .then(data => {
                            var displayName = data.display_name || lat + ", " + lon;
                            document.getElementById('location').value = displayName;
                            setMarker(lat, lon, displayName);
                        });
                }, function (error) {
                    showMessage("Could not get location. Permission denied?", "danger");
                });
            } else {
                showMessage("Geolocation not supported.", "danger");
            }
        }

        map.on('click', function (e) {
            var lat = e.latlng.lat;
            var lon = e.latlng.lng;
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
                .then(response => response.json())
                .then(data => {
                    var displayName = data.display_name;
                    document.getElementById('location').value = displayName;
                    setMarker(lat, lon, displayName);
                });
        });

        function setMarker(lat, lon, text) {
            if (marker) map.removeLayer(marker);
            map.setView([lat, lon], 15);
            marker = L.marker([lat, lon]).addTo(map).bindPopup(text).openPopup();
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('image-preview').innerHTML = '<img src="' + e.target.result + '" style="max-width: 100%; height: auto;">';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <!-- CAMERA MODAL -->
    <div id="camera-modal"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.9); z-index:9999; flex-direction:column; align-items:center; justify-content:center;">
        <div
            style="background:white; padding:10px; border-radius:8px; width:90%; max-width:600px; display:flex; flex-direction:column; align-items:center;">
            <h4>Take Photo</h4>
            <video id="camera-stream" autoplay playsinline
                style="width:100%; max-height:60vh; background:#000;"></video>
            <canvas id="camera-canvas" style="display:none;"></canvas>
            <div style="margin-top:15px; width:100%; display:flex; justify-content:space-around;">
                <button type="button" class="btn btn-secondary" onclick="closeCameraModal()">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="captureImage()">Capture</button>
            </div>
        </div>
    </div>

    <script>
        let videoStream = null;
        function openCameraModal() {
            document.getElementById('camera-modal').style.display = 'flex';
            navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
                .then(function (stream) {
                    videoStream = stream;
                    document.getElementById('camera-stream').srcObject = stream;
                })
                .catch(err => { alert("Camera access required."); closeCameraModal(); });
        }

        function closeCameraModal() {
            if (videoStream) {
                videoStream.getTracks().forEach(t => t.stop());
                videoStream = null;
            }
            document.getElementById('camera-modal').style.display = 'none';
        }

        function captureImage() {
            const video = document.getElementById('camera-stream');
            const canvas = document.getElementById('camera-canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);

            canvas.toBlob(function (blob) {
                const file = new File([blob], "capture_" + Date.now() + ".jpg", { type: "image/jpeg" });
                const container = new DataTransfer();
                container.items.add(file);
                document.getElementById('file').files = container.files;
                previewImage(document.getElementById('file'));
                closeCameraModal();
            }, 'image/jpeg');
        }
    </script>

</body>

</html>