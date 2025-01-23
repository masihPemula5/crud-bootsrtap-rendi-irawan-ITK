
function toggleSidebar() {
    const sidebar = document.getElementById("bar");
    const content = document.getElementById("content");
    sidebar.classList.toggle("active");
    content.classList.toggle("active");
  }


const modal = document.getElementById('modalForm');

function openModal() {
    modal.classList.add('active');
}
function closeModal() {
    modal.classList.add('animate__animated', 'animate__fadeOut');
    setTimeout(() => {
        modal.classList.remove('active', 'animate__animated', 'animate__fadeOut');
    }, 500);
}





window.onclick = function (event) {
    if (event.target === modal) {
        closeModal();
    }
};



// modal konfirm hapus
function konfir(event, elem) {
    event.preventDefault();
    Swal.fire({
      title: 'Apakah Anda yakin ingin menghapus data ini?',
      text: 'Data yang dihapus tidak dapat dikembalikan!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#1b76fd',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, hapus data!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        // Misalnya proses penghapusan berhasil
        Swal.fire({
    title: 'Berhasil!',
    text: 'Data berhasil dihapus.',
    icon: 'success',
    showConfirmButton: false,
    timer: 1200
        }).then(() => {
          // Arahkan ke halaman yang diperlukan setelah alert sukses
          window.location.href = elem.href;
        });
      }
    });
  }


//konfirm log out

function confirmLogout() {
    event.preventDefault();
    Swal.fire({
      title: 'Yakin ingin logout?',
      text: 'Anda akan keluar dari sistem!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#1b76fd',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, logout!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = '../logout.php';
      }
    });
  }

  // konfirm hapus semua

  function confirmDeleteAll() {
    event.preventDefault();
    Swal.fire({
      title: 'Yakin bre mau hapus semua data?',
      text: 'Semua data akan dihapus tetapi masih bisa di kembalikan',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#1b76fd',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Hapus aja bre!',
      cancelButtonText: 'Gajadi'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = 'hapus_semua.php';
      }
    });
  }
     window.onload = function() {
              const urlParams = new URLSearchParams(window.location.search);
              if (urlParams.get('success') === 'true') {
                  Swal.fire({
                      icon: 'success',
                      title: 'Berhasil!',
                      text: 'Semua data telah berhasil dihapus.',
                      showConfirmButton: false,
                      timer: 1000
                  });
              }
          };

          //hapus semua permanen

          function hapusPermanen() {
            event.preventDefault();
            Swal.fire({
              title: 'Yakin Ingin Menghapus permanen?',
              text: 'Semua data akan dihapus Secara Permanen Dan Tidak Dapat Dikembalikan',
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#1b76fd',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Ya, Hapus aja bre!',
              cancelButtonText: 'Gajadi'
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.href = 'hapus_permanen.php';
              }
            });
          }


// SweetAlert Confirmation for Delete Account
const deleteButton = document.getElementById('delete-account-btn');
if (deleteButton) {
    deleteButton.addEventListener('click', function () {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Akun Anda akan dihapus secara permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'delete_account.php';
            }
        });
    });
}




//edit modal

document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("editModal");
    const closeModal = document.getElementById("closeModal");


    const editButtons = document.querySelectorAll(".editButton");
    editButtons.forEach((button) => {
        button.addEventListener("click", function () {

            document.getElementById("editNama").value = this.getAttribute("data-nama");
            document.getElementById("editNim").value = this.getAttribute("data-nim");
            document.getElementById("editEmail").value = this.getAttribute("data-email");
            document.getElementById("editNomor").value = this.getAttribute("data-nomor");
            document.getElementById("editJurusan").value = this.getAttribute("data-jurusan");
            document.getElementById("editId").value = this.getAttribute("data-id");

        });
    });

   
    overlay.addEventListener("click", function () {
        modal.style.display = "none";
        overlay.style.display = "none";
    });
});




// fitur download tabel
document.getElementById("downloadPdf").addEventListener("click", () => {
    const { jsPDF } = window.jspdf;

    // Inisialisasi dokumen PDF
    const doc = new jsPDF();

    // Tambahkan judul
    doc.text("Data Mahasiswa", 14, 10);

    // Ambil tabel dari ID
    const table = document.getElementById("myTable");

    // Ambil data tabel secara manual, tapi abaikan kolom "Aksi"
    const rows = [];
    const headers = [];

    // Ambil header, kecuali kolom terakhir (Aksi)
    table.querySelectorAll("thead tr th").forEach((th, index) => {
        if (index < table.querySelectorAll("thead tr th").length - 1) { // Skip kolom terakhir
            headers.push(th.textContent.trim());
        }
    });

    // Ambil setiap baris data, kecuali kolom terakhir
    table.querySelectorAll("tbody tr").forEach((tr) => {
        const row = [];
        const cells = tr.querySelectorAll("td");
        cells.forEach((td, index) => {
            if (index < cells.length - 1) { // Skip kolom terakhir
                row.push(td.textContent.trim());
            }
        });
        rows.push(row);
    });

    // Gunakan autoTable untuk menambahkan tabel ke PDF
    doc.autoTable({
        head: [headers],
        body: rows,
        startY: 20, // Mulai di bawah judul
        styles: { fontSize: 10, cellPadding: 3 },
       headStyles: { fillColor: [0, 123, 255], textColor: [255, 255, 255] },
    });

    // Simpan PDF
    doc.save("data-mahasiswa.pdf");
});


// export file
document.getElementById("exportExcel").addEventListener("click", function () {
    let table = document.getElementById("myTable");
    let rows = table.rows;

    let csvContent = "data:text/csv;charset=utf-8,";
    let headers = [];
    table.querySelectorAll("thead tr th").forEach((th, index) => {
        if (index < table.querySelectorAll("thead tr th").length - 1) { 
            headers.push(th.textContent.trim());
        }
    });
    csvContent += headers.join(",") + "\n";

    for (let i = 1; i < rows.length; i++) {
        let row = [], cols = rows[i].cells;
        for (let j = 0; j < cols.length - 1; j++) {
            row.push(cols[j].innerText);
        }
        csvContent += row.join(",") + "\n";
    }

    let encodedUri = encodeURI(csvContent);
    let link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "Data_Mahasiswa.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
});


//import file








//sapaan user

// Fungsi untuk mendapatkan sapaan berdasarkan waktu
function getStyledGreeting(username) {
    const now = new Date();
    const hours = now.getHours();
    let greeting = "";

    if (hours >= 6 && hours < 9) {
        greeting = `Selamat pagi, ${username}!`;
    } else if (hours >= 9 && hours < 16) {
        greeting = `Selamat siang, ${username}!`;
    } else if (hours >= 16 && hours < 18) {
        greeting = `Selamat sore, ${username}!`;
    } else {
        greeting = `Selamat malam, ${username}!`;
        
    }

    return greeting;
}

// Tampilkan sapaan
document.getElementById("greeting").textContent = getStyledGreeting(username);

