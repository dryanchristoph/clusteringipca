<?php 
    $page = @$_GET['page'] ? $_GET['page'] : 1;
    $witel = @$_GET['witel'] ? $_GET['witel'] : FALSE;
    $cluster_id = @$_GET['cluster_id'] ? $_GET['cluster_id'] : FALSE;
    $cluster_name = @$_GET['cluster_name'] ? $_GET['cluster_name'] : FALSE;
    
    include 'connect.php';
    include 'polygon.func.php';
    
    if(!$witel && $cluster_name){
        $sql = "SELECT witel FROM tb_polygon WHERE cluster_id='$cluster_name'";
        $result = mysqli_query($conn,$sql);
        
        while ($row = $result->fetch_assoc()) {
            $witel = $row['witel'];
        }
    }
    
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Cek Clustering Polygon IPCA</title>
    
    <link rel="shortcut icon" href="./assets/images/logoTelkom_icon.png" />
    
    <link href="./assets/css/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="./assets/css/style.css" rel="stylesheet" type="text/css" />
    <link href="./assets/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="./assets/js/jQuery3.3.1.js" defer></script>
    <script type="text/javascript" src="./assets/js/popper.js" defer></script>
    <script type="text/javascript" src="./assets/js/bootstrap.js" defer></script>
    <script type="text/javascript" src="./assets/js/bootstrap-select.min.js" defer></script>
    <script type="text/javascript" src="./assets/js/custom.js" defer></script>
  </head>
  <body>
  <div class="content-wrapper">
  	<h2>Cek Clustering ODP dalam Polygon IPCA TR5</h2>
  	<br />
   	<form method="get" class="" action="">
      	<div class="row">
    		<div class="col col-sm-12 col-lg-5">
    			<div class="row">
          			<div class="col col-sm-4 inline-block">Witel</span></div>
            		<div class="col col-sm-8 inline-block">
                			<select name="witel" class="form-control selectpicker form-inline inline-block">
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
                                    <option value="SIDOARJO" <?= $witel=='SIDOARJO' ? 'selected' : ''; ?>>SIDOARJO</option>
                                    <option value="SURABAYA SELATAN" <?= $witel=='SURABAYA SELATAN' ? 'selected' : ''; ?>>SURABAYA SELATAN</option>
                                    <option value="SURABAYA UTARA" <?= $witel=='SURABAYA UTARA' ? 'selected' : ''; ?>>SURABAYA UTARA</option>
                              </select>
            		</div>
            		<?php /*<div class="inline-block txt-grey">Wajib diisi</div>*/ ?>
        		</div>
        		<div class="row">
        			<div class="col col-sm-4 inline-block">Cluster ID~Name</div>
        			<div class="col col-sm-8 inline-block">
        				<select class="form-control selectpicker" data-live-search="true" name="cluster_name">
        					<option value="">-- semua Cluster --
        					<?php 
        					    if($witel) $txt_where = ' WHERE witel = "'.$witel.'"';
        					    else $txt_where = '';
        					
        					    $sql = "SELECT * FROM tb_polygon".$txt_where;
            					$result = mysqli_query($conn,$sql);
            					
            					while ($row = $result->fetch_assoc()) {
            					   ?>
            					   <option value="<?= $row['cluster_id']; ?>"<?= $cluster_name == $row['cluster_id'] ? ' selected' : '';?>>
            					   <?php 
            					   echo $row['cluster_id'].'~'.$row['project_name'];
            					}
        					?>
        				</select>
        			</div>
        			<?php /*
        			<div class="inline-block txt-grey">
        				Opsional
        			</div>
        			*/ ?>
        		</div>
        		<br />
        		<div class="row">
            		<div class="col col-sm-12 inline-block text-right">
          				<button type="submit" class="btn btn-primary btn-sm inline-block">Submit</button>
          				<a href="./" class="btn btn-danger btn-sm inline-block">Clear Filter</a>
          			</div>
          		</div>
    		</div>
      	</div>
    </form>
    <hr />

<?php

    if($witel || $cluster_name){
        $sql = "SELECT count(*) as count FROM tb_polygon WHERE witel = '$witel'";
        $result = mysqli_query($conn,$sql);
        $count = $result->fetch_assoc()['count'];
        $limit = 50;
        $page_count = ceil($count/$limit);
        
        $data_from = (($page - 1) * $limit) + 1;
        $data_to = ($page == $page_count) ? $count : $limit * ($page); 
        
        if(!$cluster_id && !$cluster_name){
            $txt_cluster = 'Menampilkan Cluster ke '.$data_from.' - '.$data_to;
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
    <?php } else $txt_cluster = 'Menampilkan 1 Cluster'; ?>
    <span class="inline-block"><?= $txt_cluster.' dari total <b>'.$count.'</b>'; ?> Cluster</span>
    <div class="row">
    	<div class="col col-sm-12 col-lg-6">
    	<?php if($witel){ ?>
		<span class="waiting">Harap tunggu hingga halaman selesai dimuat. <img src="./assets/images/fancybox_loading.gif" /></span>
		<?php } ?>
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
    $txt_where = '';
    if($cluster_id) $txt_where .= ' AND cluster_id = "'.$cluster_id.'"';
    if($cluster_name) $txt_where .= ' AND cluster_id = "'.$cluster_name.'"';
    
    $limit_start = (($page - 1) * $limit);
    
    if($cluster_id || $cluster_name) $txt_limit = '';
    else $txt_limit = " LIMIT $limit_start,$limit";
    
    $sql = "SELECT * FROM tb_polygon WHERE witel = '$witel' $txt_where ORDER BY project_name".$txt_limit;
    #die($sql);
    $result = mysqli_query($conn,$sql);
    
    #$row = mysqli_fetch_array($result, MYSQLI_NUM);
    #echo 'Cluster ID    |    Cluster Name     |       ODP Name       <br>';
    $no = 0;
    
    while ($row = $result->fetch_assoc()) {
        $odp_found = FALSE;
        #@$result_array[] = $row;
        
        $arr_vertices = explode(',',$row['coordinate']);
        
        #$vertices_x = $vertices_y = array();
        $polygon = array();
        foreach($arr_vertices as $key=>$val){
            /* @$vertices_x[] = explode(' ',$val)[1];
            @$vertices_y[] = explode(' ',$val)[0]; */
            $polygon[] = new Point(explode(' ',$val)[0],explode(' ',$val)[1]);
            if(!@explode(' ',$val)[1]) echo $row['project_name'].'<br>'.$row['coordinate'];
        }
        
        $sql2 = "SELECT * FROM tb_odp WHERE WITEL = '$witel' ORDER BY ODP_NAME ASC";
        $result2 = mysqli_query($conn,$sql2);
        
        while($row2 = $result2->fetch_assoc()){
            #echo '<br>Cluster ID = '.$row2['cluster_id'].' Cluster Name = '.$row2['project_name'].'<br>';
            #echo '<pre>result2 = '; print_r($row2); echo '</pre>'; die;
            
            #echo '<pre>'; print_r($arr_vertices); echo '</pre>'; die;
            
            $latitude_y = $row2['LATITUDE'];
            $longitude_x = $row2['LONGITUDE'];
            
            if($row['cluster_id'] == $row2['CLUSTER_ID'] && $row2['CLUSTER_ID'] != null)
                $is_in_poly = TRUE;
            elseif($row2['CLUSTER_ID'] != null){
                $is_in_poly = FALSE;
            } else {
                $is_in_poly = pointInPolygon(new Point($latitude_y,$longitude_x), $polygon);
                if($is_in_poly){
                    $sql = 'UPDATE tb_odp SET CLUSTER_ID = "'.$row['cluster_id'].'", CLUSTER_NAME = "'.$row['project_name'].'" WHERE ODP_NAME = "'.$row2['ODP_NAME'].'"';
                    mysqli_query($conn, $sql);
                }
            }
            
            if($is_in_poly){
            #if(is_in_polygon($vertices_x, $vertices_y, $longitude_x, $latitude_y)){
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
        
        #if(!$odp_found) @$arr_no_odp[] = $row['cluster_id'].' | '.$row['project_name'];
        if(!$odp_found) @$arr_no_odp[] = $row; 
    }
    
    #echo '<pre>'; print_r($result_array); echo '</pre>'; die;
    ?>
    </tbody>
    </table>
    <?php if(@$arr_no_odp){
        $no = 1;
        ?>
        <br />
        <h4>Cluster di bawah ini tidak memiliki ODP dengan lokasi di dalam polygon : </h4>
        <br />
        <table class="table table-striped">
        	<thead>
        		<tr>
        			<th>No</th>
        			<th>Cluster ID</th>
        			<th>Nama Cluster</th>
        		</tr>
        	</thead>
        	<tbody>
        		<?php 
        		foreach($arr_no_odp as $key=>$val){
            		?>
            		<tr>
            			<td><?= @$no++; ?></td>
            			<td><?= $val['cluster_id']; ?></td>
            			<td><?= $val['project_name']; ?></td>
            		</tr>
        		<?php } ?>
        	</tbody>        	
        </table>
    <?php } ?>
    </div>
    </div>
    <?php 
    } else {
?>
	Untuk mulai menggunakan tools ini, silahkan pilih salah satu filter di atas. 
	<?php } ?>
</div>
</body>