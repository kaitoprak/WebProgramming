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