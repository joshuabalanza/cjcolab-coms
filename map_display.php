<!-- ******************** -->
<!-- ***START SESSION**** -->
<!-- ******************** -->
<?php
   // space area(sqft) width& height
   // space rent bill
   // space windows
   // space Electrical outlets/ wall plug
   // lights

   session_name("user_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require('includes/dbconnection.php');
ob_start();

?>
<!-- ******************** -->
<!-- ***** PHP CODE ***** -->
<!-- ******************** -->
<?php
if (isset($_GET['concourse_id'])) {
    $concourse_id = $_GET['concourse_id'];

    // Query the database to fetch the detailed information for the selected concourse
    $concourseQuery = "SELECT * FROM concourse_verification WHERE concourse_id = $concourse_id";
    $concourseResult = mysqli_query($con, $concourseQuery);


} else {
    echo 'Concourse ID not provided.';
}
?>
<!-- ******************** -->
<!-- **** START HTML **** -->
<!-- ******************** -->
<?php
include('includes/header.php');
include('includes/nav.php');
?>
<style>
   #fp-canvas-container{
   height:100vh;
   background:#9b593c;
   width:calc(100%);
   position:relative;
   }
   .fp-img,.fp-canvas,.fp-canvas-2{
   position:absolute;
   /* width:calc(100%);
   height:calc(100%); */
   top:0;
   left:0;
   z-index: 1;
   }
   #fp-map{
   position:absolute;
   width:calc(100%);
   height:calc(100%);
   top:0;
   left:0;
   z-index: 1;
   }
   .fp-canvas {
   z-index: 2;
   background: #0000000d;
   cursor: crosshair;
   }
   #fp-map{
   z-index: 1;
   }
   area:hover {
   background: #0000004d;
   color: #fff !important;
   }
</style>
<section style="margin-top:80px;">
   <?php
   // if ($concourseResult && mysqli_num_rows($concourseResult) > 0) {
   //     $concourseData = mysqli_fetch_assoc($concourseResult);

   //     echo '<h3>Concourse Map</h3>';
   //     echo '<div style ="">';
   //     echo '<img src="/COMS/uploads/' . $concourseData['concourse_map'] . '" alt="Concourse Map">';
   //     echo '</div>';


   // } else {
   //     echo 'Concourse not found.';
   // }
?>
   <div class="card">
      <div class="card-header d-flex justify-content-between">
         <h3 class="card-title">Tables</h3>
      </div>
      <div class="card-body">
         <div class="col-md-12">
            <div class="row">

                <div class="col-2 text-right">
                    <button class="btn btn-primary rounded-0" id="draw"> Draw to Map Space</button>
                    <button class="btn btn-primary rounded-0 d-none" id="create_table"> Create Table</button>
                    <button class="btn btn-dark rounded-0 d-none" id="cancel"> Cancel</button>
                </div>
                <div class="col-2 text-right">
                    <button class="btn btn-primary rounded-0" id="space-list    "> Space List</button>
                </div>
            </div>
            <div id="fp-canvas-container">
               <?php
            if ($concourseResult && mysqli_num_rows($concourseResult) > 0) {
                $concourseData = mysqli_fetch_assoc($concourseResult);

                // echo '<h3>Concourse Map</h3>';
                // echo '<div style ="">';
                echo '<img src="/COMS/uploads/' . $concourseData['concourse_map'] . '" alt="Concourse Map" class="fp-img" id="fp-img" >';
            // echo '</div>';


            } else {
                echo 'Concourse not found.';
            }
?>
               <!-- <img src="./../uploads/floorplan.png" alt="Floor Plan" class='fp-img' id="fp-img" usemap="#fp-map"> -->
               <map name="fp-map" id="fp-map" class="">
               </map>
               <canvas class="fp-canvas d-none" id="fp-canvas"></canvas>
            </div>
         </div>
         <!-- <div class="col-md-4">
            <table class="table table-hover table-striped table-bordered">
                <colgroup>
                    <col width="5%">
                    <col width="75%">
                </colgroup>
                <thead>
                    <tr>
                        <th class="text-center p-0">#</th>
                        <th class="text-center p-0">Name</th>
                        <th class="text-center p-0">Action</th>
                    </tr>
                </thead>
                <tbody> -->
         <?php
            //                         $sql = "SELECT * FROM `table_list` order by tbl_no asc";
            // $qry = $conn->query($sql);
            // $tbl = array();
            // while($row = $qry->fetchArray()):
            //     $tbl[$row['table_id']] = array(
            //                                 "id" => $row['table_id'],
            //                                 "tbl_no" => $row['tbl_no'],
            //                                 "coordinates" => $row['coordinates'],
            //                                 "name" => $row['name']
            //                                     );
?>
         <!-- <tr>
            <td class="text-center p-0"><?php echo $row['tbl_no'] ?></td>
            <td class="py-0 px-1"><?php echo $row['name'] ?></td>
            <th class="text-center py-0 px-1">
                <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle btn-sm rounded-0 py-0" data-bs-toggle="dropdown" aria-expanded="false">
                    Action
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <li><a class="dropdown-item edit_data" data-id = '<?php echo $row['table_id'] ?>' href="javascript:void(0)">Edit</a></li>
                        <li><a class="dropdown-item delete_data" data-id = '<?php echo $row['table_id'] ?>' data-name = '<?php echo $row['tbl_no'] . " - " . $row['name'] ?>' href="javascript:void(0)">Delete</a></li>
                    </ul>
                </div>
            </th>
            </tr> -->
         <!--                     
            </tbody>
            </table>
            </div> -->
      </div>
   </div>
</section>
<?php include('includes/footer.php');?>