<?php
session_name("user_session");
session_start();
require('includes/dbconnection.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Spaces</title>
    <style>
        /* Add any additional styles here if needed */
        .tooltip {
            position: absolute;
            background: rgba(255, 255, 255, 0.8);
            padding: 5px;
            border: 1px solid #000;
            border-radius: 5px;
            z-index: 3;
            display: none;
        }
    </style>
</head>

<body>
    <?php
    $concourse_id = $_GET['concourse_id'];

    // Retrieve concourse information
    $concourseQuery = "SELECT * FROM concourse_verification WHERE concourse_id = $concourse_id";
    $concourseResult = mysqli_query($con, $concourseQuery);

    if ($concourseResult && mysqli_num_rows($concourseResult) > 0) {
        $concourseData = mysqli_fetch_assoc($concourseResult);

        // Retrieve spaces information
        $spacesQuery = "SELECT * FROM space WHERE concourse_id = $concourse_id";
        $spacesResult = mysqli_query($con, $spacesQuery);
    ?>
<h2><?php echo $concourseData['concourse_name']; ?> Spaces</h2>
<img src="/COMS/uploads/<?php echo $concourseData['concourse_map']; ?>" alt="Concourse Map" usemap="#fp-map">
<map name="fp-map" id="fp-map">
    <?php
while ($spaceData = mysqli_fetch_assoc($spacesResult)) {
    // Extract coordinates
    $coordinates = explode(',', $spaceData['coordinates']);
    // Debugging: Echo coordinates
    echo "Coordinates for {$spaceData['space_name']}: " . implode(',', $coordinates) . "<br>";
}

    ?>
</map>
        <div id="space-info" class="tooltip"></div>

        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(function () {
        // Define the actual image dimensions
        var actualImageWidth = 1000; // replace with the actual width
        var actualImageHeight = 800; // replace with the actual height

        $('.space-area').mouseover(function () {
            var spaceInfo = $(this).attr('title');
            var coordinates = $(this).attr('coords').split(',');

            // Scale coordinates based on the actual image size
            var scaleX = actualImageWidth / $('#fp-map').width();
            var scaleY = actualImageHeight / $('#fp-map').height();

            // Adjust coordinates to match the actual image size
            var x = (parseInt(coordinates[0]) / scaleX + parseInt(coordinates[2]) / scaleX) / 2;
            var y = (parseInt(coordinates[1]) / scaleY + parseInt(coordinates[3]) / scaleY) / 2;

            $('#space-info').html(spaceInfo);
            $('#space-info').css({
                'left': x + 'px',
                'top': y + 'px'
            });
            $('#space-info').show();
        });

        $('.space-area').mouseout(function () {
            $('#space-info').hide();
        });
    });
</script>


    <?php
    } else {
        echo 'Concourse not found.';
    }
    ?>
</body>

</html>
