<?php 
  require_once "koneksi.php";
  session_start();
  if(!@$_SESSION['posisi_peg']) {
    echo "<script>window.location='login.php';</script>";
  } else {
 ?>
 
 <!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="asset/img/logo/logos.jpg">
    <!-- Bootstrap CSS -->
    <!-- Bootstrap CSS (Updated to Bootstrap 5) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS (for Bootstrap 5) -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <link rel="stylesheet" href="asset/bootstrap_4/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="asset/private_style/style_index.css">
    <link rel="stylesheet" href="asset/font_awesome/css/all.css">
    <link rel="stylesheet" href="asset/DataTables/datatables.min.css">
    <link rel="stylesheet" href="asset/sweetalert/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="asset/bootstrap_datepicker1.9.0/css/bootstrap-datepicker.min.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    
       <!-- Favicon -->
     <link href="img/favicon.ico" rel="icon">
     <style>
        .dropdown-menu {
            padding: 10px;
            margin-top: 0;
            border-radius: 0.5rem;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .dropdown-item {
            padding: 10px 20px;
            margin: 5px 0;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease;
        }
        .dropdown-item:hover,
        .dropdown-item.active {
            background-color: #e9ecef;
            color: #333;
        }
        .nav-link {
            display: flex;
            align-items: center;
        }
        .nav-link .me-2 {
            margin-right: 0.5rem;
        }
        /* Tambahkan padding ke elemen container utama */
         .container-fluid {
          padding: 15px;
        }

        .table-warning {
        background-color: #fff3cd;
      }
        

    </style>

<!-- Google Web Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Icon Font Stylesheet -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

<!-- Libraries Stylesheet -->
<link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
<link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

<!-- Template Stylesheet -->
<link href="css/style.css" rel="stylesheet">


		
    
    <title>
      Aplikasi Forecasting CV Three Star Cahaya Buana | 
      <?php 
        if(@$_GET['page']=='') {
          echo "Dashboard";
        } else if(@$_GET['page']=='dataaksesoris' || @$_GET['page']=='tambah_dataaksesoris' || @$_GET['page']=='edit_dataaksesoris') {
          echo "Data aksesoris";
        } else if(@$_GET['page']=='datapegawai' || @$_GET['page']=='tambah_datapegawai' || @$_GET['page']=='edit_datapegawai') {
          echo "Data Pegawai";
        } else if(@$_GET['page']=='datapenjualan' || @$_GET['page']=='entry_datapenjualan' || @$_GET['page']=='form_tambahpenjualan' || @$_GET['page']=='datapenjualan_aksesoris') {
          echo "Data Penjualan";
        } else if(@$_GET['page']=='datapembelian' || @$_GET['page']=='entry_datapembelian' || @$_GET['page']=='form_laporanpembelian') {
          echo "Data Pembelian";
        } else if(@$_GET['page']=='laporan') {
          echo "Laporan";
        }
      ?>
    </title>
 

 
</head>

<body>
    <div class="container-fluid position-relative bg-white d-flex p-0">


        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-light navbar-light">
            <a href="./" class="navbar-brand mx-3 mb-3">
           <h5 class="text-primary"></i>Aplikasi Peramalan</h5>
            </a>
                
            <div class="navbar-nav w-100">
    <a href="./" class="nav-item nav-link <?php if(@$_GET['page']=='') {echo "active";} ?>">
        <i class="fa fa-home me-2"></i>Beranda
    </a>
    <div class="nav-item dropdown">
    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
        <i class="fa fa-folder me-2"></i>Data Master &nbsp;&nbsp;&nbsp;
    </a>
    <div class="dropdown-menu bg-transparent border-0">
        <a href="?page=dataaksesoris" class="dropdown-item <?php if(@$_GET['page']=='dataaksesoris' || @$_GET['page']=='tambah_dataaksesoris' || @$_GET['page']=='edit_dataaksesoris' || @$_GET['page']=='info_kadaluarsa') {echo "active";} ?>">
            <i class="fa fa-stethoscope me-2"></i> Data Aksesoris
        </a>
        <?php if($_SESSION['posisi_peg'] == 'Administrator' || $_SESSION['posisi_peg'] == 'General Manager' || $_SESSION['posisi_peg'] == 'Direktur Utama') { ?>  
            <a href="?page=datapegawai" class="dropdown-item <?php if(@$_GET['page']=='datapegawai' || @$_GET['page']=='tambah_datapegawai' || @$_GET['page']=='edit_datapegawai') {echo "active";} ?>">
                <i class="fa fa-user-circle me-2"></i> Data Pegawai
            </a>
            <a href="?page=datasupplier" class="dropdown-item <?php if(@$_GET['page']=='datasupplier' || @$_GET['page']=='tambah_datasupplier' || @$_GET['page']=='edit_datasupplier') {echo "active";} ?>">
                <i class="fa fa-truck me-2"></i> Data Supplier
            </a>
            <a href="?page=datapenjualan" class="dropdown-item <?php if(@$_GET['page']=='datapenjualan' || @$_GET['page']=='entry_datapenjualan' || @$_GET['page']=='form_tambahpenjualan' || @$_GET['page']=='datapenjualan_aksesoris') {echo "active";} ?>">
        <i class="fa fa-file-invoice-dollar me-2"></i>Data Penjualan
    </a>
        <?php } ?>
    </div>
</div>
    <?php if($_SESSION['posisi_peg'] == 'Administrator' || $_SESSION['posisi_peg'] == 'Administrasi') { ?>
    <a href="?page=datapembelian" class="nav-item nav-link <?php if(@$_GET['page']=='datapembelian' || @$_GET['page']=='entry_datapembelian' || @$_GET['page']=='form_laporanpembelian') {echo "active";} ?>">
        <i class="fas fa-shopping-bag me-2"></i>Pembelian
    </a>
    <a href="?page=peramalan" class="nav-item nav-link <?php if(@$_GET['page']=='peramalan' || @$_GET['page']=='hasil_peramalan' || @$_GET['page']=='riwayat_peramalan') {echo "active";} ?>">
        <i class="fas fa-chart-bar me-2"></i>Peramalan 
    </a>
    <?php } ?>
</div>
            </nav>
        </div>
        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
    <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
    <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
        <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
    </a>
    <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
    
    <div class="navbar-nav align-items-center ms-auto">
        <div class="nav-item d-flex align-items-center">
            <span class="text-dark tanggal-jam" id="jam"></span>
        </div>
        <!-- Profil dipindahkan ke kanan dengan menambahkan ms-auto -->
        <div class="nav-item dropdown ms-auto">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <span class="d-none d-lg-inline-flex"><?php echo $_SESSION['posisi_peg']; ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                <!-- Isi dropdown menu -->
                <div class="col-12 text-center nama-posisi p-2">
                    <h2>
                        <i class="fas fa-user-circle"></i>
                    </h2>
                    <span class="nama"><?php echo $_SESSION['nama_peg']; ?></span><br>
                    <span class="posisi">ID: <span id="id_session" class="posisi"><?php echo $_SESSION['id_peg']; ?></span></span>
                </div>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item" id="tombol_profil" data-bs-toggle="modal" data-bs-target="#profil_user">Profil</a>
                <a href="#" class="dropdown-item" id="tombol_keluar">Logout</a>
            </div>
        </div>
    </div>
</nav>
    <div class="container-fluid pt-4 px-4">

<script src="asset/Jquery/jquery-3.3.1.min.js"></script>
      <script src="asset/sweetalert/dist/sweetalert2.min.js"></script>
      <script src="asset/bootstrap_datepicker1.9.0/js/bootstrap-datepicker.min.js"></script>
      <script src="asset/bootstrap_datepicker1.9.0/locales/bootstrap-datepicker.id.min.js"></script>
      <script src="asset/ChartJs/Chart.min.js"></script>
            <!-- Navbar End -->


  			<?php 
  				if(@$_GET['page']=='') {
    					include 'pages/home.php';
    					// echo "Halaman Dashboard";
    				} else if(@$_GET['page']=='dataaksesoris') {
    					include 'pages/dataaksesoris.php';
    				} else if(@$_GET['page']=='info_kadaluarsa') {
              include 'pages/info_kadaluarsa.php';
            } else if(@$_GET['page']=='datapegawai') {
          		include 'pages/datapegawai.php';
        		} else if(@$_GET['page']=='tambah_datapegawai') {
          		include 'pages/form_tmbdatapegawai.php';
        		} else if(@$_GET['page']=='tambah_datapegawai') {
          		include 'pages/form_tmbdatapegawai.php';
        		} else if(@$_GET['page']=='edit_datapegawai') {
    					include 'pages/form_editdatapegawai.php';
    				} else if(@$_GET['page']=='tambah_dataaksesoris') {
    					include 'pages/form_tmbdataaksesoris.php';
    				} else if(@$_GET['page']=='edit_dataaksesoris') {
  		        	include 'pages/form_editdataaksesoris.php';
		        } else if(@$_GET['page']=='datasupplier') {
		            include 'pages/datasupplier.php';
		        } else if(@$_GET['page']=='tambah_datasupplier') {
		            include 'pages/form_tmbdatasupplier.php';
		        } else if(@$_GET['page']=='edit_datasupplier') {
		            include 'pages/form_editdatasupplier.php';
		        } else if(@$_GET['page']=='datapenjualan') {
		            include 'pages/datapenjualan.php';
		        } else if(@$_GET['page']=='datapenjualan_aksesoris') {
                include 'pages/datapenjualan_aksesoris.php';
            } else if(@$_GET['page']=='datapembelian') {
		            include 'pages/datapembelian.php';
		        } else if(@$_GET['page']=='entry_datapenjualan') {
		            include 'pages/form_entrypenjualan.php';
		        } else if(@$_GET['page']=='entry_datapembelian') {
		            include 'pages/form_entrypembelian.php';
		        } else if(@$_GET['page']=='form_tambahpenjualan') {
		            include 'pages/form_tambahpenjualan.php';
		        } else if(@$_GET['page']=='form_laporanpembelian') {
		            include 'pages/form_laporanpembelian.php';
		        } else if(@$_GET['page']=='peramalan') {
                if($_SESSION['posisi_peg'] == 'Administrator' || $_SESSION['posisi_peg'] == 'Administrasi' ) {
  		            include 'pages/peramalan.php';
                } else {
                }
		        } else if(@$_GET['page']=='hasil_peramalan') {
		            include 'pages/hasilperamalan.php';
		        } else if(@$_GET['page']=='riwayat_peramalan') {
		            include 'pages/riwayat_peramalan.php';
		        } else if(@$_GET['page']=='laporan') {
		            include 'pages/laporan.php';
		        } 
  			 ?>
  		</div>
          

      <div class="modal fade" id="profil_user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Profil Pegawai</h5>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <table class="tabel-profil">
                <?php 
                  $query = "SELECT * FROM pegawai WHERE id_peg = '$_SESSION[id_peg]'";
                  $sql = mysqli_query($conn, $query) or die ($conn->error);
                  $data = mysqli_fetch_array($sql);
                ?>
                <tr>
                  <th>ID</th>
                  <td> <?php echo $data['id_peg']; ?></td>
                </tr>
                <tr>
                  <th>Nama</th>
                  <td> <?php echo $data['nama_peg']; ?></td>
                </tr>
                <tr>
                  <th>Posisi</th>
                  <td> <?php echo $data['pos_peg']; 
                    if ($data['pos_peg']=="Administrator" || $data['pos_peg']=="General Manager" || $data['pos_peg']=="Direktur Utama" ) {
                  ?> 
                    <i class="fas fa-check-circle text-info"></i>
                  <?php } ?>
                  </td>
                </tr>
                <tr>
                  <th>Jenis Kelamin</th>
                  <td> <?php echo $data['jk_peg']; ?></td>
                </tr>
                <tr>
                  <th>Tanggal Lahir</th>
                  <td> <?php echo $data['lhr_peg']; ?></td>
                </tr>
                <tr>
                  <th style="vertical-align: top;">Alamat</th>
                  <td> <?php echo $data['alamat_peg']; ?></td>
                </tr>
                <tr>
                  <th>No Handphone</th>
                  <td> <?php echo $data['hp_peg']; ?></td>
                </tr>
                <tr>
                  <th>Username</th>
                  <td> <?php echo $data['username']; ?></td>
                </tr>
                <tr>
                  <th>Password</th>
                  <td> xxxxxxxx</td>
                </tr>
              </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
              <a href="?page=edit_datapegawai&id=<?php echo $_SESSION['id_peg'] ?>" class="">
              <button type="button" class="btn btn-primary btn-sm">Edit</button>
              </a>
            </div>
          </div>
        </div>
      </div>

                  </div>
                    </div>
                     <!-- Footer -->
  <footer class="footer text-center">
    <div class="container">
      <span class="text-muted">Copyright 2024 / Fikri Adam Pratama Sutanto / 10120070</span>
    </div>
  </footer>
  <!-- End of Footer -->

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="asset/bootstrap_4/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="asset/DataTables/datatables.min.js"></script>
    <script>
      var id_session = $("#id_session").text();
    	$(document).ready(function() {
		    $('#example').DataTable({
          
        });
        $('#example2').DataTable({
          
        });
        $('#example3').DataTable({
          
        });

        $('#data_penjualan').DataTable({
           lengthMenu : [[25, 50, -1], [25, 50, "All"]]
        });

        $('#riwayatperamalan').DataTable({
           lengthMenu : [[30, 50, -1], [30, 50, "All"]]
        });

        $('#pjlaksesoris').DataTable({
           lengthMenu : [[50, -1], [50, "All"]]
        });

        $('#tabel_dataaksesoris').DataTable({
          // ordering: false,
          lengthMenu : [[30, 50, 100, -1], [30, 50, 100, "All"]],
          order: [[1, "asc"]]
        });
        
		  });
      $("#tombol_keluar").click(function(){
        // alert("Log Out");
        Swal.fire({
          title: 'Apakah Anda Yakin?',
          text: 'anda akan keluar dari aplikasi',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya'
        }).then((tes) => {
          if (tes.value) {
            $.ajax({
              type: "POST",
              url: "ajax/logout.php",
              success: function(hasil) {
                window.location='./';
              }
            })  
          }
        })
      });
      function checkTime(i) {
        if (i < 10) {
          i = "0" + i;
        }
        return i;
      }
      function startTime() {
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        // add a zero in front of numbers<10
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('jam').innerHTML = h + ":" + m + ":" + s;
        t = setTimeout(function() {
          startTime()
        }, 500);
      }
      startTime();
    </script>
  </body>
</html>

<?php 
  }
?>