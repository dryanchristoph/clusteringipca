<?php 
    $page = @$_GET['page'] ? $_GET['page'] : 1;
    $witel = @$_GET['witel'] ? $_GET['witel'] : FALSE;
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Cek Clustering Polygon IPCA</title>
    <link href="./assets/css/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="./assets/css/style.css" rel="stylesheet" type="text/css" />
  </head>
  <body>
  <div class="content-wrapper">
  	<h2>Cek Clustering ODP dalam Polygon IPCA TR5</h2>
  	<br />
   	<form method="get" class="" action="">
      	<div class="row">
    		<div class="col col-sm-4">
      			<div class="col col-sm-2 inline-block">Witel</div>
        		<div class="col col-sm-6 inline-block">
            			<select name="witel" class="form-control form-inline inline-block">
            					<option value="">--- pilih Witel ---
            					<option value="DENPASAR" <?= $witel=='DENPASAR' ? 'selected' : ''; ?>>DENPASAR</option>
                                <option value="JEMBER" <?= $witel=='JEMBER' ? 'selected' : ''; ?>>JEMBER</option>
                                <option value="KEDIRI" <?= $witel=='KEDIRI' ? 'selected' : ''; ?>>KEDIRI</option>
                                <option value="MADIUN" <?= $witel=='MADIUN' ? 'selected' : ''; ?>>MADIUN</option>
                                <option value="MADURA" <?= $witel=='MADURA' ? 'selected' : ''; ?>>MADURA</option>
                                <option value="MALANG" <?= $witel=='MALANG' ? 'selected' : ''; ?>>MALANG</option>
                                <option value="NTB" <?= $witel=='NTB' ? 'selected' : ''; ?>>NTB</option>
                                <option value="NTT" <?= $witel=='NTT' ? 'selected' : ''; ?>>NTT</option>
                                <option value="PASURUAN" <?= $witel=='PASURUAN' ? 'selected' : ''; ?>>PASURUAN</option>
                                <option value="SINGARAJA" <?= $witel=='SINGARAJA' ? 'selected' : ''; ?>>SINGARAJA</option>
                                <option value="SURABAYA SELATAN" <?= $witel=='SURABAYA SELATAN' ? 'selected' : ''; ?>>SURABAYA SELATAN</option>
                                <option value="SURABAYA UTARA" <?= $witel=='SURABAYA UTARA' ? 'selected' : ''; ?>>SURABAYA UTARA</option>
                          </select>
        		</div>
        		<div class="col col-sm-3 inline-block">
      				<button type="submit" class="btn btn-primary inline-block">Submit</button>
      			</div>
    		</div>
      	</div>
    </form>
    <hr />

<?php

    include 'connect.php';
    include 'polygon.func.php';

    if($witel){
        $sql = "SELECT count(*) as count FROM tb_polygon WHERE witel = '$witel'";
        $result = mysqli_query($conn,$sql);
        $count = $result->fetch_assoc()['count'];
        $limit = 50;
        $page_count = ceil($count/$limit);
        ?>
    <nav aria-label="Page navigation example" class="inline-block">
      <ul class="pagination">
      	<?php
          	if($page != 1){ ?>
            <li class="page-item"><a class="page-link" href="<?= '?witel='.$witel.'&page='.($page-1); ?>">Previous</a></li>
            <?php }
            for ($x = 1; $x <= $page_count; $x++){ ?> 
                <li class="page-item<?= ($x == ($page)) ? ' active' : ''; ?>"><a class="page-link" href="<?= '?witel='.$witel.'&page='.$x; ?>"><?= $x; ?></a></li>
        <?php } 
            if($page < $page_count){ ?>
            <li class="page-item"><a class="page-link" href="<?= '?witel='.$witel.'&page='.($page+1); ?>">Next</a></li>
            <?php } ?>
      </ul>
    </nav>
    <span class="inline-block">Total <?= $count; ?> Cluster</span>
    <div class="row">
    	<div class="col col-sm-6">
    <table class="table table-striped">
    	<thead>
    		<tr>
    			<th>No</th>
    			<th>Cluster ID</th>
    			<th>Cluster Name</th>
    			<th>ODP Name</th>
    		</tr>
    	</thead>
    	<tbody>
<?php

    
    $sql = "SELECT * FROM tb_polygon WHERE witel = '$witel' LIMIT $page,$limit";
    $result = mysqli_query($conn,$sql);
    
    #$row = mysqli_fetch_array($result, MYSQLI_NUM);
    #echo 'Cluster ID    |    Cluster Name     |       ODP Name       <br>';
    $no = 0;
    
    while ($row = $result->fetch_assoc()) {
        $odp_found = FALSE;
        #@$result_array[] = $row;
        
        $arr_vertices = explode(',',$row['coordinate']);
        
        $vertices_x = $vertices_y = array();
        foreach($arr_vertices as $key=>$val){
            @$vertices_x[] = explode(' ',$val)[1];
            @$vertices_y[] = explode(' ',$val)[0];
        }
        
        $sql2 = "SELECT * FROM tb_odp WHERE WITEL = 'SINGARAJA'";
        $result2 = mysqli_query($conn,$sql2);
        
        while($row2 = $result2->fetch_assoc()){
            #echo '<br>Cluster ID = '.$row2['cluster_id'].' Cluster Name = '.$row2['project_name'].'<br>';
            #echo '<pre>result2 = '; print_r($row2); echo '</pre>'; die;
            
            #echo '<pre>'; print_r($arr_vertices); echo '</pre>'; die;
            
            $latitude_y = $row2['LATITUDE'];
            $longitude_x = $row2['LONGITUDE'];
            
            if(is_in_polygon($vertices_x, $vertices_y, $longitude_x, $latitude_y)){
                $no++;
                #echo implode(',',$vertices_x);
                ?>
                	<tr>
                		<td><?= $no; ?></td>
                		<td><?= $row['cluster_id']; ?></td>
                		<td><?= $row['project_name']; ?></td>
                		<td><?= $row2['ODP_NAME']; ?></td>
                	</tr>
                <?php 
                #echo '<br>'.$row['cluster_id'].' | '.$row['project_name'].' | '.$row2['ODP_NAME'];
                $odp_found = TRUE;
            }
        }
        
        if(!$odp_found) @$arr_no_odp[] = $row['cluster_id'].' | '.$row['project_name']; 
    }
    
    #echo '<pre>'; print_r($result_array); echo '</pre>'; die;
    ?>
    </tbody>
    </table>
    </div>
    </div>
    <?php 
    } else {
?>
	Untuk mulai menggunakan tools ini, silahkan pilih Witel pada pilihan di atas. 
	<?php } ?>
</div>
</body>