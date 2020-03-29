<option value="">-- semua Cluster --
<?php
include 'connect.php';
$witel = $_POST['witel'];

$txt_where = '';
if($witel) $txt_where = 'WHERE witel = "'.$witel.'"'; 
$sql = "SELECT * FROM tb_polygon $txt_where";
$result = mysqli_query($conn,$sql);

$return = array();
while ($row = $result->fetch_assoc()) {
    #$return[] = array('cluster_id'=>$row['cluster_id'],'cluster_name'=>$row['project_name']);
    ?>
    <option value="<?= $row['cluster_id']; ?>">
    <?php 
    echo $row['cluster_id'].'~'.$row['project_name'];
}

#echo json_encode($return);