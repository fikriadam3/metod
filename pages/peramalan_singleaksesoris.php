<?php 

	$skor_aksesoris = 0;
	$skor_ses = 0;
	
	$data = array();
	$rml_aksesoris = array();
	$hsl_mae_aksesoris = array();
	$hsl_mape_aksesoris = array();
	$hsl_msd_aksesoris = array();
	$stat_aksesoris = array();
	$kes_aksesoris;
	$kes_ses;

	$data_ses = array();
	$rml_ses = array();
	$hsl_mae_ses = array();
	$hsl_mape_ses = array();
	$hsl_msd_ses = array();
	$stat_ses = array();
	$baris = 11;
	
	

	for($i=10; $i>=0; $i--) {
		if($prd_ramalan=="per_hari") {
			$query_tjl = "SELECT DATE_SUB('$tanggal_akhir', INTERVAL '$interval_sql' DAY) AS tgl_awal, DATE_SUB('$tanggal_akhir', INTERVAL '$interval' DAY) AS tgl_akhir_baru, IFNULL(SUM(penjualandetail.jml_jual), 0) AS jumlah_terjual FROM penjualan INNER JOIN penjualandetail ON penjualan.no_penjualan = penjualandetail.no_penjualan WHERE penjualandetail.kd_aksesoris = '$kd_aksesoris[0]' AND penjualan.tgl_penjualan BETWEEN (DATE_SUB('$tanggal_akhir', INTERVAL '$interval_sql' DAY)) AND '$tanggal_akhir'";
			$sql_tjl = mysqli_query($conn, $query_tjl) or die ($conn->error);
			$dpenjualan = mysqli_fetch_array($sql_tjl);
			$data[$i][0] = $dpenjualan['tgl_awal'];
			$data[$i][1] = $tanggal_akhir;
			$data[$i][2] = $dpenjualan['jumlah_terjual'];

			$data_ses[$i][0] = $dpenjualan['tgl_awal'];
			$data_ses[$i][1] = $tanggal_akhir;
			$data_ses[$i][2] = $dpenjualan['jumlah_terjual'];

			$tanggal_akhir = $dpenjualan['tgl_akhir_baru'];
		} else {
			$query_tjl = "SELECT YEAR(DATE_SUB('$tanggal_akhir', INTERVAL 1 MONTH)) AS dua, MONTH(DATE_SUB('$tanggal_akhir', INTERVAL 1 MONTH)) AS satu, DATE_SUB('$tanggal_akhir', INTERVAL 1 MONTH) AS tgl_awal, IFNULL(SUM(penjualandetail.jml_jual), 0) AS jumlah_terjual FROM penjualan INNER JOIN penjualandetail ON penjualan.no_penjualan = penjualandetail.no_penjualan WHERE penjualandetail.kd_aksesoris = '$kd_aksesoris[0]' AND (penjualan.tgl_penjualan >= DATE_SUB('$tanggal_akhir', INTERVAL 1 MONTH) AND penjualan.tgl_penjualan < '$tanggal_akhir')";
			$sql_tjl = mysqli_query($conn, $query_tjl) or die ($conn->error);
			$dpenjualan = mysqli_fetch_array($sql_tjl);
			$data[$i][0] = $dpenjualan['satu'];
			$data[$i][1] = $dpenjualan['dua'];
			$data[$i][2] = $dpenjualan['jumlah_terjual'];

			$data_ses[$i][0] = $dpenjualan['satu'];
			$data_ses[$i][1] = $dpenjualan['dua'];
			$data_ses[$i][2] = $dpenjualan['jumlah_terjual'];

			$tanggal_akhir = $dpenjualan['tgl_awal'];
		}
	}

	if($metode=="Semua" || $metode=="Weighted Moving Average") {
		include 'alg_ramalan/met_aksesoris.php';
	}
	if($metode=="Semua" || $metode=="Single Exponential Smoothing") {
		include 'alg_ramalan/met_ses.php';
	}
 ?>
<div class="form-container">
	<div class="keterangan-hasil" style="padding: 0 10px;">
		<table class="tabel-keterangan">
			<tr>
				<th>Nama aksesoris</th>
				<td>: <?php echo $nama_aksesoris[0]; ?></td>
			</tr>
			<tr>
				<th>Periode Ramalan</th>
				<td>: 
					<?php 
						if($prd_ramalan=="per_hari") {
							echo $interval." Hari kedepan (".tgl_indo($data[$baris][0])." sd. ".tgl_indo($data[$baris][1]).")";
						} else {
							echo bulan_indo($bulan_ini)."  ".$tahun_ini; 
						}
					?>
				</td>
			</tr>
			<tr>
				<th>Metode</th>
				<td>: Weighted Moving Average dan Single Exponential Smoothing</td>
			</tr>
			<?php if($metode=="Semua" || $metode=="Weighted Moving Average") { ?>
			<tr>
				<th>Nilai Moving Average</th>
				<td>: <?php echo $nilai_ma[0]; ?> periode dan <?php echo $nilai_ma[1]; ?> periode</td>
			</tr>
			<?php } ?>
			<?php if($metode=="Semua" || $metode=="Single Exponential Smoothing") { ?>
			<tr>
				<th>Nilai Bobot Pemulusan</th>
				<td>: <?php echo $alpha[0]; ?> dan <?php echo $alpha[1]; ?></td>
			</tr>
			<?php } ?>
		</table>
	</div>

	<style>
		.baris-prdramalan td {
			background-color: #d6d6d6;
			font-weight: bold;
		}
		.tabel-data-peramalan th {
			text-align: center;
			vertical-align: middle;
		}
		.lebih-kecil {
			background-color: #d6d6d6;
			font-weight: bold;
		}
		.kotak-alert {
			padding: 10px 40px;
			margin: 0;
			text-align: center;
		}
		.kotak-hasil p{
			font-weight: bold;
			font-size: 16px;
		}
		ul.nav-pills{
            padding: 12px 15px;
            /*border-bottom: 1px solid #169BB0;*/
        }
        .kotak-data-tab .nav-item{
            font-size: 12px;
            font-weight: lighter;
            padding-bottom: 5px;
            border-bottom: 1px solid #D9DADB;
            margin-right: 15px;
        }
        .kotak-data-tab .nav-link{
            color: #000000;
        }
        .kotak-data-tab .nav-link.active{
            background-color: #169BB0;
        }
	</style>
	<div class="kotak-data-tab">
		<?php 
			if($metode=="Semua") {
		 ?>
		<ul class="nav nav-pills" id="pills-tab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="aksesoris-tab" data-toggle="pill" href="#aksesoris" role="tab" aria-controls="aksesoris" aria-selected="true">Weighted Moving Average</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="SES-tab" data-toggle="pill" href="#SES" role="tab" aria-controls="SES" aria-selected="false">Single Exponential Smoothing</a>
          </li>
    	</ul>
    	<?php } ?>
		
		<div class="kotak-konten-tab" style="padding: 0 10px;">
	    	<div class="tab-content" id="pills-tabContent"  style="border: 1px solid #D9DADB;">

	    		<!-- TAB METODE MOVING AVERAGE -->
	    		<?php if($metode=="Semua" || $metode=="Weighted Moving Average") { ?>
	    		<div class="tab-pane fade show <?php if($metode=="Semua" || $metode=="Weighted Moving Average") {echo "active";} ?>" id="aksesoris" role="tabpanel" aria-labelledby="aksesoris-tab">
					<div class="tabel-periode table-responsive" style="padding: 0 10px; margin-top: 8px;">
						<table class="table table-bordered tabel-data-peramalan">
							<thead>
								<tr>
									<th style="vertical-align: middle;">No</th>
									<th style="vertical-align: middle;">Periode</th>
									<th style="vertical-align: middle;">Jumlah Terjual <br> (X)</th>
									<th style="vertical-align: middle; ">
										Ramalan M=<?php echo $nilai_ma[0]; ?> <br> (F<sub><?php echo $nilai_ma[0]; ?></sub>)
									</th>
									<th style="vertical-align: middle;">Error Absolute M=<?php echo $nilai_ma[0]; ?> <br> |X-F<sub><?php echo $nilai_ma[0]; ?></sub>|</th>
									<th style="vertical-align: middle;">Absolute Percentage M=<?php echo $nilai_ma[0]; ?> <br> |(X-F<sub><?php echo $nilai_ma[0]; ?></sub>)/X|*100</th>
									<th style="vertical-align: middle;">Square Deviation M=<?php echo $nilai_ma[0]; ?> <br> |X-F<sub><?php echo $nilai_ma[0]; ?></sub>|<sup>2</sup></th>
									<th style="vertical-align: middle;">Ramalan M=<?php echo $nilai_ma[1]; ?> <br> (F<sub><?php echo $nilai_ma[1]; ?></sub>)</th>
									<th style="vertical-align: middle;">Error Absolute M=<?php echo $nilai_ma[1]; ?> <br> |X-F<sub><?php echo $nilai_ma[1]; ?></sub>|</th>
									<th style="vertical-align: middle;">Absolute Percentage M=<?php echo $nilai_ma[1]; ?> <br> |(X-F<sub><?php echo $nilai_ma[1]; ?></sub>)/X|*100</th>
									<th style="vertical-align: middle;">Square Deviation M=<?php echo $nilai_ma[1]; ?> <br> |X-F<sub><?php echo $nilai_ma[1]; ?></sub>|<sup>2</sup></th>
								</tr>
							</thead>
							<tbody>
							<?php 
								for($i=0; $i<=$baris; $i++) {
							 ?>
							 		<tr <?php if($i==$baris) echo "Class='baris-prdramalan'"; ?>>
							 			<td width="6%" align="center"><?php echo $i+1; ?></td>
							 			<td>
							 				<?php 
							 					if($prd_ramalan=="per_hari"){
							 						echo tgl_indo($data[$i][0])." sd. ".tgl_indo($data[$i][1]); 
							 					} else {
								 					echo bulan_indo($data[$i][0])." ".$data[$i][1]; 
								 				}
							 				?>
							 			</td>
							 			<td align="center"><?php echo $data[$i][2]; ?></td>
							 			<td align="center" style="">
							 				<?php echo $data[$i][3]; ?>
							 			</td>
							 			<td align="center"><?php echo $data[$i][5]; ?></td>
							 			<td align="center"><?php echo $data[$i][7]; ?></td>
							 			<td align="center"><?php echo $data[$i][9]; ?></td>
							 			<td align="center"><?php echo $data[$i][4]; ?></td>
							 			<td align="center"><?php echo $data[$i][6]; ?></td>
							 			<td align="center"><?php echo $data[$i][8]; ?></td>
							 			<td align="center"><?php echo $data[$i][10]; ?></td>
							 		</tr>
							<?php } ?>
									<tr>
										<td colspan="2">Rata-rata Error</td>
										<td>-</td>
										<td>-</td>
										<td><?php echo $jml_ae2."/".$n2; ?></td>
										<td><?php echo $jml_pe2."/".$n2; ?></td>
										<td><?php echo $jml_sd2."/".$n2; ?></td>
										<td>-</td>
										<td><?php echo $jml_ae5."/".$n5; ?></td>
										<td><?php echo $jml_pe5."/".$n5; ?></td>
										<td><?php echo $jml_sd5."/".$n5; ?></td>
									</tr>
							</tbody>
						</table>
					</div>
					<div class="kotak-pembandingan" style="padding: 0 10px; margin-top: 8px;">
						<table class="table">
							<thead>
								<tr>
									<th></tthd>
									<th>Ramalan M=<?php echo $nilai_ma[0]; ?></th>
									<th>Ramalan M=<?php echo $nilai_ma[1]; ?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Mean Absolute Error</td>
									<td <?php if($hsl_mae_aksesoris[0]<$hsl_mae_aksesoris[1]) { 
										echo "Class='lebih-kecil'";
										$skor_m2++;
									} ?>>
										<?php echo $hsl_mae_aksesoris[0]; ?>
									</td>
									<td <?php if($hsl_mae_aksesoris[1]<$hsl_mae_aksesoris[0]) { 
										echo "Class='lebih-kecil'";
										$skor_m5++;
									} ?>>
										<?php echo $hsl_mae_aksesoris[1]; ?>
									</td>
								</tr>
								<tr>
									<td>Mean Absolute Percentage Error</td>
									<td <?php if($hsl_mape_aksesoris[0]<$hsl_mape_aksesoris[1]) { 
										echo "Class='lebih-kecil'";
										$skor_m2++;
									} ?>>
										<?php echo $hsl_mape_aksesoris[0]; ?>
									</td>
									<td <?php if($hsl_mape_aksesoris[1]<$hsl_mape_aksesoris[0]) { 
										echo "Class='lebih-kecil'";
										$skor_m5++;
									} ?>>
										<?php echo $hsl_mape_aksesoris[1]; ?>
									</td>
								</tr>
								<tr>
									<td>Mean Square Deviation Error</td>
									<td <?php if($hsl_msd_aksesoris[0]<$hsl_msd_aksesoris[1]) { 
										echo "Class='lebih-kecil'";
										$skor_m2++;
									} ?>>
										<?php echo $hsl_msd_aksesoris[0]; ?>
									</td>
									<td <?php if($hsl_msd_aksesoris[1]<$hsl_msd_aksesoris[0]) { 
										echo "Class='lebih-kecil'";
										$skor_m5++;
									} ?>>
										<?php echo $hsl_msd_aksesoris[1]; ?>
									</td>
								</tr>
								<tr>
									<td>Hasil Ramalan</td>
									<td <?php if($skor_m2>$skor_m5) { 
										echo "Class='lebih-kecil'";
									} ?>>
										<?php echo $data[$baris][3]; ?>
									</td>
									<td <?php if($skor_m5>$skor_m2) { 
										echo "Class='lebih-kecil'";
									} ?>>
										<?php echo $data[$baris][4]; ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<?php 
						if($skor_m5>$skor_m2) {
							$hasil_ramalan = $data[$baris][4];
							$mae_aksesoris = $hsl_mae_aksesoris[1];
							$mape_aksesoris = $hsl_mape_aksesoris[1];
							$msd_aksesoris = $hsl_msd_aksesoris[1];
							$stat_aksesoris[1] = "baik";
							$stat_aksesoris[0] = "kurang";
							$kes_aksesoris = $nilai_ma[1];
					?>
							<div style="padding: 4px; font-weight: bold;">
								*Catatan : Weighted Moving Average <?php echo $nilai_ma[1]; ?> periode mendapatkan skor perbandingan error yang lebih baik
							</div>
					<?php
						} else {
							$hasil_ramalan = $data[$baris][3];
							$mae_aksesoris = $hsl_mae_aksesoris[0];
							$mape_aksesoris = $hsl_mape_aksesoris[0];
							$msd_aksesoris = $hsl_msd_aksesoris[0];
							$stat_aksesoris[1] = "kurang";
							$stat_aksesoris[0] = "baik";
							$kes_aksesoris = $nilai_ma[0];
					?>
							<div style="padding: 4px; font-weight: bold;">
								*Catatan : Weighted Moving Average <?php echo $nilai_ma[0]; ?> periode mendapatkan skor perbandingan error yang lebih baik
							</div>
					<?php
						}
					 ?>
					<?php if($metode=="Weighted Moving Average") { ?>
					<div class="kotak-hasil hasil-aksesoris" style="padding: 0 10px; margin-top: 8px;">
						<div class="kotak-alert alert alert-success" role="alert">
							<p>
								Hasil peramalan penjualan aksesoris <span id="nama_aksesoris"><?php echo $nama_aksesoris[0]; ?></span> untuk periode bulan <span id="prd_bulan"><?php echo bulan_indo($bulan_ini); ?></span> <span id="prd_tahun"><?php echo "$tahun_ini"; ?></span> adalah sebesar <span id="hasil_ramalan"><?php echo round($hasil_ramalan); ?></span> <span id="satuan"><?php echo $sat_aksesoris[0]; ?></span>
							</p>
						</div>
					</div>
					<?php } ?>
				</div>
				<?php } ?>
				
				<!-- TAB METODE EXPONENTIAL SMOOTHING -->
				<?php if($metode=="Semua" || $metode=="Single Exponential Smoothing") { ?>
				<div class="tab-pane fade show <?php if($metode=="Single Exponential Smoothing") {echo "active";} ?>" id="SES" role="tabpanel" aria-labelledby="SES-tab">
					<div class="tabel-periode table-responsive" style="padding: 0 10px; margin-top: 8px;">
						<table class="table table-bordered tabel-data-peramalan">
							<thead>
								<tr>
									<th style="vertical-align: middle;">No</th>
									<th style="vertical-align: middle;">Periode</th>
									<th style="vertical-align: middle;">Jumlah Terjual <br> (X)</th>
									<th style="vertical-align: middle;">Ramalan a=0.2 <br> (F<sub>0.2</sub>)</th>
									<th style="vertical-align: middle;">Error Absolute a=0.2 <br> |X-F<sub>0.2</sub>|</th>
									<th style="vertical-align: middle;">Absolute Percentage a=0.2 <br> |(X-F<sub>0.2</sub>)/X|*100</th>
									<th style="vertical-align: middle;">Square Deviation a=0.2 <br> |X-F<sub>0.2</sub>|<sup>2</sup></th>
									<th style="vertical-align: middle;">Ramalan a=0.8 <br> (F<sub>0.8</sub>)</th>
									<th style="vertical-align: middle;">Error Absolute a=0.8 <br> |X-F<sub>0.8</sub>|</th>
									<th style="vertical-align: middle;">Absolute Percentage a=0.8 <br> |(X-F<sub>0.8</sub>)/X|*100</th>
									<th style="vertical-align: middle;">Square Deviation a=0.8 <br> |X-F<sub>0.8</sub>|<sup>2</sup></th>
								</tr>
							</thead>
							<tbody>
							<?php 
								for($i=0; $i<=$baris; $i++) {
							 ?>
							 		<tr <?php if($i==$baris) echo "Class='baris-prdramalan'"; ?>>
							 			<td width="6%" align="center"><?php echo $i+1; ?></td>
							 			<td>
							 				<?php 
							 					if($prd_ramalan=="per_hari"){
							 						echo tgl_indo($data_ses[$i][0])." sd. ".tgl_indo($data_ses[$i][1]); 
							 					} else {
								 					echo bulan_indo($data_ses[$i][0])." ".$data_ses[$i][1]; 
								 				}
							 				?>
							 			</td>
							 			<td align="center"><?php echo $data_ses[$i][2]; ?></td>
							 			<td align="center"><?php echo $data_ses[$i][3]; ?></td>
							 			<td align="center"><?php echo $data_ses[$i][5]; ?></td>
							 			<td align="center"><?php echo $data_ses[$i][7]; ?></td>
							 			<td align="center"><?php echo $data_ses[$i][9]; ?></td>
							 			<td align="center"><?php echo $data_ses[$i][4]; ?></td>
							 			<td align="center"><?php echo $data_ses[$i][6]; ?></td>
							 			<td align="center"><?php echo $data_ses[$i][8]; ?></td>
							 			<td align="center"><?php echo $data_ses[$i][10]; ?></td>
							 		</tr>
							<?php } ?>
									<tr>
										<td colspan="2">Rata-rata Error</td>
										<td>-</td>
										<td>-</td>
										<td><?php echo $jml_ae02."/".$n; ?></td>
										<td><?php echo $jml_pe02."/".$n; ?></td>
										<td><?php echo $jml_sd02."/".$n; ?></td>
										<td>-</td>
										<td><?php echo $jml_ae08."/".$n; ?></td>
										<td><?php echo $jml_pe08."/".$n; ?></td>
										<td><?php echo $jml_sd08."/".$n; ?></td>
									</tr>
							</tbody>
						</table>
					</div>
					<div class="kotak-pembandingan" style="padding: 0 10px; margin-top: 8px;">
						<table class="table">
							<thead>
								<tr>
									<th></tthd>
									<th>Ramalan a=<?php echo $alpha[0]; ?></th>
									<th>Ramalan a=<?php echo $alpha[1]; ?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Mean Absolute Error</td>
									<td <?php if($hsl_mae_ses[0]<$hsl_mae_ses[1]) {
										echo "Class='lebih-kecil'";
										$skor_e02++;
									} ?>>
										<?php echo $hsl_mae_ses[0]; ?>
									</td>
									<td <?php if($hsl_mae_ses[1]<$hsl_mae_ses[0]) {
										echo "Class='lebih-kecil'";
										$skor_e08++;
									} ?>>
										<?php echo $hsl_mae_ses[1]; ?>
									</td>
								</tr>
								<tr>
									<td>Mean Absolute Percentage Error</td>
									<td <?php if($hsl_mape_ses[0]<$hsl_mape_ses[1]) {
										echo "Class='lebih-kecil'";
										$skor_e02++;
									} ?>>
										<?php echo $hsl_mape_ses[0]; ?>
									</td>
									<td <?php if($hsl_mape_ses[1]<$hsl_mape_ses[0]) {
										echo "Class='lebih-kecil'";
										$skor_e08++;
									} ?>>
										<?php echo $hsl_mape_ses[1]; ?>
									</td>
								</tr>
								<tr>
									<td>Mean Square Deviation Error</td>
									<td <?php if($hsl_msd_ses[0]<$hsl_msd_ses[1]) {
										echo "Class='lebih-kecil'";
										$skor_e02++;
									} ?>>
										<?php echo $hsl_msd_ses[0]; ?>
									</td>
									<td <?php if($hsl_msd_ses[1]<$hsl_msd_ses[0]) {
										echo "Class='lebih-kecil'";
										$skor_e08++;
									} ?>>
										<?php echo $hsl_msd_ses[1]; ?>
									</td>
								</tr>
								<tr>
									<td>Hasil Ramalan</td>
									<td <?php if($skor_e02>$skor_e08) { 
										echo "Class='lebih-kecil'";
									} ?>>
										<?php echo $data_ses[$baris][3]; ?>
									</td>
									<td <?php if($skor_e08>$skor_e02) { 
										echo "Class='lebih-kecil'";
									} ?>>
										<?php echo $data_ses[$baris][4]; ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<?php 
						if($skor_e08>$skor_e02) {
							$hasil_ramalan_es = $data_ses[$baris][4];
							$mae_ses = $hsl_mae_ses[1];
							$mape_ses = $hsl_mape_ses[1];
							$msd_ses = $hsl_msd_ses[1];
							$stat_ses[0] = "kurang";
							$stat_ses[1] = "baik";
							$kes_ses = $alpha[1];
						?>
							<div style="padding: 4px; font-weight: bold;">
								*Catatan : Single Exponential Smoothing alpha <?php echo $alpha[1]; ?> mendapatkan skor perbandingan error yang lebih baik
							</div>
						<?php
						} else {
							$hasil_ramalan_es = $data_ses[$baris][3];
							$mae_ses = $hsl_mae_ses[0];
							$mape_ses = $hsl_mape_ses[0];
							$msd_ses = $hsl_msd_ses[0];
							$stat_ses[0] = "baik";
							$stat_ses[1] = "kurang";
							$kes_ses = $alpha[0];
						?>
							<div style="padding: 4px; font-weight: bold;">
								*Catatan : Single Exponential Smoothing alpha <?php echo $alpha[0]; ?> mendapatkan skor perbandingan error yang lebih baik
							</div>
						<?php
						}
					 ?>
					<?php if($metode=="Single Exponential Smoothing") { ?>
					<div class="kotak-hasil hasil-aksesoris" style="padding: 0 10px; margin-top: 8px;">
						<div class="kotak-alert alert alert-success" role="alert">
							<p>
								Hasil peramalan penjualan aksesoris <span id="nama_aksesoris"><?php echo $nama_aksesoris[0]; ?></span> untuk periode bulan <span id="prd_bulan"><?php echo bulan_indo($bulan_ini); ?></span> <span id="prd_tahun"><?php echo "$tahun_ini"; ?></span> adalah sebesar <span id="hasil_ramalan"><?php echo round($hasil_ramalan_es); ?></span> <span id="satuan"><?php echo $sat_aksesoris[0]; ?></span>
							</p>
						</div>
					</div>
					<?php } ?>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	
	<?php if($metode=="Semua") { ?> 
	<div class="kotak-perbandingan-akhir" style="padding: 0 10px; margin-top: 15px;">
		<div class="border-kotak-perbandingan-akhir" style="padding: 10px 0; border: 1px solid #D9DADB;">
			<h6 style="font-weight: bold; text-align: center;">Tabel Perbandingan Error Metode Weighted Moving Average and Single Exponential Smoothing</h6>
			<div class="kotak-pembandingan" style="padding: 0 10px; margin-top: 8px;">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th rowspan="2"></th>
							<th colspan="2" style="text-align: center;">Metode</th>
						</tr>
						<tr>
							<th style="text-align: center;">
								Weighted Moving Average <?php echo $kes_aksesoris; ?> periode
							</th>
							<th style="text-align: center;">
								Single Exponential Smoothing alpha <?php echo $kes_ses; ?>
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Mean Absolute Error</td>
							<td <?php if($mae_aksesoris<$mae_ses) {
								echo "Class='lebih-kecil'";
								$skor_aksesoris++;
							} ?>>
								<?php echo $mae_aksesoris; ?>
							</td>
							<td <?php if($mae_ses<$mae_aksesoris) {
								echo "Class='lebih-kecil'";
								$skor_ses++;
							} ?>>
								<?php echo $mae_ses; ?>
							</td>
						</tr>
						<tr>
							<td>Mean Absolute Percentage Error</td>
							<td <?php if($mape_aksesoris<$mape_ses) {
								echo "Class='lebih-kecil'";
								$skor_aksesoris++;
							} ?>>
								<?php echo $mape_aksesoris; ?>
							</td>
							<td <?php if($mape_ses<$mape_aksesoris) {
								echo "Class='lebih-kecil'";
								$skor_ses++;
							} ?>>
								<?php echo $mape_ses; ?>
							</td>
						</tr>
						<tr>
							<td>Mean Square Deviation Error</td>
							<td <?php if($msd_aksesoris<$msd_ses) {
								echo "Class='lebih-kecil'";
								$skor_aksesoris++;
							} ?>>
								<?php echo $msd_aksesoris; ?>
							</td>
							<td <?php if($msd_ses<$msd_aksesoris) {
								echo "Class='lebih-kecil'";
								$skor_ses++;
							} ?>>
								<?php echo $msd_ses; ?>
							</td>
						</tr>
						<tr>
							<th>Hasil Ramalan</th>
							<td <?php if($skor_aksesoris>$skor_ses) {
								echo "Class='lebih-kecil'";
								$hasil_ramalan_akhir = $hasil_ramalan;
								$mtd_terbaik = "aksesoris";
								$error_mape = $mape_aksesoris;
								$akurasi = 100 - $error_mape;
							} ?>>
								<?php echo $hasil_ramalan; ?>
							</td>
							<td <?php if($skor_ses>$skor_aksesoris) {
								echo "Class='lebih-kecil'";
								$hasil_ramalan_akhir = $hasil_ramalan_es;
								$mtd_terbaik = "SES";
								$error_mape = $mape_ses;
								$akurasi = 100 - $error_mape;
							} ?>>
								<?php echo $hasil_ramalan_es; ?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="kotak-hasil hasil-semua" style="padding: 0 10px; margin-top: 8px;">
				<div class="kotak-alert alert alert-success" role="alert">
					<!-- <p>
						<u>Kesimpulan</u> : Pada periode <?php if($prd_ramalan=="per_hari") {
							echo $interval." Hari kedepan (".tgl_indo($data[$baris][0])." sd. ".tgl_indo($data[$baris][1]).")";
						} else {
							echo "bulan ".bulan_indo($bulan_ini)."  ".$tahun_ini; 
						} ?>,
						aksesoris <?php echo $nama_aksesoris[0]; ?> diprediksi akan terjual sebanyak <?php echo round($hasil_ramalan_akhir); ?> <?php echo $sat_aksesoris[0]; ?> dengan tingkat keakuratan sebesar <?php echo round($akurasi, 2); ?>%
					</p> -->
					<style>
						.tabel-hasil td {
							text-align: left;
							font-weight: bold;
							padding: 8px;
							vertical-align: top;
						}
					</style>
					<table class="table tabel-hasil">
						<tr>
							<td><u>Kesimpulan</u></td>
							<td>
								Pada periode <?php if($prd_ramalan=="per_hari") {
									echo $interval." Hari kedepan (".tgl_indo($data[$baris][0])." sd. ".tgl_indo($data[$baris][1]).")";
								} else {
									echo "bulan ".bulan_indo($bulan_ini)."  ".$tahun_ini; 
								} ?> aksesoris <?php echo $nama_aksesoris[0]; ?> diprediksi akan terjual sebanyak <?php echo round($hasil_ramalan_akhir); ?> <?php echo $sat_aksesoris[0]; ?>
							</td>
						</tr>
						<tr>
							<td><u>Akurasi</u></td>
							<td>
								<?php echo round($akurasi, 2); ?>%
							</td>
						</tr>
						<tr>
							<td><u>Metode</u></td>
							<td>
								<?php 
									if($mtd_terbaik == "aksesoris") {
										if($kes_aksesoris == $nilai_ma[0]) {
											echo "Weighted Moving Average ".$nilai_ma[0]." periode";
										} else {
											echo "Weighted Moving Average ".$nilai_ma[1]." periode";
										}
									} else {
										if($kes_ses == $alpha[0]) {
											echo "Single Exponential Smoothing dengan bobot pemulusan".$alpha[0];
										} else {
											echo "Single Exponential Smoothing dengan bobot pemulusan".$alpha[1];
										}
									}
								 ?>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
	<style>
		.canvas {
			padding: 20px;
			height: 350px;
			/*width: 90%;*/
		}
	</style>
	<div class="canvas">
		<canvas id="myChart">
		
		</canvas>
	</div>
	<?php 
		$hasil = array();
		$labels2 = array();
		$data2 = array();
		$data3 = array();

		for($i=0; $i<=$baris; $i++) {
			if($prd_ramalan == "per_hari") {
				$labels2[] = tgl_indo($data[$i][0])." sd ".tgl_indo($data[$i][1]);
			} else {
				$labels2[] = bulan_indo($data[$i][0])." ".$data[$i][1];
			}

			if($i==$baris) {
				$data2[] = null;
			} else {
				$data2[] = $data[$i][2];
			}
		}

		if($mtd_terbaik == "aksesoris") {
			if($kes_aksesoris == $nilai_ma[0]) {
				$hasil = $pola_aksesoris0;
			} else {
				$hasil = $pola_aksesoris1;
			}
			for($i=0; $i<=$baris; $i++) {
				$data3[] = $hasil[$i];
			}
		} else {
			if($kes_ses == $alpha[0]) {
				$hasil = $pola_ses0;
			} else {
				$hasil = $pola_ses1;
			}
			for($i=0; $i<=$baris; $i++) {
				$data3[] = $hasil[$i];
			}
		}

		// echo json_encode($pola_aksesoris0); echo "<br>";
		// echo json_encode($pola_aksesoris1); echo "<br>";
		// echo json_encode($pola_ses0); echo "<br>";
		// echo json_encode($pola_ses1); echo "<br>";
	 ?>

	<script>
		var ctx = document.getElementById('myChart').getContext('2d');
		var myChart = new Chart(ctx, {
		    type: 'line',
		    data: {
		        labels: <?php echo json_encode($labels2); ?>,
		        datasets: [{
		            label: 'Pola Data Penjualan',
		            fill: false,
		            // borderDash: [5, 5],
		            data: <?php echo json_encode($data2); ?>,
		            backgroundColor: [
		                'rgba(0, 255, 0, 0.1)'
		            ],
		            borderColor: [
		                'rgba(0, 255, 0, 1)'
		            ],
		            borderWidth: 1
		        }, {
		            label: 'Pola Data Ramalan',
		            fill: false,
		            // borderDash: [5, 5],
		            data: <?php echo json_encode($data3); ?>,
		            backgroundColor: [
		                'rgba(0, 0, 255, 0.1)'
		            ],
		            borderColor: [
		                'rgba(0, 0, 255, 1)'
		            ],
		            borderWidth: 1
		        }]
		    },
		    options: {
		    	responsive: true,
		    	maintainAspectRatio: false,
		        scales: {
		            yAxes: [{
		                ticks: {
		                    beginAtZero: true
		                }
		            }]
		        }
		    }
		});
	</script>
	
	<?php 
	
	?>	
</div>