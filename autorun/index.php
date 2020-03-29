<?php
include '../connect.php';
include '../polygon.func.php';

$witel = $_GET['witel'];

$sql = "SELECT * FROM tb_polygon WHERE witel = '$witel'";
#die($sql);
$result = mysqli_query($conn,$sql);

$no = 0;
while ($row = $result->fetch_assoc()) {
    $sql2 = "SELECT * FROM tb_odp WHERE WITEL = '$witel' ORDER BY ODP_NAME ASC";
    $result2 = mysqli_query($conn,$sql2);
    
    $arr_vertices = explode(',',$row['coordinate']);
    
    $polygon = array();
    foreach($arr_vertices as $key=>$val){
        $polygon[] = new Point(explode(' ',$val)[0],explode(' ',$val)[1]);
    }
    
    $txt_sql = '';
    $count_odp = 0;
    while($row2 = $result2->fetch_assoc()){
        $latitude_y = $row2['LATITUDE'];
        $longitude_x = $row2['LONGITUDE'];
        
        $is_in_poly = pointInPolygon(new Point($latitude_y,$longitude_x), $polygon);
        
        if($is_in_poly){
            if($count_odp != 0) $txt_sql .= ',';
            $txt_sql .= '("'.$witel.'","'.$row['cluster_id'].'","'.$row['project_name'].'","'.$row2['ODP_NAME'].'")';
            $count_odp++;
        }
    }
    if($count_odp){
        $sql = 'INSERT IGNORE INTO tb_results (witel,cluster_id,cluster_name,odp_name) VALUES '.$txt_sql;
        #die($sql);
        mysqli_query($conn, $sql);
    }
    
    echo ++$no.'. Cluster '.$row['cluster_id'].'~'.$row['project_name'].' : '.$count_odp.' ODP Found.<br />';
    flush();
    ob_flush();
}