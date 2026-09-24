# Tugas Mandiri Modul 6 — Perancangan ERD E-Library Kampus

## 1. Analisis Skenario

Sistem E-Library Kampus merupakan sistem basis data relasional yang digunakan
untuk mencatat data mahasiswa, buku, penerbit, serta transaksi peminjaman dan
pengembalian buku.

Sistem harus dapat menyimpan informasi mengenai mahasiswa yang melakukan
peminjaman, buku yang dipinjam, penerbit buku, tanggal peminjaman, batas waktu
pengembalian, dan tanggal pengembalian aktual.

## 2. Entitas

Berdasarkan kebutuhan sistem, terdapat empat entitas utama:

1. **Mahasiswa**
   - Menyimpan data mahasiswa yang dapat melakukan peminjaman buku.

2. **Buku**
   - Menyimpan informasi mengenai buku yang tersedia di perpustakaan.

3. **Penerbit**
   - Menyimpan informasi mengenai penerbit buku.

4. **Transaksi Peminjaman**
   - Menyimpan riwayat peminjaman dan pengembalian buku oleh mahasiswa.

## 3. Atribut dan Kunci

### 3.1 Mahasiswa

| Atribut | Keterangan | Kunci |
|---|---|---|
| `nim` | Nomor induk mahasiswa | PK |
| `nama` | Nama mahasiswa | - |
| `email` | Email mahasiswa | - |
| `program_studi` | Program studi mahasiswa | - |

### 3.2 Penerbit

| Atribut | Keterangan | Kunci |
|---|---|---|
| `id_penerbit` | Identitas unik penerbit | PK |
| `nama_penerbit` | Nama penerbit | - |
| `alamat` | Alamat penerbit | - |

### 3.3 Buku

| Atribut | Keterangan | Kunci |
|---|---|---|
| `id_buku` | Identitas unik buku | PK |
| `judul` | Judul buku | - |
| `isbn` | Nomor ISBN buku | - |
| `tahun_terbit` | Tahun buku diterbitkan | - |
| `id_penerbit` | Identitas penerbit buku | FK |

### 3.4 Transaksi Peminjaman

| Atribut | Keterangan | Kunci |
|---|---|---|
| `id_transaksi` | Identitas unik transaksi | PK |
| `nim` | Mahasiswa yang melakukan peminjaman | FK |
| `id_buku` | Buku yang dipinjam | FK |
| `tanggal_peminjaman` | Tanggal buku dipinjam | - |
| `tanggal_jatuh_tempo` | Batas waktu pengembalian | - |
| `tanggal_pengembalian` | Tanggal aktual buku dikembalikan | - |

## 4. Relasi Antarentitas

- Satu mahasiswa dapat melakukan banyak transaksi peminjaman.
- Setiap transaksi peminjaman dilakukan oleh satu mahasiswa.
- Satu buku dapat tercatat dalam banyak transaksi peminjaman sepanjang waktu.
- Setiap transaksi peminjaman berkaitan dengan satu buku.
- Satu penerbit dapat menerbitkan banyak buku.
- Setiap buku memiliki satu penerbit.

Dengan demikian, kardinalitas relasi adalah:

```text
MAHASISWA 1 : N TRANSAKSI_PEMINJAMAN
BUKU      1 : N TRANSAKSI_PEMINJAMAN
PENERBIT  1 : N BUKU

## 5. Primary Key dan Foreign Key

| Tabel | Primary Key | Foreign Key |
|---|---|---|
| `mahasiswa` | `nim` | - |
| `penerbit` | `id_penerbit` | - |
| `buku` | `id_buku` | `id_penerbit` → `penerbit.id_penerbit` |
| `transaksi_peminjaman` | `id_transaksi` | `nim` → `mahasiswa.nim`, `id_buku` → `buku.id_buku` |

Primary key digunakan sebagai identitas unik setiap baris pada tabel.
Foreign key digunakan untuk menghubungkan tabel yang memiliki relasi.

Pada tabel `buku`, atribut `id_penerbit` menjadi foreign key yang mengacu
kepada `penerbit.id_penerbit`.

Pada tabel `transaksi_peminjaman`, atribut `nim` menjadi foreign key yang
mengacu kepada `mahasiswa.nim`, sedangkan `id_buku` menjadi foreign key yang
mengacu kepada `buku.id_buku`.

## 6. Normalisasi

### 6.1 Unnormalized Form (UNF)

Pada kondisi awal, data mahasiswa, buku, penerbit, dan transaksi peminjaman
diasumsikan masih disimpan dalam satu struktur data.

Contoh struktur data awal:

| ID Transaksi | NIM | Nama Mahasiswa | Email | Program Studi | ID Buku | Judul Buku | ISBN | Tahun Terbit | ID Penerbit | Nama Penerbit | Alamat Penerbit | Tanggal Peminjaman | Tanggal Jatuh Tempo | Tanggal Pengembalian |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| TR001 | 13001 | Andi | andi@kampus.ac.id | Informatika | B001 | Basis Data | 978-001 | 2024 | P001 | Informatika Press | Makassar | 2026-09-01 | 2026-09-08 | 2026-09-07 |
| TR002 | 13001 | Andi | andi@kampus.ac.id | Informatika | B002 | Algoritma | 978-002 | 2023 | P002 | Tech Publisher | Jakarta | 2026-09-03 | 2026-09-10 | - |
| TR003 | 13002 | Budi | budi@kampus.ac.id | Sistem Informasi | B001 | Basis Data | 978-001 | 2024 | P001 | Informatika Press | Makassar | 2026-09-04 | 2026-09-11 | - |

Pada bentuk UNF, data dari beberapa entitas masih berada dalam satu tabel.
Akibatnya, informasi mahasiswa, buku, dan penerbit dapat berulang pada
beberapa transaksi.

Contohnya, data buku `B001` dan penerbit `P001` muncul kembali ketika buku
yang sama dipinjam oleh mahasiswa yang berbeda.

Kondisi tersebut dapat menimbulkan:

- **Update anomaly**, yaitu perubahan data harus dilakukan pada beberapa baris.
- **Insert anomaly**, yaitu data buku atau penerbit baru sulit disimpan jika
  belum memiliki transaksi.
- **Delete anomaly**, yaitu penghapusan transaksi tertentu berpotensi
  menghilangkan satu-satunya informasi mengenai buku atau penerbit.

### 6.2 First Normal Form (1NF)

Untuk memenuhi 1NF, setiap atribut harus memiliki nilai atomik dan tidak boleh
terdapat repeating group atau kumpulan nilai dalam satu atribut.

Setiap baris pada tabel merepresentasikan satu transaksi peminjaman satu buku.
Dengan demikian, atribut pada setiap baris memiliki satu nilai.

Struktur tabel pada tahap 1NF masih berupa satu tabel:

```text
PEMINJAMAN(
    id_transaksi,
    nim,
    nama_mahasiswa,
    email,
    program_studi,
    id_buku,
    judul_buku,
    isbn,
    tahun_terbit,
    id_penerbit,
    nama_penerbit,
    alamat_penerbit,
    tanggal_peminjaman,
    tanggal_jatuh_tempo,
    tanggal_pengembalian
)

Primary key pada tahap ini adalah `id_transaksi`, karena setiap transaksi
peminjaman memiliki identitas yang unik.

Walaupun sudah memenuhi 1NF, tabel masih mengandung redundansi. Sebagai
contoh, data mahasiswa dengan NIM `13001` muncul pada lebih dari satu
transaksi. Data buku `B001` dan penerbit `P001` juga muncul kembali ketika
buku yang sama dipinjam oleh mahasiswa lain.

Dengan demikian, 1NF belum menghilangkan seluruh redundansi dan masih
diperlukan proses normalisasi ke bentuk berikutnya.

### 6.3 Second Normal Form (2NF)

2NF mensyaratkan bahwa tabel telah memenuhi 1NF dan tidak memiliki
ketergantungan parsial, yaitu atribut non-key hanya boleh bergantung pada
sebagian dari primary key.

Pada tabel 1NF, primary key yang digunakan adalah `id_transaksi` dan hanya
terdiri dari satu atribut. Karena primary key tidak berupa gabungan beberapa
atribut, maka tidak mungkin terjadi ketergantungan parsial. Dengan demikian,
tabel 1NF secara teori telah memenuhi 2NF.

Namun, tabel masih mengandung redundansi karena informasi buku dan penerbit
disimpan bersama dengan data transaksi. Untuk menghasilkan struktur yang
lebih terorganisasi dan mempersiapkan proses menuju 3NF, atribut yang
berkaitan dengan buku dipisahkan dari atribut transaksi.

Hasil pemisahan menjadi:

```text
TRANSAKSI_PEMINJAMAN(
    id_transaksi,
    nim,
    id_buku,
    tanggal_peminjaman,
    tanggal_jatuh_tempo,
    tanggal_pengembalian
)

BUKU(
    id_buku,
    judul,
    isbn,
    tahun_terbit,
    id_penerbit,
    nama_penerbit,
    alamat_penerbit
)

## 7. Rancangan Tabel Akhir

Setelah proses normalisasi hingga 3NF, sistem terdiri dari empat tabel:
`mahasiswa`, `penerbit`, `buku`, dan `transaksi_peminjaman`.

### 7.1 Tabel `mahasiswa`

| Kolom | Tipe Data | Constraint | Keterangan |
|---|---|---|---|
| `nim` | `VARCHAR(20)` | PK, NOT NULL | Nomor induk mahasiswa |
| `nama` | `VARCHAR(100)` | NOT NULL | Nama mahasiswa |
| `email` | `VARCHAR(150)` | NOT NULL, UNIQUE | Email mahasiswa |
| `program_studi` | `VARCHAR(100)` | NOT NULL | Program studi mahasiswa |

### 7.2 Tabel `penerbit`

| Kolom | Tipe Data | Constraint | Keterangan |
|---|---|---|---|
| `id_penerbit` | `INT` | PK, NOT NULL | Identitas unik penerbit |
| `nama_penerbit` | `VARCHAR(150)` | NOT NULL | Nama penerbit |
| `alamat` | `VARCHAR(255)` | NOT NULL | Alamat penerbit |

### 7.3 Tabel `buku`

| Kolom | Tipe Data | Constraint | Keterangan |
|---|---|---|---|
| `id_buku` | `INT` | PK, NOT NULL | Identitas unik buku |
| `judul` | `VARCHAR(200)` | NOT NULL | Judul buku |
| `isbn` | `VARCHAR(20)` | NOT NULL, UNIQUE | ISBN buku |
| `tahun_terbit` | `SMALLINT` | NOT NULL | Tahun buku diterbitkan |
| `id_penerbit` | `INT` | FK, NOT NULL | Penerbit buku |

Foreign key `id_penerbit` mengacu kepada `penerbit.id_penerbit`.

### 7.4 Tabel `transaksi_peminjaman`

| Kolom | Tipe Data | Constraint | Keterangan |
|---|---|---|---|
| `id_transaksi` | `INT` | PK, NOT NULL | Identitas unik transaksi |
| `nim` | `VARCHAR(20)` | FK, NOT NULL | Mahasiswa yang meminjam |
| `id_buku` | `INT` | FK, NOT NULL | Buku yang dipinjam |
| `tanggal_peminjaman` | `DATE` | NOT NULL | Tanggal peminjaman |
| `tanggal_jatuh_tempo` | `DATE` | NOT NULL | Batas waktu pengembalian |
| `tanggal_pengembalian` | `DATE` | NULL | Tanggal aktual pengembalian |

Foreign key `nim` mengacu kepada `mahasiswa.nim`.

Foreign key `id_buku` mengacu kepada `buku.id_buku`.

`tanggal_pengembalian` diperbolehkan bernilai `NULL` karena buku yang masih
dipinjam belum memiliki tanggal pengembalian.

### 7.5 Ringkasan Relasi

| Relasi | Kardinalitas | Foreign Key |
|---|---|---|
| Mahasiswa → Transaksi Peminjaman | 1 : N | `transaksi_peminjaman.nim` |
| Buku → Transaksi Peminjaman | 1 : N | `transaksi_peminjaman.id_buku` |
| Penerbit → Buku | 1 : N | `buku.id_penerbit` |

## 8. ERD Logis

ERD berikut menggambarkan hubungan antarentitas beserta primary key dan
foreign key yang telah ditentukan.

```mermaid
erDiagram
    MAHASISWA ||--o{ TRANSAKSI_PEMINJAMAN : melakukan
    BUKU ||--o{ TRANSAKSI_PEMINJAMAN : dipinjam
    PENERBIT ||--o{ BUKU : menerbitkan

    MAHASISWA {
        VARCHAR(20) nim PK
        VARCHAR(100) nama
        VARCHAR(150) email UK
        VARCHAR(100) program_studi
    }

    PENERBIT {
        INT id_penerbit PK
        VARCHAR(150) nama_penerbit
        VARCHAR(255) alamat
    }

    BUKU {
        INT id_buku PK
        VARCHAR(200) judul
        VARCHAR(20) isbn UK
        SMALLINT tahun_terbit
        INT id_penerbit FK
    }

    TRANSAKSI_PEMINJAMAN {
        INT id_transaksi PK
        VARCHAR(20) nim FK
        INT id_buku FK
        DATE tanggal_peminjaman
        DATE tanggal_jatuh_tempo
        DATE tanggal_pengembalian
    }