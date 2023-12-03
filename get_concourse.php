<style>
    /* Remove underline from links */
    body a {
        text-decoration: none !important;
        color: black !important;
    }

    /* Remove underline from card title and card text */
    body .card-title,
    body .card-text {
        text-decoration: none !important;
        color: black !important;
    }
    h3{
        color: #c19f90;
        font-weight: bold;
    }
</style>
<?php

require('includes/dbconnection.php');

$itemsPerPage = 6;
if (isset($_GET['page'])) {
    $currentPage = (int)$_GET['page'];
} else {
    $currentPage = 1;
}

$offset = ($currentPage - 1) * $itemsPerPage;
$approvedConcoursesQuery = "SELECT * FROM concourse_verification ORDER BY concourse_id DESC LIMIT $itemsPerPage OFFSET $offset";
$approvedConcoursesResult = mysqli_query($con, $approvedConcoursesQuery);

if ($approvedConcoursesResult && mysqli_num_rows($approvedConcoursesResult) > 0) {
    while ($concourseData = mysqli_fetch_assoc($approvedConcoursesResult)) {
        // Generate the HTML for each concourse and echo it


        echo '<div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-4">';
        echo '<div class="card">';
        echo '<div class="image-container">';
        echo '<a href="concourse_view.php?concourse_id=' . $concourseData['concourse_id'] . '">';

        echo '<img src="./uploads/' . $concourseData['concourse_map'] . '" class="card-img-top" style="width:100%; height: 300px;" alt="Concourse Map">';
        echo '</div>';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . $concourseData['concourse_name'] . '</h5>';

        echo '<p class="card-text"><i class="fa-solid fa-location-dot"></i> ' . $concourseData['concourse_address'] . '</p>';
        // echo '<p class="card-text">Concourse ID: ' . $concourseData['concourse_id'] . '</p>';
        // echo '<p class="card-text">Concourse ID: ' . $concourseData['concourse_id'] . '</p>';
        // echo '<p class="card-text">Owner ID: ' . $concourseData['owner_id'] . '</p>';

        // echo  '<div class="bg-gray">';
        // echo  '<ul>';
        // echo    '<li><span>150</span> Sqft</li>';
        // echo  '<li><span>1</span> Beds</li>';
        // echo '<li><span>1</span> Baths</li>';
        // echo '<li><span>1</span> Kitchen</li>';
        // echo '<li><span>1</span> Balcony</li>';

        // echo '</ul>';
        // echo '</div>';
        echo '<p class="card-text">By: ' . $concourseData['owner_name'] . '</p>';
        // echo '<p class="card-text">: ' . $concourseData['owner_name'] . '</p>';
        echo '<p class="card-text"><i class="fa-solid fa-calendar-days"></i> ' . date('Y-m-d', strtotime($concourseData['created_at'])) . '</p>';
        echo '</a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo 'No approved concourses found.';
}
// Calculate the total number of pages

$totalItemsQuery = "SELECT COUNT(*) FROM concourse_verification WHERE status = 'approved'";
$totalItemsResult = mysqli_query($con, $totalItemsQuery);
$totalItems = mysqli_fetch_array($totalItemsResult)[0];
// $totalPages = ceil($totalItems / $itemsPerPage);
$totalPages = ceil($totalItems / $itemsPerPage);

// Generate the pagination links
$paginationHTML = '<ul class="pagination" id="pagination">';
for ($page = 1; $page <= $totalPages; $page++) {
    $activeClass = ($page == $currentPage) ? 'active' : '';
    $paginationHTML .= '<li class="page-item ' . $activeClass . '">';
    $paginationHTML .= '<a class="page-link" data-page="' . $page . '" href="#">' . $page . '</a>';
    $paginationHTML .= '</li>';
}
$paginationHTML .= '</ul>';

echo $paginationHTML;
