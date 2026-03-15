<?php
require_once '../controllerUserData.php';
$email = $_SESSION['email'];
$password = $_SESSION['password'];
if ($email != false && $password != false) {
    $sql = "SELECT * FROM usertable WHERE email = '$email'";
    $run_Sql = mysqli_query($con, $sql);
    if ($run_Sql) {
        $fetch_info = mysqli_fetch_assoc($run_Sql);
        $status = $fetch_info['status'];
        $code = $fetch_info['code'];
        if ($status == "verified") {
            if ($code != 0) {
                header('Location: ../reset-code.php');
            }
        } else {
            header('Location: ../user-otp.php');
        }
    }
} else {
    header('Location: ../login-user.php');
} ?>
<?php



error_reporting(0);

$msg = "";


if (isset($_POST['submit'])) {

    $name = mysqli_real_escape_string($con, $_POST['name']);
    $mobile = mysqli_real_escape_string($con, $_POST['mobile']);
    $checkbox1 = $_POST['wastetype'];
    $chk = "";
    foreach ($checkbox1 as $chk1) {
        $chk .= $chk1 . ",";
    }

    $email = mysqli_real_escape_string($con, $_POST['email']);
    $status = mysqli_real_escape_string($con, $_POST['status']);
    $location = mysqli_real_escape_string($con, $_POST['location']);
    $locationdescription = mysqli_real_escape_string($con, $_POST['locationdescription']);
    $date = $_POST['date'];

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
    $isImage = false;
    if (in_array($imageFileType, $extensions_arr)) {
        // Check if it's a real image
        $check = getimagesize($_FILES["file"]["tmp_name"]);
        if ($check !== false) {
            $isImage = true;
            // Upload file
            move_uploaded_file($image = $_FILES['file']['tmp_name'], $target_dir . $file);
        } else {
            $msg = '<div class = "alert alert-warning"><span class="fa fa-times"> Please upload a valid image of the wastage item!</span></div>';
        }
    } else {
        $msg = '<div class = "alert alert-warning"><span class="fa fa-times"> Invalid file! Only wastage item images (JPG, PNG) are allowed.</span></div>';
    }

    if ($isImage) {

        $sql = "insert into garbageinfo(name,mobile,email,wastetype,location,locationdescription,file,date,status)values('$name','$mobile','$email','$chk','$location','$locationdescription','$file','$date','$status')";

        if (mysqli_query($con, $sql)) {
            $msg = '<div class = "alert alert-success"><span class="fa fa-check"> Compain Registered Successfully!</span> <a href="../adminlogin/welcome.php" class="btn btn-primary btn-sm ml-2" style="background-color: #37517e; border-color: #37517e;">View Complain</a></div>';

        } else {
            $msg = '<div class = "alert alert-warning"><span class="fa fa-times"> Failed to Registered !"</span></div>';
        }
    }



    $html = "<table><tr><td>FirstName: $name</td></tr><tr><td>Mobile: $mobile</td></tr><tr><td>Email: $email</td></tr><tr><td>Type Of Waste: $chk</td></tr><tr><td>Area: $location</td></tr><tr><td>Area description: $locationdescription</td></tr><tr><td>Images: $file  </td></tr><tr><td>Date: $date</td></tr></table>";
    /* Email logic disabled for local testing to prevent crash
    include('PHPMailerAutoload.php');
    require_once('PHPMailerAutoload.php');
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure='tls';
    $mail->Host='smtp.gmail.com';
    $mail->Port= '587';
    $mail->isHTML(true);
    $mail->Username='janak.bista@sagarmatha.edu.np';
    $mail->Password='your email passsword';
    $mail->SetFrom('no-reply@howcode.org');     
    $mail->Subject='Hello sir!';
    $mail->Body=$html;     
    $mail->AddAddress('francis@howcode.org');
    $mail->SMTPOptions=array('ssl'=>array(
        'verify_peer'=>false,
        'verify_peer_name'=>false,
        'allow_self_signed'=>false
    ));
    $mail->send();
    */

}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <!-- TensorFlow.js + MobileNet for waste detection -->
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@4.10.0/dist/tf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/mobilenet@2.1.1/dist/mobilenet.min.js"></script>
    <title>Complain</title>
    <style>
        /* Sidebar and Layout Styles */
        body {
            margin: 0;
            font-family: "Lato", sans-serif;
        }

        .sidebar {
            margin: 0;
            padding: 0;
            width: 150px;
            background-color: #37517e;
            position: fixed;
            height: 100%;
            overflow: auto;
            z-index: 100;
            top: 0;
            left: 0;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 16px;
            text-decoration: none;
        }

        .sidebar a:hover {
            color: whitesmoke;
            text-decoration: none;
        }

        .logo1 {
            border-radius: 50%;
        }

        .main-content {
            margin-left: 150px;
            padding: 20px;
        }

        @media screen and (max-width: 700px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .sidebar a {
                float: left;
            }

            .main-content {
                margin-left: 0;
            }
        }

        @media screen and (max-width: 400px) {
            .sidebar a {
                text-align: center;
                float: none;
            }
        }
    </style>
</head>
</head>

<body>
    <div class="sidebar">
        <a href="../index.html" class="fa fa-home"><strong> Home - <img src="../assets/img/clients/Capture.PNG"
                    alt="LOGO" height='30' width='30' class='logo1'>
            </strong></a>
    </div>
    <div class="main-content">

        <?php
        $error = '';
        ?>
        <form method="post" action="trash.php" enctype="multipart/form-data">
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <div class="contact-info">
                            <img src="images.jfif" alt="image" />
                            <h2>Register Your Complain</h2>
                            <h4>We would love to hear from you !</h4>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="contact-form">
                            <div class="form-group">
                                <div id="error"></div>
                                <span style="color:red"><?php echo "<b>$msg</b>" ?></span>
                                <label class="control-label col-sm-2" for="fname"> Name:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" placeholder="Enter Your Name"
                                        name="name" value="<?php echo $fetch_info['name'] ?>" readonly required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="lname">Mobile:</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="mobile"
                                        placeholder="Enter Your Mobile Number" name="mobile" required min="80000000"
                                        max="100000000000">
                                </div>
                            </div>
                            <div class="form-group">
                                <!-- <label class="control-label col-sm-2" for="email">Email:</label>
                  <div class="col-sm-10"> -->
                                <input type="hidden" class="form-control" id="email" placeholder="Enter Your email"
                                    name="email" value="<?php echo $_SESSION['email']; ?>">
                                <!-- </div> -->
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="option">Category:</label>
                                <div class="col-sm-10">
                                    <input type="checkbox" name="wastetype[]" value="organic"> Organic
                                    <input type="checkbox" name="wastetype[]" value="inorganic"> Inorganic
                                    <input type="checkbox" name="wastetype[]" value="Household"> Household
                                    <input type="checkbox" name="wastetype[]" value="mixed" id="mycheck" checked> All
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="control-label col-sm-2" for="lname">Pictures:</label>
                                <div class="col-sm-10">
                                    <div style="display: flex; align-items: center;">
                                        <!-- Camera Logo Trigger -->
                                        <!-- Modified to open custom camera modal -->
                                        <div onclick="openCameraModal()" style="cursor: pointer; margin-right: 15px;"
                                            title="Tap to Open Live Camera">
                                            <i class="fa fa-camera" style="font-size: 40px; color: #37517e;"></i>
                                        </div>

                                        <!-- Visible file input -->
                                        <input type="file" class="form-control" id="file" name="file" required
                                            accept="image/*" capture="environment" onchange="previewImage(this)">
                                    </div>

                                    <div id="image-preview" style="margin-top: 10px;"></div>
                                    <!-- Waste Detection Result -->
                                    <div id="waste-scan-result" style="margin-top:10px;"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="lname">Location:</label>
                                <div class="col-sm-10">
                                    <div class="input-group shadow-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"
                                                style="background: #fff; border-right: none; color: #37517e;">
                                                <i class="fa fa-map-marker" style="font-size: 1.2rem;"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" id="location" name="location"
                                            placeholder="Detecting or Enter Location..." required onblur="updateMap()"
                                            style="border-left: none; border-right: none; height: 45px;">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary px-3" type="button" onclick="locateUser()"
                                                title="Detect Current Location"
                                                style="background-color: #37517e; border-color: #37517e; font-weight: 600;">
                                                <i class="fa fa-crosshairs"></i> Detect My Location
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- MAP CONTAINER -->
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <div id="map-message"></div>
                                    <div id="map"
                                        style="height: 300px; width: 100%; margin-top: 10px; border: 1px solid #ccc;">
                                    </div>
                                    <small class="form-text text-muted">You can also click on the map to set your
                                        location.</small>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="lname">Description:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" rows="5" id="locationdescription"
                                        placeholder="Enter Location details..." name="locationdescription" required
                                        onblur="updateMap()"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type='hidden' class="form-control" id="date" name="status" value="Approved">
                                    <input type="hidden" class="form-control" id="date" name="date" value="<?php $timezone = date_default_timezone_set("Asia/Kathmandu");
                                    echo date("g:ia ,\n l jS F Y"); ?>">
                                    <button type="submit" class="btn btn-default btn-submit" name="submit"
                                        id="submitBtn" disabled
                                        title="Upload a waste photo first to enable registration">Register</button>
                                    <small id="submit-hint" style="display:block;color:#888;margin-top:5px;"><i
                                            class="fa fa-info-circle"></i> Please upload a waste photo — it will be
                                        scanned automatically before you can register.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    </div>
    <script type="text/javascript" src="formValidation.js"></script>

    <!-- LEAFLET MAP SCRIPTS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([23.0225, 72.5714], 12); // Default to Ahmedabad

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var marker;

        function showMessage(msg, type) {
            var msgDiv = document.getElementById('map-message');
            msgDiv.innerHTML = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
                msg +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span></button></div>';
        }

        // 1. Search by Text
        function updateMap() {
            var locationInput = document.getElementById('location').value;
            var descInput = document.getElementById('locationdescription').value;

            var query = locationInput;
            if (descInput) {
                query += " " + descInput;
            }

            if (query) {
                fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            var lat = data[0].lat;
                            var lon = data[0].lon;
                            setMarker(lat, lon, query);
                            document.getElementById('map-message').innerHTML = ''; // Clear error if success
                        } else {
                            // Show warning with advice
                            showMessage("<strong>Location not found:</strong> '" + query + "'.<br>Please check the spelling (e.g. 'Ahmedabad' instead of 'Ahemdabad') or add more details like City, Country.", 'warning');
                        }
                    })
                    .catch(error => console.error('Error fetching location:', error));
            }
        }

        // Trigger search on Enter key
        document.getElementById('location').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Prevent form submission
                updateMap();
            }
        });

        document.getElementById('locationdescription').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Prevent form submission
                updateMap();
            }
        });

        // 2. Locate User (GPS)
        function locateUser() {
            autoDetectLocationAndFill();
        }

        function autoDetectLocationAndFill() {
            if (navigator.geolocation) {
                showLocationDetectingPrompt();
                navigator.geolocation.getCurrentPosition(function (position) {
                    var lat = position.coords.latitude;
                    var lon = position.coords.longitude;
                    // Reverse geocode
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&addressdetails=1`)
                        .then(response => response.json())
                        .then(data => {
                            var displayName = data.display_name || lat + ", " + lon;
                            var address = data.address || {};
                            var shortLocation = address.road || address.suburb || address.neighbourhood || address.city || address.town || address.village || "";
                            if (shortLocation && address.city && shortLocation !== address.city) {
                                shortLocation += ", " + address.city;
                            }

                            document.getElementById('location').value = shortLocation || displayName;
                            document.getElementById('locationdescription').value = displayName;

                            setMarker(lat, lon, displayName);
                            hideLocationDetectingPrompt();
                            showMessage('&#10003; Location detected and address fields filled.', 'success');
                        }).catch(function () {
                            hideLocationDetectingPrompt();
                            showMessage('Could not fetch address details. Please type manually.', 'warning');
                        });
                }, function (error) {
                    hideLocationDetectingPrompt();
                    // Friendly error handling
                    var msg = "Could not get your location automatically.";
                    if (error.code == error.PERMISSION_DENIED) {
                        msg = "<strong>Browser denied location access.</strong><br>To fix this: Click the 🔒 icon in your browser address bar and set Location to <strong>Allow</strong>.";
                    }
                    showMessage(msg, 'warning');
                });
            } else {
                showMessage("Geolocation is not supported by this browser. Please type your location.", 'danger');
            }
        }

        // 3. Click on Map to Set Location
        map.on('click', function (e) {
            var lat = e.latlng.lat;
            var lon = e.latlng.lng;

            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
                .then(response => response.json())
                .then(data => {
                    var displayName = data.display_name || lat + ", " + lon;
                    // Ensure we don't accidentally wipe out the description if they just wanted to refine the point
                    // But usually for "where is this", we update the main location field
                    document.getElementById('location').value = displayName;
                    setMarker(lat, lon, displayName);
                });
        });

        function setMarker(lat, lon, text) {
            if (marker) {
                map.removeLayer(marker);
            }
            map.setView([lat, lon], 15);
            marker = L.marker([lat, lon]).addTo(map)
                .bindPopup(text)
                .openPopup();
        }

        // ================================================================
        // WASTE DETECTION — Smart 3-List + Confidence Entropy AI
        // ================================================================

        // List A: EXPLICIT waste → always ALLOW (real ImageNet classes)
        var EXPLICIT_WASTE = [
            'dunghill', 'cesspool', 'muck', 'sandpile',
            'garbage truck', 'dustcart', 'trash can', 'garbage can',
            'dustbin', 'ashbin', 'wastebin', 'ashcan', 'dumpster',
            'trash', 'garbage', 'waste', 'rubbish', 'litter', 'debris', 'junk', 'dump',
            'refuse', 'sewage', 'landfill', 'compost heap', 'scrap',
            'rotten', 'rotting', 'discarded', 'contamination', 'pollution', 'rubble',
            'plastic bag', 'shopping bag', 'diaper', 'nappy',
            'syringe', 'needle', 'vial', 'biohazard', 'pill bottle',
            'medical waste', 'hospital waste', 'clinic waste',
            'hay', 'straw', 'mud', 'gravel', 'pile', 'heap', 'scraps',
            // Added from RealWaste categories
            'cardboard', 'carton', 'glass bottle', 'water bottle', 'can', 'tin', 'textile', 'rag', 'cloth',
            'paper', 'sheet', 'plastic', 'metal', 'organic', 'vegetation', 'food waste'
        ];

        // Specific RealWaste Dataset Categories for "Perfect Prediction"
        var REALWASTE_CATEGORIES = {
            'cardboard': 'Cardboard',
            'carton': 'Cardboard',
            'glass': 'Glass',
            'bottle': 'Glass/Plastic',
            'metal': 'Metal',
            'can': 'Metal',
            'tin': 'Metal',
            'paper': 'Paper',
            'plastic': 'Plastic',
            'textile': 'Textile Trash',
            'cloth': 'Textile Trash',
            'rag': 'Textile Trash',
            'organic': 'Food Organics',
            'food': 'Food Organics',
            'vegetation': 'Vegetation',
            'grass': 'Vegetation',
            'leaf': 'Vegetation',
            'trash': 'Miscellaneous Trash',
            'garbage': 'Miscellaneous Trash'
        };

        // List B: CLEAN objects → always BLOCK (clearly not waste)
        var CLEAN_BLOCK = [
            // Indoor room / selfie backgrounds
            'wardrobe', 'closet', 'china cabinet', 'entertainment center',
            'bookcase', 'library', 'studio couch', 'rocking chair',
            'couch', 'sofa', 'bed', 'pillow', 'curtain', 'chandelier',
            'table lamp', 'floor lamp', 'mirror', 'refrigerator',
            'washing machine', 'dishwasher', 'microwave', 'ceiling',
            // Tech / screens
            'monitor', 'computer', 'laptop', 'keyboard', 'screen',
            'television', 'desktop computer', 'notebook', 'cash machine',
            'comic book', 'book jacket',
            // Vehicles (not garbage)
            'sports car', 'convertible', 'limousine', 'jeep', 'minivan',
            'cab', 'taxi', 'ambulance', 'fire engine', 'school bus', 'motorcycle',
            // Fashion
            'suit', 'bow tie', 'necktie', 'dress', 'gown', 'lab coat',
            // Served food (clean plate)
            'plate', 'restaurant', 'hamburger', 'hotdog', 'ice cream', 'cake',
            // Instruments
            'guitar', 'piano', 'violin', 'drum', 'accordion',
            // Animals in nature (not in waste)
            'tabby', 'golden retriever', 'poodle', 'persian cat',
            // Sports
            'tennis ball', 'basketball', 'football', 'volleyball'
        ];

        // List C: FOOD / ORGANIC — use CONFIDENCE ENTROPY to decide
        // A clean fruit photo → MobileNet very confident (>45%) for one label
        // A garbage/waste heap → MobileNet confused, spreads score across many labels (<42%)
        var FOOD_ORGANIC = [
            'broccoli', 'cauliflower', 'head cabbage', 'eggplant', 'zucchini',
            'squash', 'artichoke', 'cucumber', 'pepper', 'bell pepper', 'carrot',
            'onion', 'tomato', 'potato', 'spinach', 'celery', 'asparagus',
            'leek', 'radish', 'turnip', 'corn', 'mushroom', 'garlic', 'cabbage',
            'lettuce', 'bean', 'beet',
            'banana', 'lemon', 'lime', 'orange', 'apple', 'strawberry', 'pineapple',
            'mango', 'watermelon', 'melon', 'grape', 'pear', 'peach', 'plum', 'fig',
            'pomegranate', 'coconut', 'cherry', 'berry', 'granny smith', 'clementine',
            'pot', 'flowerpot', 'leaf', 'leaves', 'soil', 'plant', 'vegetation',
            'flower', 'tree', 'grass', 'field', 'orchard', 'garden'
        ];

        var mobileNetModel = null;
        var wasteDetected = false;

        window.addEventListener('load', function () {
            mobilenet.load().then(function (model) {
                mobileNetModel = model;
                console.log('MobileNet ready.');
            }).catch(function (e) {
                console.warn('MobileNet failed to load:', e);
            });
            // Automatically detect location on page load
            locateUser();
            
            // Check if limit already reached on load
            if (sessionStorage.getItem('failedAttempts') >= 5) {
                enforceLimit();
            }
        });

        function enforceLimit() {
            var scanDiv = document.getElementById('waste-scan-result');
            var submitBtn = document.getElementById('submitBtn');
            var fileInput = document.getElementById('file');
            var cameraBtn = document.querySelector('[onclick="openCameraModal()"]');
            
            fileInput.disabled = true;
            if (cameraBtn) cameraBtn.style.pointerEvents = 'none';
            if (cameraBtn) cameraBtn.style.opacity = '0.5';
            submitBtn.disabled = true;
            
            scanDiv.innerHTML = '<div class="alert alert-danger"><strong><i class="fa fa-ban"></i> Access Denied:</strong><br>Maximum wrong photo upload limit (5) reached. You are no longer allowed to upload photos or register complaints in this session.</div>';
        }

        function previewImage(input) {
            var errorDiv = document.getElementById('error');
            var preview = document.getElementById('image-preview');
            var scanDiv = document.getElementById('waste-scan-result');
            var submitBtn = document.getElementById('submitBtn');

            preview.innerHTML = '';
            errorDiv.innerHTML = '';
            scanDiv.innerHTML = '';
            wasteDetected = false;
            submitBtn.disabled = true;
            document.getElementById('submit-hint').style.display = 'block';
            input.setCustomValidity('');

            if (input.files && input.files[0]) {
                var file = input.files[0];
                var validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/tiff'];
                if (!validTypes.includes(file.type)) {
                    errorDiv.innerHTML = '<div class="alert alert-danger"><strong>Invalid file!</strong><br>Only image files (JPG, PNG) are allowed.</div>';
                    input.value = '';
                    return;
                }
                var reader = new FileReader();
                reader.onload = function (e) {
                    preview.innerHTML =
                        '<img id="uploaded-img" src="' + e.target.result + '" ' +
                        'style="max-width:100%;height:auto;border:2px solid #ddd;padding:5px;border-radius:8px;" ' +
                        'crossorigin="anonymous">';
                    scanDiv.innerHTML =
                        '<div class="alert alert-info" id="scan-alert">' +
                        '<i class="fa fa-spinner fa-spin"></i>&nbsp; ' +
                        '<strong>AI scanning photo&hellip;</strong> Please wait.' +
                        '</div>';
                    setTimeout(function () { analyzeImageForWaste(); }, 500);
                };
                reader.readAsDataURL(file);
                autoDetectLocationAndFill();
            }
        }

        // ==============================================================
        // SMART WASTE DETECTION — 4-Rule Logic:
        //  Rule 1 — Explicit waste keywords   → ALLOW
        //  Rule 2 — Clean object detected     → BLOCK
        //  Rule 3 — Food/organic + LOW conf   → ALLOW (messy pile)
        //  Rule 4 — Food/organic + HIGH conf  → BLOCK (clean fruit)
        //  Rule 5 — Unknown                   → BLOCK (strict)
        // ==============================================================
        function analyzeImageForWaste() {
            var img = document.getElementById('uploaded-img');
            var scanDiv = document.getElementById('waste-scan-result');
            var submitBtn = document.getElementById('submitBtn');
            if (!img) { return; }

            if (!mobileNetModel) {
                scanDiv.innerHTML = '<div class="alert alert-warning"><i class="fa fa-spinner fa-spin"></i>&nbsp; Loading AI model&hellip; please wait.</div>';
                setTimeout(analyzeImageForWaste, 1500);
                return;
            }

            mobileNetModel.classify(img, 5).then(function (predictions) {

                // Build word list + full text from all 5 predictions
                var allWords = [];
                var fullText = '';
                predictions.forEach(function (p) {
                    var label = p.className.toLowerCase();
                    fullText += ' ' + label + ' ';
                    label.split(/[\s,]+/).forEach(function (w) {
                        if (w.length > 2) allWords.push(w);
                    });
                });

                var topLabel = predictions.length > 0 ? predictions[0].className : 'unknown';
                var topConf = predictions.length > 0 ? predictions[0].probability : 0;
                var topPct = Math.round(topConf * 100);

                // Helper — check if any keyword in a list matches
                function hits(list) {
                    return list.some(function (kw) {
                        var k = kw.toLowerCase();
                        if (k.indexOf(' ') !== -1) return fullText.indexOf(k) !== -1;
                        return allWords.some(function (w) {
                            return w === k || w.indexOf(k) !== -1 || k.indexOf(w) !== -1;
                        });
                    });
                }

                // Rule 0: Specific RealWaste Category Mapping
                var detectedCategory = null;
                for (var key in REALWASTE_CATEGORIES) {
                    if (fullText.indexOf(key) !== -1) {
                        detectedCategory = REALWASTE_CATEGORIES[key];
                        break;
                    }
                }

                // --- Rule 1: Explicit waste label or RealWaste Category → ALLOW ---
                if (detectedCategory || hits(EXPLICIT_WASTE)) {
                    var categoryMsg = detectedCategory ? " (Category: " + detectedCategory + ")" : "";
                    return allow('&#10003; Yes, waste image.',
                        'Perfect prediction: This photo contains waste/garbage.' + categoryMsg,
                        topLabel, topPct, scanDiv, submitBtn);
                }

                // --- Rule 2: Clearly clean/indoor object → BLOCK ---
                if (hits(CLEAN_BLOCK)) {
                    return block('&#10007; Error: Neat and clean environment detected.',
                        'The photo appears to show <em>' + topLabel + '</em>, which is not a waste/garbage item. Please upload a messy garbage photo.',
                        scanDiv, submitBtn);
                }

                // --- Rule 3 & 4: Food/organic items — decide by confidence ---
                if (hits(FOOD_ORGANIC)) {
                    if (topConf < 0.42) {
                        // LOW confidence = mixed messy scene  → WASTE PILE
                        return allow('&#10003; Yes, waste image.',
                            'Perfect prediction: Mixed organic waste detected.',
                            topLabel + ' (mixed)', topPct, scanDiv, submitBtn);
                    } else {
                        // HIGH confidence = single clean fruit/plant → NOT WASTE
                        return block('&#10007; Error: Clean item detected.',
                            'This appears to be a clean <em>' + topLabel + '</em>. Not a large amount of garbage.',
                            scanDiv, submitBtn);
                    }
                }

                // --- Rule 5: Completely unknown → BLOCK (strict) ---
                block('&#10007; Error: No waste detected.',
                    'The photo does not clearly show garbage (<em>' + topLabel + '</em>). Please upload a perfect garbage image.',
                    scanDiv, submitBtn);

            }).catch(function (err) {
                console.warn('AI error:', err);
                block('&#9888; AI scan failed.',
                    'Could not analyze photo. Please upload a clear waste/garbage photo.',
                    scanDiv, submitBtn);
            });
        }

        function allow(title, msg, label, pct, scanDiv, submitBtn) {
            wasteDetected = true;
            submitBtn.disabled = false;
            document.getElementById('submit-hint').style.display = 'none';
            scanDiv.innerHTML =
                '<div class="alert alert-success">' +
                '<i class="fa fa-check-circle"></i>&nbsp; ' +
                '<strong>' + title + '</strong> ' + msg +
                '<br><small>AI detected: <em>' + label + '</em> (' + pct + '% confidence)</small>' +
                '</div>';
        }

        function block(title, msg, scanDiv, submitBtn) {
            wasteDetected = false;
            submitBtn.disabled = true;
            
            // Increment failed attempts
            let attempts = parseInt(sessionStorage.getItem('failedAttempts') || 0);
            attempts++;
            sessionStorage.setItem('failedAttempts', attempts);
            
            if (attempts >= 5) {
                enforceLimit();
                return;
            }

            document.getElementById('submit-hint').style.display = 'block';
            scanDiv.innerHTML =
                '<div class="alert alert-danger">' +
                '<i class="fa fa-times-circle"></i>&nbsp; ' +
                '<strong>' + title + '</strong><br>' + msg + '<br>' +
                'Attempt ' + attempts + ' of 5: Please upload a <strong>clear photo of the garbage/waste area</strong>.' +
                '</div>';
        }


        // ==============================================================
        // AUTO LOCATION DETECTION (Implemented above)
        // ==============================================================
        function showLocationDetectingPrompt() {
            document.getElementById('map-message').innerHTML =
                '<div class="alert alert-info" id="location-detecting-alert">' +
                '<i class="fa fa-spinner fa-spin"></i>&nbsp; <strong>Detecting your location&hellip;</strong>' +
                ' Click <strong>Allow</strong> if browser asks for location permission.' +
                '</div>';
        }
        function hideLocationDetectingPrompt() {
            var el = document.getElementById('location-detecting-alert');
            if (el) el.parentNode.removeChild(el);
        }
    </script>

    <!-- CAMERA MODAL AND LOGIC -->
    <div id="camera-modal"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:9999; flex-direction:column; align-items:center; justify-content:center;">
        <div
            style="background:white; padding:10px; border-radius:8px; width:90%; max-width:600px; display:flex; flex-direction:column; align-items:center;">
            <h4 style="color:#333;">Identify Waste</h4>
            <video id="camera-stream" autoplay playsinline
                style="width:100%; border-radius:4px; max-height:60vh; background:#000;"></video>
            <canvas id="camera-canvas" style="display:none;"></canvas>
            <div style="margin-top:15px; width:100%; display:flex; justify-content:space-around;">
                <button type="button" class="btn btn-secondary" onclick="closeCameraModal()">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="captureImage()"><i class="fa fa-camera"></i>
                    Capture</button>
            </div>
        </div>
    </div>

    <script>
        let videoStream = null;

        function openCameraModal() {
            const modal = document.getElementById('camera-modal');
            const video = document.getElementById('camera-stream');

            // Show modal
            modal.style.display = 'flex';

            // Request camera access
            navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
                .then(function (stream) {
                    videoStream = stream;
                    video.srcObject = stream;
                    video.play();
                })
                .catch(function (err) {
                    console.error("Error accessing camera: ", err);
                    alert("Could not access camera. Please ensure you have allowed camera permissions. Fallback to file upload.");
                    closeCameraModal();
                    document.getElementById('file').click(); // Fallback to standard picker
                });
        }

        function closeCameraModal() {
            const modal = document.getElementById('camera-modal');
            const video = document.getElementById('camera-stream');

            // Stop all tracks
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
                videoStream = null;
            }
            video.srcObject = null;
            modal.style.display = 'none';
        }

        function captureImage() {
            const video = document.getElementById('camera-stream');
            const canvas = document.getElementById('camera-canvas');
            const context = canvas.getContext('2d');

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            canvas.toBlob(function (blob) {
                const file = new File([blob], 'capture_' + new Date().getTime() + '.jpg', { type: 'image/jpeg' });

                const container = new DataTransfer();
                container.items.add(file);
                const fileInput = document.getElementById('file');
                fileInput.files = container.files;

                // Close modal, then show preview + auto-detect location
                closeCameraModal();
                previewImage(fileInput);

            }, 'image/jpeg', 0.8);
        }
    </script>

</body>

</html>