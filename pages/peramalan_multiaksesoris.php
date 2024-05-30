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
<div class="form-container">
	<div class="keterangan-hasil" style="padding: 0 10px;">
		<table class="tabel-keterangan">
			<tr>
				<th>Periode Ramalan</th>
				<td>: 
					<?php 
						echo "$periode_ramal";
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
	<div class="tabel-periode table-responsive" style="padding: 0 10px; margin-top: 8px;">
	<table class="table table-striped tab;e-bordered">
		<thead>
			<tr>
				<th>No</th>
				<th>Nama aksesoris</th>
				<th>Satuan</th>
				<th>MEA</th>
				<th>MAPE</th>
				<th>MSD</th>
				<th>Hasil Ramalan</th>
				<th>Akurasi</th>
			</tr>
		</thead>
	<?php 
	for($x=0; $x<$jml_aksesoris; $x++) {
		$skor_wma = 0;
		$skor_ses = 0;
		
		$data = array();
		$rml_wma = array();
		$hsl_mae_wma = array();
		$hsl_mape_wma = array();
		$hsl_msd_wma = array();
		$stat_wma = array();

		$data_ses = array();
		$rml_ses = array();
		$hsl_mae_ses = array();
		$hsl_mape_ses = array();
		$hsl_msd_ses = array();
		$stat_ses = array();
		$baris = 11;

		$bulan_ini = date('m');
		$tahun_ini = date('Y');

		if($prd_ramalan=="bulan_depan") {
			if($bulan_ini=="12") {
		  		$bulan_ini = 1;
		  		$tahun_ini = $tahun_ini+1;
		  	} else {
		  		$bulan_ini = $bulan_ini+1;
		  	}
		  	$tanggal_akhir = $tahun_ini."-".$bulan_ini."-01";
		} else if($prd_ramalan=="bulan_ini"){
			$tanggal_akhir = date('Y-m-01');
		} else if($prd_ramalan=="per_hari"){
			$tanggal_akhir = date('Y-m-d');
		}
		
		
		for($i=10; $i>=0; $i--) {
			if($prd_ramalan=="per_hari") {
				$query_tjl = "SELECT DATE_SUB('$tanggal_akhir', INTERVAL '$interval_sql' DAY) AS tgl_awal, DATE_SUB('$tanggal_akhir', INTERVAL '$interval' DAY) AS tgl_akhir_baru, IFNULL(SUM(penjualandetail.jml_jual), 0) AS jumlah_terjual FROM penjualan INNER JOIN penjualandetail ON penjualan.no_penjualan = penjualandetail.no_penjualan WHERE penjualandetail.kd_aksesoris = '$kd_aksesoris[$x]' AND penjualan.tgl_penjualan BETWEEN (DATE_SUB('$tanggal_akhir', INTERVAL '$interval_sql' DAY)) AND '$tanggal_akhir'";
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
				$query_tjl = "SELECT YEAR(DATE_SUB('$tanggal_akhir', INTERVAL 1 MONTH)) AS dua, MONTH(DATE_SUB('$tanggal_akhir', INTERVAL 1 MONTH)) AS satu, DATE_SUB('$tanggal_akhir', INTERVAL 1 MONTH) AS tgl_awal, IFNULL(SUM(penjualandetail.jml_jual), 0) AS jumlah_terjual FROM penjualan INNER JOIN penjualandetail ON penjualan.no_penjualan = penjualandetail.no_penjualan WHERE penjualandetail.kd_aksesoris = '$kd_aksesoris[$x]' AND (penjualan.tgl_penjualan >= DATE_SUB('$tanggal_akhir', INTERVAL 1 MONTH) AND penjualan.tgl_penjualan < '$tanggal_akhir')";
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
			include 'alg_ramalan/met_wma.php';
		}
		if($metode=="Semua" || $metode=="Single Exponential Smoothing") {
			include 'alg_ramalan/met_ses.php';
		}

		// HITUNG SKOR ERROR wma
		if($hsl_mae_wma[0]<$hsl_mae_wma[1]) {
			$skor_m2++;
		} else if($hsl_mae_wma[1]<$hsl_mae_wma[0]) {
			$skor_m5++;
		}
		if($hsl_mape_wma[0]<$hsl_mape_wma[1]) {
			$skor_m2++;
		} else if($hsl_mape_wma[1]<$hsl_mape_wma[0]) {
			$skor_m5++;
		}
		if($hsl_msd_wma[0]<$hsl_msd_wma[1]) {
			$skor_m2++;
		} else if($hsl_msd_wma[1]<$hsl_msd_wma[0]) {
			$skor_m5++;
		}

		if($skor_m5>$skor_m2) {
			$hasil_ramalan = $data[$baris][4];
			$mae_wma = $hsl_mae_wma[1];
			$mape_wma = $hsl_mape_wma[1];
			$msd_wma = $hsl_msd_wma[1];
			$stat_wma[0] = "kurang";
			$stat_wma[1] = "baik";
		} else {
			$hasil_ramalan = $data[$baris][3];
			$mae_wma = $hsl_mae_wma[0];
			$mape_wma = $hsl_mape_wma[0];
			$msd_wma = $hsl_msd_wma[0];
			$stat_wma[0] = "baik";
			$stat_wma[1] = "kurang";
		}

		// HITUNG SKOR ERROR SES
		if($hsl_mae_ses[0]<$hsl_mae_ses[1]) {
			$skor_e02++;
		} else if($hsl_mae_ses[1]<$hsl_mae_ses[0]) {
			$skor_e08++;
		}
		if($hsl_mape_ses[0]<$hsl_mape_ses[1]) {
			$skor_e02++;
		} else if($hsl_mape_ses[1]<$hsl_mape_ses[0]) {
			$skor_e08++;
		}
		if($hsl_msd_ses[0]<$hsl_msd_ses[1]) {
			$skor_e02++;
		} else if($hsl_msd_ses[1]<$hsl_msd_ses[0]) {
			$skor_e08++;
		}

		if($skor_e08>$skor_e02) {
			$hasil_ramalan_es = $data_ses[$baris][4];
			$mae_ses = $hsl_mae_ses[1];
			$mape_ses = $hsl_mape_ses[1];
			$msd_ses = $hsl_msd_ses[1];
			$stat_ses[0] = "kurang";
			$stat_ses[1] = "baik";
		} else {
			$hasil_ramalan_es = $data_ses[$baris][3];
			$mae_ses = $hsl_mae_ses[0];
			$mape_ses = $hsl_mape_ses[0];
			$msd_ses = $hsl_msd_ses[0];
			$stat_ses[0] = "baik";
			$stat_ses[1] = "kurang";
		}

		// HITUNG SKOR KEDUA METODE
		if($mae_wma<$mae_ses) {
			$skor_wma++;
		} else if($mae_ses<$mae_wma) {
			$skor_ses++;
		}
		if($mape_wma<$mape_ses) {
			$skor_wma++;
		} else if($mape_ses<$mape_wma) {
			$skor_ses++;
		}
		if($msd_wma<$msd_ses) {
			$skor_wma++;
		} else if($msd_ses<$msd_wma) {
			$skor_ses++;
		}

		if($skor_wma>$skor_ses) {
			$hasil_ramalan_akhir = $hasil_ramalan;
			$mtd_terbaik = "wma";
			$mae_rml = $mae_wma;
			$mape_rml = $mape_wma;
			$msd_rml = $msd_wma;
		} else if($skor_ses>$skor_wma) {
			$hasil_ramalan_akhir = $hasil_ramalan_es;
			$mtd_terbaik = "SES";
			$mae_rml = $mae_ses;
			$mape_rml = $mape_ses;
			$msd_rml = $msd_ses;
		}

	?>
		
		<tr>
			<td><?php echo $x+1; ?></td>
			<td><?php echo $nama_aksesoris[$x]; ?></td>
			<td><?php echo $sat_aksesoris[$x]; ?></td>
			<td><?php echo $mae_rml; ?></td>
			<td><?php echo $mape_rml; ?></td>
			<td><?php echo $msd_rml; ?></td>
			<?php 
				$akurasi = 100 - $mape_rml;
			 ?>
			<th><?php echo round($hasil_ramalan_akhir); ?></th>
			<th><?php echo round($akurasi, 2); ?>%</th>
		</tr>
		
		<?php 
		?>	
<?php 
	}
?>
	</table>
	</div>
</div>