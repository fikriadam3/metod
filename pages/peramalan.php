<?php
// Ambil tahun saat ini
$tahun_ini = date("Y");
// Hitung tahun depan
$tahun_depan = $tahun_ini + 1;
?>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb bg-light">
    <li class="breadcrumb-item"><a href="./"><i class="fas fa-home"></i> Home</a></li>
    <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-chart-bar"></i> Peramalan Penjualan</li>
  </ol>
</nav>

<div class="page-content">
	<div class="row">
		<div class="col-6"><h4>Peramalan Aksesoris berdasarkan penjualan</h4></div>
		<div class="col-6 text-right">
      		<a href="?page=riwayat_peramalan">
				<!-- <button class="btn btn-sm btn-info">Riwayat Peramalan</button> -->
			</a>
		</div>
	</div>
	<form action="" method="POST" target="" id="form_peramalan">
		<div class="form-container">
			<div class="row">
				<div class="col-md-6 offset-md-3 offset-form">
					<h6><i class="fas fa-list-alt"></i> Lengkapi form ini untuk melakukan peramalan penjualan</h6>
					
					  <div class="form-group row">
					    <label for="nm_aksesoris" class="col-sm-3 col-form-label">Pilih Aksesoris</label>
					    <div class="col-sm-9">
					      <div class="input-group">
					      	<textarea name="nm_aksesoris" id="nm_aksesoris" rows="2" class="form-control" placeholder="aksesoris terpilih" style="font-size: 14px; height: 90px;" readonly=""></textarea>
					      	<div class="input-group-append">
	                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modal_dataaksesoris" id="lihat_data_aksesoris"><i class="fas fa-search"></i></button>
	                        </div>
					      </div>
					    </div>
					  </div>
					  <div class="form-group row">
					    <label for="ip_periode" class="col-sm-3 col-form-label">Periode Peramalan</label>
					    <div class="col-sm-9">
					      <div class="form-check">
					      	<label class="form-check-label" style="font-weight: normal;">
					      		<input name="ip_periode" id="ip_periode1" type="radio" class="form-check-input" value="tahun_ini" checked=""> 
					      		Tahun ini (<?php echo $tahun_ini; ?>)
					      	</label>
					      </div>
	                      <div class="form-check">
	                    	<label class="form-check-label" style="font-weight: normal;">
	                    		<input name="ip_periode" id="ip_periode2" type="radio" class="form-check-input" value="tahun_depan">
	                    		Tahun depan (<?php echo $tahun_depan; ?>)
	                    	</label>
	                	  </div>
					    </div>
					  </div>
					  <div class="form-group row">
					    <label for="met_peramalan" class="col-sm-3 col-form-label">Metode Peramalan</label>
					    <div class="col-sm-9">
					      <select name="met_peramalan" id="met_peramalan" class="form-control form-control-sm">
					      	<option value="Weighted Moving Average" selected="">Weighted Moving Average</option>
					      </select>
					    </div>
					  </div>
					  <div class="form-group row">
					    <div class="col-sm-12 text-right">
					      <button type="button" class="btn btn-info" id="hitung_ramal" name="hitung_ramal">Hitung</button>
					    </div>
					  </div>
					
				</div>
			</div>
		</div>
		<div class="modal fade" id="modal_dataaksesoris" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-xl" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">Data Aksesoris</h5>
		        <button type="button" class="close" data-dismiss="modal" id="tb_close" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <table id="" class="table table-striped display">
		            <thead>
		                <tr>
		                    <th>Kode</th>
		                    <th>Nama Aksesoris</th>
		                    <th>Stok</th>
							<th>Minimal Stok</th>
		                    <th>Satuan</th>
		                    <th>Opsi</th>
		                </tr>
		            </thead>
		            <tbody>
		        <?php 
		            $query_tampil = "SELECT * FROM aksesoris ORDER BY nm_aksesoris ASC";
		            $sql_tampil = mysqli_query($conn, $query_tampil) or die ($conn->error);
		            $no=0;
		            while($data = mysqli_fetch_array($sql_tampil)) {
		         ?>
		                <tr>
		                    <td><?php echo $data['kd_aksesoris']; ?></td>
		                    <td><?php echo $data['nm_aksesoris']; ?></td>
		                    <td><?php echo $data['stk_aksesoris']; ?></td>
		                    <td><?php echo $data['minstk_aksesoris']; ?></td>
							<td><?php echo $data['sat_aksesoris']; ?></td>
		                    <td class="td-opsi">
		                        <input class="form-check-input position-static pilih-aksesoris" type="checkbox" name="aksesoris[]" id="aksesoris<?php echo $no++; ?>" value="<?php echo $data['kd_aksesoris']; ?>" data-nama="<?php echo $data['nm_aksesoris']; ?>">
		                    </td>
		                </tr>
		         <?php 
		            } 
		         ?>
		            </tbody>
		        </table>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary btn-sm" id="selesai_pilih" data-dismiss="modal">Selesai</button>
		      </div>
		    </div>
		  </div>
		</div>
	</form>
</div>

<script>
	var aksesoris = [];
	var nama = [];
	var jml = 0;

	$("button[name='tombol_pilihaksesoris']").click(function() {
	    var kode = $(this).data("kode");
	    var nama = $(this).data("nama");
	    var satuan = $(this).data("satuan");
	    $("#ip_kd_aksesoris").val(kode);
	    $("#ip_sat_aksesoris").val(satuan);
	    $("#nm_aksesoris").val(nama);
	});

	$("#selesai_pilih").click(function() {
	    $(':checkbox:checked').each(function(i){
	        aksesoris[i] = $(this).val();
	        nama[i] = $(this).data('nama');
	    });
	    jml = aksesoris.length;
	    $("#nm_aksesoris").val(nama.join(", ")); // Menggabungkan nama aksesoris yang dipilih

	    aksesoris = [];
	    nama = [];
	});

	$("#tb_close").click(function() {
	    $("#selesai_pilih").click();
	});

	$("#hitung_ramal").click(function() {
	    var nama = $("#nm_aksesoris").val();
	    var kdaksesoris = $("#nm_aksesoris").val();
	    var metode = $("#met_peramalan").val();
	    var periode = document.querySelector('input[name="ip_periode"]:checked').value;

	    if(nama == "") {
	        document.getElementById("nm_aksesoris").focus();
	        Swal.fire(
	          'Data Belum Lengkap',
	          'maaf, tolong pilih aksesoris terlebih dulu',
	          'warning'
	        );
	    } else {
	        var form_data = $("#form_peramalan").serialize();
	        $.ajax({
	            url: "ajax/cek_datapenjualan.php",
	            method: "GET",
	            data: form_data,
	            success: function(data) {
	                var objKode = JSON.parse(data);
	                if(objKode != "") {
	                    Swal.fire(
	                      'Belum ada transaksi penjualan',
	                      'maaf, untuk ' + objKode + ' belum terdapat transaksi penjualan yang dilakukan selama periode yang dipilih sebelumnya',
	                      'warning'
	                    );
	                } else {
	                    var form = document.getElementById("form_peramalan");
	                    form.action = '?page=hasil_peramalan';
	                    form.submit();                    
	                }                
	            }
	        });
	    }
	});
</script>
