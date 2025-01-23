<?php
session_start();

if (!isset($_SESSION['username'])) {
  header("Location: index.php"); // Redirect jika belum login
  exit;
}

// Ambil nama pengguna dari session
$username = $_SESSION['username'];


include 'koneksi.php';

$role = $_SESSION['role'];


// Cek apakah tabel mahasiswa kosong
$result = $conn->query("SELECT COUNT(*) AS total FROM mahasiswa");
$row = $result->fetch_assoc();
if ($role === 'admin'){
  echo "<div id='bar' class='sidebar'>
         <h2>Menu Dashboard</h2>
         <hr>
         <br>
         <a href='#'><i class='fas fa-home'></i><span>Beranda</span></a>
         <a onclick='openModal()' ><i class='fas fa-plus '></i><span>Tambah Data</span></a>
         <a href='#'><i class='fas fa-users'></i><span>Data Mahasiswa</span></a>
  <a href='#' onclick='return confirmDeleteAll()'><i class='fa-solid fa-trash'></i><span>Hapus semua Data</span></a>
          <a href='#'><i class='fas fa-cog'></i><span>Pengaturan</span></a>
          <a href='../logout.php' onclick='return confirmLogout()'><i class='fas fa-sign-out-alt'></i> <span>Log Out</span></a>
          <a id='delete-account-btn' href='#'><i class='fas fa-cog'></i><span>Delete Account</span></a>
        </div>";
}else{
  echo "<div id='bar' class='sidebar'>
         <h2>Menu Dashboard</h2>
         <hr>
         <br>
  <a href='#'><i class='fas fa-home'></i>&nbsp;&nbsp;<span>Beranda</span></a>
  <a href='#'><i class='fas fa-users'></i>&nbsp;&nbsp;<span>Data Mahasiswa</span></a>
          <a href='#'><i class='fas fa-cog'></i> <span>Pengaturan</span></a>
          <a href='../logout.php' onclick='return confirmLogout()'><i class='fas fa-sign-out-alt'></i> <span>Log Out</span></a>
          <a id='delete-account-btn' href='#'><i class='fa-solid fa-trash'></i><span>Delete Account</span></a>
        </div>";
}








//top content

echo "<div class='top-content'></div>";


echo "<div id='content' class=' main-content'>";

// Jika tabel kosong, tampilkan tombol Batal Hapus
if ($row['total'] == 0) {
  echo '<a href="batal_hapus.php" class="cancel-del" type="submit">Batal Hapus</a>';
         echo '<br>';
         echo '<br>';
        echo '<a href="hapus_permanen.php" onclick="return hapusPermanen()" class="cancel-del">Hapus Permanent</a>';
}


// Tampilkan tabel mahasiswa jika ada data
$query = "SELECT * FROM mahasiswa INNER JOIN jurusan ON mahasiswa.id_jurusan = jurusan.id_jurusan";
$result = $conn->query($query);

if ($result->num_rows > 0) {

  echo "<h2>Data Mahasiswa</h2>";
  echo "<div class='cta'>";
  if($role === 'admin'){
    echo "<a class='add-data' onclick='openModal()' ><i class='fas fa-plus '></i>&nbsp;&nbsp;Tambah Data</a>";
  }
 
  echo "<button class='btn-save' id='downloadPdf'><i class='fas fa-download'></i> Download PDF</button>";
  echo '<button id="exportExcel" class="btn-save"><i class="fas fa-file-excel"></i> Export to Excel</button>';
  echo "</div>";
  echo "<div class='table-responsive'>";
  echo "<table id='myTable' class='display table table-striped table-bordered bg-primary'>";
  echo "<thead>";
  if($role === 'admin'){
  echo "<tr>
        <th>Nama</th>
        <th>NIM</th>
        <th>Email</th>
        <th>Nomor</th>
        <th>Jurusan</th>
        <th>Aksi</th> 
      </tr>";
} else {
  echo "<tr>
        <th>Nama</th>
        <th>NIM</th>
        <th>Email</th>
        <th>Nomor</th>
        <th>Jurusan</th>
      </tr>";
}
  echo "</thead>";
  echo "<tbody>";

  while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['nama'] . "</td>";
    echo "<td>" . $row['nim'] . "</td>";
    echo "<td>" . $row['email'] . "</td>";
    echo "<td>" . $row['nomor'] . "</td>";
    echo "<td>" . $row['nama_jurusan'] . "</td>";

    // Kolom Aksi dengan tautan Edit dan Hapus

if($role === 'admin'){
  echo "<td>
 <a href='#' 
class='btn btn-sm btn-success  editButton' onclick='openEditModal()'
data-id='{$row['id_mhs']}'
data-nama='{$row['nama']}'
data-nim='{$row['nim']}'
data-email='{$row['email']}'
data-nomor='{$row['nomor']}'
data-jurusan='{$row['id_jurusan']}'
><i class='fas fa-edit'></i> <span>Edit</span></a>

<a href='hapus.php?id_mhs=" . $row['id_mhs'] . "' onclick='return konfir(event, this)' class='btn btn-sm btn-danger'><i class='fas fa-trash'></i> Hapus</a>

            </td>";
}

    echo "</tr>";
  }
  echo "<tbody>";
  echo "</table>";
  echo "</div>";
} else {
  echo '<div class="nodata-img d-flex justify-content-center"><img  src="../style/no data.jpg" width="300px" height="300px alt=""></div>';
  $username = $_SESSION['username'];
}


// mengambil data jurusan dari database
$jurusan_query = "SELECT id_jurusan, nama_jurusan FROM jurusan";
$jurusan_result = $conn->query($jurusan_query);

echo "<div id='modalForm' class='modal' >
        <span class='close-btn' onclick='closeModal()'>×</span>
        <div id='mediaContent' class='form-card'>
          <h2>Tambah Data</h2>
          <form  id='tambahForm' action='tambah_proses.php' method='POST'>
          <input type='hidden' name='id_mhs'>
              <div class='input-group'>
                  <i class='fas fa-user'></i>
                  <input type='text' name='nama' placeholder='Nama' required>
              </div>
              <div class='input-group'>
                  <i class='fas fa-id-card'></i>
                  <input type='number' name='nim' placeholder='NIM' required>
              </div>
              <div class='input-group'>
                  <i class='fas fa-envelope'></i>
                  <input type='email' name='email' placeholder='Email' required>
              </div>
              <div class='input-group'>
                  <i class='fas fa-phone'></i>
                  <input id='nomor' type='number' name='nomor' placeholder='Nomor' required>
              </div>
              <div class='input-group'>
                  <i class='fas fa-graduation-cap'></i>
                  <select name='id_jurusan' required>
                      <option value='' disabled selected>Pilih Jurusan</option>";

if ($jurusan_result->num_rows > 0) {
  while ($row = $jurusan_result->fetch_assoc()) {
    echo "<option value='" . $row['id_jurusan'] . "'>" . $row['nama_jurusan'] . "</option>";
  }
}

echo "          </select>
              </div>
              <button class='btn-tambah' type='submit'>Tambah</button>
          </form>
      </div>
</div>";

echo "</div>";

// sappaan user

$username = $_SESSION['username'];
?>
<script>
  const username = "<?php echo $username; ?>";
</script>




<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Data Mahasiswa</title>
  <link rel="stylesheet" href="../style/index.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
    rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
 <div id="greeting" class="greeting-text text-wrap">
</div>

<input onclick="toggleSidebar()" style="display: none;" class="toggle-checkbox" id="toggle" type="checkbox" />
<label  class="hamburger" for="toggle">
  <div class="bar"></div>
  <div class="bar"></div>
  <div class="bar"></div>
</label>




<div id="uploadModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="uploadModalLabel">Upload Foto Avatar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="upload_avatar.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="id_user" value="<?php echo $_SESSION['id_user']; ?>">
          <div class="form-group">
            <input type="file" name="avatar" class="form-control-file" required>
          </div>
          <button type="submit" class="btn btn-primary">Upload Avatar</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </form>
      </div>
    </div>
  </div>
</div>







<!-- tambah data jika admin -->
  

  <!-- Modal -->
  <?php
  include 'koneksi.php';
  $query = "SELECT id_jurusan, nama_jurusan FROM jurusan";
  $result = $conn->query($query);
  ?>
  <div id='editModal' class='editModal' style='display: none;'>
    <span class='close-btnEdit' onclick='closeModalEdit()'>×</span>
    <div id='mediaContent' class='form-card'>
      <h2>Edit Data</h2>
      <form id='editForm12' action='edit_proses.php' method='POST'>
        <input type='hidden' name='id_mhs' id="editId">
        <div class='input-group'>
          <i class='fas fa-user'></i>
          <input type='text' id="editNama" name='nama' placeholder='Nama' >
        </div>
        <div class='input-group'>
          <i class='fas fa-id-card'></i>
          <input type='text' id="editNim" name='nim' placeholder='NIM' >
        </div>
        <div class='input-group'>
          <i class='fas fa-envelope'></i>
          <input type='text' id="editEmail" name='email' placeholder='Email' >
        </div>
        <div class='input-group'>
          <i class='fas fa-phone'></i>
          <input type='text' id="editNomor" name='nomor' placeholder='Nomor' >
        </div>
        <div class='input-group'>
          <i class='fas fa-graduation-cap'></i>
          <input type='number' id="editJurusan" name='id_jurusan' placeholder='Jurusan' >
        </div>
        <button class='btn-tambah' type='submit'>Simpan</button>
      </form>
    </div>
  </div>

  


  <script>




    function openEditModal() {
      document.getElementById('editModal').style.display = 'block';
    }

    function closeModalEdit() {
      document.getElementById('editModal').style.display = 'none';
    }

    // Inisialisasi DataTables
    $(document).ready(function () {
      $('#myTable').DataTable({
        "paging": false,
        "searching": true,
        "info": true,
        "lengthChange": true,
        "pageLength": 10

      });
    });

  </script>


  <script src="script.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

</body>

</html>