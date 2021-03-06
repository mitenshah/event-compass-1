<?php require_once("includes/base.php") ?>
<?php require_once("includes/functions.php") ?>
<?php require_once("includes/connection.php") ?>
<?php require_once("backend/ifnotlogin.php") ?>    
<?php
//This function will check whether the user is logged in or not. (Security function)

//session_start();
if (!(isset($_SESSION['currentuser']) && $_SESSION['currentuser'] != '')) 
{
    redirect_to("login.php");
}

?>

<?php
    if (isset($_GET['user'])) 
    {
        $username = $_GET['user'];
    }
?>
    <!-- Page Content -->
    <div class="container">

        <!-- Introduction Row -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Hi, <?php echo $username;?></h1>
                <p>The events may interest you.</p>
            </div>
        </div>
        <div id="wrapper">

        
<!-- ************************Starting here *********************************************** -->
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header">Recommended for you</h2>
            </div>

<?php
//$query="SELECT Ev_name, Ev_description,Sub_id FROM Event NATURAL JOIN Interested_in WHERE Count IN(SELECT MAX(Count) FROM Interested_in))";
//$query2="SELECT Ev_name, Ev_description,Sub_id FROM Event NATURAL JOIN Interested_in WHERE User_id=2 ORDER BY Count DESC";

$query2="SELECT User_id FROM User WHERE User_username='$username'";
$query_run2=mysql_query($query2);
$User_id=mysql_result($query_run2,0,'User_id');     
$query="SELECT * FROM Event e,Interested_in i where e.Sub_id=i.Sub_id AND i.User_id=$User_id ORDER BY Count DESC";
$query_run=mysql_query($query);
$mini=min((mysql_num_rows($query_run)),4);
         for ($i = 0; $i <$mini ; $i++)
        {
          
            $Ev_id = mysql_result($query_run, $i,'Ev_id');  
          $Ev_name=mysql_result($query_run, $i,'Ev_name');
          $Ev_description=mysql_result($query_run, $i,'Ev_description');
          $Sub_id=mysql_result($query_run, $i,'Sub_id');

   ?>     
        
            <div class="col-lg-3 col-sm-6 text-center">
                <a href="/event_page.php?user=<?php echo $username;?>&id=<?php echo $Ev_id;?>"><img class="img-responsive img-responsive img-center" src="img/.jpg" alt=""></a>
                <a href="/event_page.php?user=<?php echo $username;?>&id=<?php echo $Ev_id;?>"><h3><?php echo $Ev_name ?></h3></a>
                    <small><?php echo $Sub_id ?></small>
                <p><?php echo $Ev_description ?></p>
            </div>

            <?php } ?>

            
            </div>

<!--   ******************************************************************************************* -->


            <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header">Near You</h2>
            </div>


<?php



$query2="SELECT User_address FROM User WHERE User_username = '$username'";
$query_run2=mysql_query($query2);
$from=mysql_result($query_run2,0,'User_address');

$qr = "SELECT * FROM Event";
$qr2=mysql_query($qr);
$distance=100000000000000000000000;

for ($i = 0; $i < 2/*mysql_num_rows($qr2)*/; $i++) 
{
    $to=mysql_result($qr2,$i,'Ev_location');
    $from = urlencode($from);
    $to = urlencode($to);
    $data = file_get_contents("http://maps.googleapis.com/maps/api/distancematrix/json?origins=$from&destinations=$to&language=en-EN&sensor=false");
    $data = json_decode($data);
    $distance1 = 0;
    //$mini=min((mysql_num_rows($query_run)),4);
    //for ($i = 0; $i <$mini ; $i++)
    //{

      foreach($data->rows[0]->elements as $road)
      {
        $distance1 += $road->distance->value;
      }
      

      if($distance1<$distance)
      {
        $distance=$distance1;

      }
    //}

}

/*$from = urlencode($from);
$to = urlencode($to);
$data = file_get_contents("http://maps.googleapis.com/maps/api/distancematrix/json?origins=$from&destinations=$to&language=en-EN&sensor=false");
$data = json_decode($data);
$distance = 0;
$mini=min((mysql_num_rows($query_run)),4);
         for ($i = 0; $i <$mini ; $i++)
        {
      foreach($data->rows[0]->elements as $road) {
    $distance += $road->distance->value;
  }
}*/


echo "Distance: ".$distance." meters";

$query="SELECT * FROM Event where Ev_location ='$from' ";
$query_run=mysql_query($query);
$mini=min((mysql_num_rows($query_run)),4);
         for ($i = 0; $i <$mini ; $i++)
        {
          $Ev_id = mysql_result($query_run, $i,'Ev_id');
          $Ev_name=mysql_result($query_run, $i,'Ev_name');
          $Ev_description=mysql_result($query_run, $i,'Ev_description');
          $Sub_id=mysql_result($query_run, $i,'Sub_id');

   ?>     
        
            <div class="col-lg-3 col-sm-6 text-center">
                <a href="/event_page.php?user=<?php echo $username;?>&id=<?php echo $Ev_id;?>"><img class="img-responsive img-responsive img-center" src="img/.jpg" alt=""></a>
                <a href="/event_page.php?user=<?php echo $username;?>&id=<?php echo $Ev_id;?>"><h3><?php echo $Ev_name ?></h3></a>
                    <small><?php echo $Sub_id ?></small>
                <p><?php echo $Ev_description ?></p>
            </div>

            <?php } ?>

            </div>

<!--  **********************************************************************************8 -->

<div class="row">
            <div class="col-lg-12">
                <h2 class="page-header">Past Events</h2>
            </div>


<?php

$query2=mysql_query("SELECT Ev_id FROM Past_Events WHERE User_id = '$User_id'");
if(!$query2)
{
  echo mysql_error();

}
$numrows = mysql_num_rows($query2);

         for ($i = 0; $i <$numrows ; $i++)
        {
          $row = mysql_fetch_array($query2);
          $id = $row['Ev_id'];
          $q = mysql_query("SELECT * FROM Event WHERE Ev_id = $id");
          if(!$q)
          {
            echo mysql_error();
          }
          $row2 = mysql_fetch_array($q);
          $Ev_name = $row2['Ev_name'];
          $Ev_description = $row2['Ev_description'];
          $Sub_id = $row2['Sub_id'];
          /*$Ev_name=mysql_result($query_run, $i,'Ev_name');
          $Ev_description=mysql_result($query_run, $i,'Ev_description');
          $Sub_id=mysql_result($query_run, $i,'Sub_id');*/

   ?>     
        
            <div class="col-lg-3 col-sm-6 text-center">
                <a href="/event_page.php?user=<?php echo $username;?>&id=<?php echo $id;?>"><img class="img-responsive img-responsive img-center" src="img/.jpg" alt=""></a>
                <a href="/event_page.php?user=<?php echo $username;?>&id=<?php echo $id;?>"><h3><?php echo $Ev_name ?></h3></a>
                    <small><?php echo $Sub_id ?></small>
                <p><?php echo $Ev_description ?></p>
            </div>

            <?php } ?>

            </div>


       

<!--************************************************************************************************* -->

<div class="row">
            <div class="col-lg-12">
                <h2 class="page-header">Most Popular</h2>
            </div>

<?php
//$query="SELECT Ev_name, Ev_description,Sub_id FROM Event NATURAL JOIN Interested_in WHERE Count IN(SELECT MAX(Count) FROM Interested_in))";
//$query2="SELECT Ev_name, Ev_description,Sub_id FROM Event NATURAL JOIN Interested_in WHERE User_id=2 ORDER BY Count DESC";

$query2="SELECT User_id FROM User WHERE User_username='$username'";
$query_run2=mysql_query($query2);
$User_id=mysql_result($query_run2,0,'User_id');     
$query="SELECT * FROM Event ORDER BY Ev_page_count DESC";
$query_run=mysql_query($query);
$mini=min((mysql_num_rows($query_run)),4);
         for ($i = 0; $i <$mini ; $i++)
        {
          
          $Ev_id = mysql_result($query_run, $i,'Ev_id');
          $Ev_name=mysql_result($query_run, $i,'Ev_name');
          $Ev_description=mysql_result($query_run, $i,'Ev_description');
          $Sub_id=mysql_result($query_run, $i,'Sub_id');

   ?>     
        
            <div class="col-lg-3 col-sm-6 text-center">
                <a href="/event_page.php?user=<?php echo $username;?>&id=<?php echo $Ev_id;?>"><img class="img-responsive img-responsive img-center" src="img/.jpg" alt=""></a>
                <a href="/event_page.php?user=<?php echo $username;?>&id=<?php echo $Ev_id;?>"><h3><?php echo $Ev_name ?></h3></a>
                    <small><?php echo $Sub_id ?></small>
                <p><?php echo $Ev_description ?></p>
            </div>

            <?php } ?>

            
            </div>      
<!-- ********************************************************************************** -->      
            
            </div>
            </div>
        <hr>

     <?php require_once("includes/footer.php") ?>   

