<?php
session_name("user_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require('includes/dbconnection.php');
ob_start();
?>

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

<?php
include('includes/header.php');
include('includes/nav.php');
?>



<style>
    #fp-canvas-container{
        height:50vh;
        width:calc(100%);
        position:relative;
    }
    .fp-img,.fp-canvas,.fp-canvas-2{
        position:absolute;
        width:calc(100%);
        height:calc(100%);
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
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Tables</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-12 text-right">
                        <button class="btn btn-primary rounded-0" id="draw"> Draw to Map Table</button>
                        <button class="btn btn-primary rounded-0 d-none" id="create_table"> Create Table</button>
                        <button class="btn btn-dark rounded-0 d-none" id="cancel"> Cancel</button>
                    </div>
                </div>
                <div id="fp-canvas-container">
                    <img src="./../uploads/floorplan.png" alt="Floor Plan" class='fp-img' id="fp-img" usemap="#fp-map">
                    <map name="fp-map" id="fp-map" class="">
                    </map>
                    <canvas class="fp-canvas d-none" id="fp-canvas"></canvas>
                </div>
            </div>
            <div class="col-md-4">
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
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM `space_list` order by tbl_no asc";
$qry = $con->query($sql);
$tbl = array();
while($row = $qry->fetchArray()):
    $tbl[$row['table_id']] = array(
                                "id" => $row['table_id'],
                                "tbl_no" => $row['tbl_no'],
                                "coordinates" => $row['coordinates'],
                                "name" => $row['name']
                                    );
    ?>
                        <tr>
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
                        </tr>
                        <?php endwhile; ?>
                    
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>
<script>
    var px1_perc=0,py1_perc=0,px2_perc=0,py2_perc=0;
    var cposX =0,cposY = 0;
    var posX =0,posY = 0;
    var nposX =0,nposY = 0;
    var ctx;
    var isDraw = false;
    var tbl = $.parseJSON('<?php echo json_encode($tbl) ?>')
    function map_tbls(){
        if(Object.keys(tbl).length > 0){
            $('#fp-map').html('')

            Object.keys(tbl).map(k=>{
                var data = tbl[k]
                var area = $("<area shape='rect'>")
                    area.attr('href',"javascript:void(0)")
                var perc = data.coordinates
                perc = perc.replace(" ",'')
                perc = perc.split(",")
                var x = $('#fp-img').width() * perc[0];
                var y = $('#fp-img').height() * perc[1];
                var width = ($('#fp-img').width() * perc[2]) - x;
                var height = ($('#fp-img').height() * perc[3]) - y;
                area.attr('coords',x+", "+y+", "+width+", "+height)
                area.text("#"+data.tbl_no)
                area.addClass('fw-bolder text-muted')
                area.css({
                    'position':'absolute',
                    // 'border':"1px solid blue",
                    'height':height+'px',
                    'width':width+'px',
                    'top':y+'px',
                    'left':x+'px',
                    'display':'flex',
                    'text-align':'center',
                    'justify-content':'center',
                    'align-items':'center',
                })
                $('#fp-map').append(area)
                area.click(function(){
                    uni_modal('Table Details',"view_table.php?id="+data.id)
                })
            })
        }
    }
    $(function(){
        cposX = $('#fp-canvas')[0].getBoundingClientRect().x
        cposY = $('#fp-canvas')[0].getBoundingClientRect().y
        ctx = $('#fp-canvas')[0].getContext('2d');
        map_tbls()
        $(window).on('resize',function(){
            map_tbls()
        })
        $('.edit_data').click(function(){
            uni_modal('Edit Table Details',"manage_table.php?id="+$(this).attr('data-id'))
        })
        $('.delete_data').click(function(){
            _conf("Are you sure to delete <b>"+$(this).attr('data-name')+"</b> from list?",'delete_data',[$(this).attr('data-id')])
        })
        $('table td,table th').addClass('align-middle')
        $('table').dataTable({
            columnDefs: [
                { orderable: false, targets:2 }
            ]
        })

        $('.fp-canvas').on('mousedown',function(e){
                px1_perc = (e.clientX - cposX)/$('#fp-canvas').width()
                py1_perc = (e.clientY - cposY)/$('#fp-canvas').height()
                posX = $('#fp-canvas')[0].width * ((e.clientX - cposX)/$('#fp-canvas').width());
                posY = $('#fp-canvas')[0].height * ((e.clientY - cposY)/$('#fp-canvas').height());
                isDraw = true
        })
        $('.fp-canvas').on('mousemove',function(e){
            if(isDraw == false)
            return false;
            nposX = $('#fp-canvas')[0].width * ((e.clientX - cposX)/$('#fp-canvas').width());
            nposY = $('#fp-canvas')[0].height *((e.clientY - cposY)/$('#fp-canvas').height());
            var height = nposY - posY;
            var width = nposX - posX;
            ctx.clearRect(0, 0,  $('.fp-canvas')[0].width, $('.fp-canvas')[0].height);
            ctx.beginPath();
            ctx.lineWidth = "1";
            ctx.strokeStyle = "red";
            ctx.rect(posX, posY, width, height);
            ctx.stroke();
        })
        $('.fp-canvas').on('mouseup',function(e){
            px2_perc = (e.clientX - cposX)/$('#fp-canvas').width()
            py2_perc = (e.clientY - cposY)/$('#fp-canvas').height()
            nposX = $('#fp-canvas')[0].width * ((e.clientX - cposX)/$('#fp-canvas').width());
            nposY = $('#fp-canvas')[0].height *((e.clientY - cposY)/$('#fp-canvas').height());
            var height = nposY - posY;
            var width = nposX - posX;
            
            ctx.clearRect(0, 0,  $('.fp-canvas')[0].width, $('.fp-canvas')[0].height);
            ctx.beginPath();
            ctx.lineWidth = "1";
            ctx.strokeStyle = "red";
            ctx.rect(posX, posY, width, height);
            ctx.stroke();
            isDraw = false
        })

        $('#draw').click(function(){
            $(this).hide('slow')
            $('#create_table,#cancel,#fp-canvas').removeClass('d-none')
            // $('#fp-map').addClass('d-none')
            cposX = $('#fp-canvas')[0].getBoundingClientRect().x
            cposY = $('#fp-canvas')[0].getBoundingClientRect().y
            ctx = $('#fp-canvas')[0].getContext('2d');
        })
        $('#cancel').click(function(){
            $(this).addClass('d-none')
            $('#create_table,#fp-canvas').addClass('d-none')
            $('#draw').show('slow')
            // $('#fp-map').removeClass('d-none')
            ctx.clearRect(0, 0,  $('.fp-canvas')[0].width, $('.fp-canvas')[0].height);

        })
        $('#create_table').click(function(){
            uni_modal("Map Table","manage_table.php?x="+px1_perc+"&y="+py1_perc+"&w="+px2_perc+"&h="+py2_perc)
        })

    })
    function delete_data($id){
        $('#confirm_modal button').attr('disabled',true)
        $.ajax({
            url:'./../Actions.php?a=delete_table',
            method:'POST',
            data:{id:$id},
            dataType:'JSON',
            error:err=>{
                console.log(err)
                alert("An error occurred.")
                $('#confirm_modal button').attr('disabled',false)
            },
            success:function(resp){
                if(resp.status == 'success'){
                    location.reload()
                }else{
                    alert("An error occurred.")
                    $('#confirm_modal button').attr('disabled',false)
                }
            }
        })
    }
</script>
<?php include('includes/footer.php'); ?>
