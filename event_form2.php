<?php
    ob_start();
?>
<?php require_once("includes/base.php") ?>
<?php require_once("includes/functions.php") ?>
<?php require_once("includes/connection.php") ?>
<?php
    $username = $_SESSION['currentuser'];
?>
<?php
    if(isset($_GET['id']))
    {
        $id = $_GET['id'];
    }
    if (isset($_POST['submit']))
    {
        //echo $id;
        $uploadDir = 'img/';
        //chmod("img/",0600);
        $image_name = $_FILES['image']['name'];
        $image_type = $_FILES['image']['type'];
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];   
        $ext = pathinfo($image_name, PATHINFO_EXTENSION);
        //echo $ext;
        //echo type($ext);
        echo $image_name;
        echo $image_tmp_name;
        if($ext == "jpg")
        {
            $filePath = $uploadDir . $id . '.jpg';
        }
        if($ext == "png")
        {
            $filePath = $uploadDir . $id . '.png';
        }
        if($ext == "jpeg")
        {
            $filePath = $uploadDir . $id . '.jpeg';
        }
        
        $result = move_uploaded_file($image_tmp_name, $filePath);
        if (!$result) 
        {
            echo "Error uploading file";
            exit;
        }
        
        $query = mysql_query("UPDATE Event SET Ev_photo = '$filePath' WHERE Ev_id = '$id'"); 
        if(!$query)
        {
            echo mysql_error();
        }
        redirect_to("event_form3.php?user=$username&id=$id");
    }
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header text-center">EVENT FORM</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <h3>Upload Cover Picture for your event</h3>
                </div>
                <div class="panel-body">
                    <div class="row col-md-offset-1">
                        <div class="col-lg-12">
                            <form enctype="multipart/form-data" role="form" method="post" action="event_form2.php?user=<?php echo $username;?>&id=<?php echo $id; ?>">
                                <div class="form-group">
                                    <label><h3>Cover Photo</h3></label>
                                    <input type="file" name="image">          
                                    <br>
                                    <button type="submit" class="btn btn-" name="submit" value="submit">Proceed to next step</button>
                                    <button type="submit" class="btn btn-" name="submit" value="submit1">Skip</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.col-lg-12 (nested) -->
                    </div>
                    <!-- /.row (nested) -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->

<!-- jQuery -->
<script src="js/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="metisMenu.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="sb-admin-2.js"></script>

</body>

<?php require_once("includes/footer.php") ?>

</html>
