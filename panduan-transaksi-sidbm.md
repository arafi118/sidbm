# Panduan Transaksi SI DBM

---

## Daftar Isi

### Daftar Cara Transaksi Jurnal Umum

| No | Jenis Transaksi |
|----|----------------|
| 1 | Input Transaksi Tarik dan Setor Bank |
| 2 | Input Transaksi Bunga, Pajak dan Adm Bank |
| 3 | Input Pembelian ATI (Tanah, Gedung, Kendaraan, Inventaris) |
| 4 | Input Aset Tak Berwujud (Pendirian, Lisensi, Sewa, Asuransi) |
| 5 | Input Transaksi Utang Laba (Laba Masyarakat, Laba Desa, Laba Penyerta Modal) |
| 6 | Input Pinjaman Pihak ke 3 |
| 7 | Input Penyertaan Modal Desa |
| 8 | Input Taksiran PPh dan Pembayaran Pajak |
| 9 | Input Transaksi Beban Penyusutan (Gedung, Kendaraan, Inventaris) |
| 10 | Input Transaksi Beban Amortisasi (Pendirian, Lisensi, Sewa, Asuransi) |
| 11 | Input Transaksi antar Kas / antar Bank |
| 12 | Input Transaksi Operasional (Akun Beban) |
| 13 | Input Transaksi Investasi unit Usaha |
| 14 | Mengubah Status Kondisi Nama Barang Daftar ATI (Penghapusan) |
| 15 | Transaksi Penyisihan Cadangan Penghapusan (CKP) |
| 16 | Input Utang Bonus Prestasi Kerja |
| 17 | Input Transaksi Pembelian Barang Secara Tempo |

### Daftar Cara Transaksi Pencairan & Angsuran

| No | Jenis Transaksi |
|----|----------------|
| 1 | Transaksi Pencairan Pinjaman |
| 2 | Transaksi Angsuran Pinjaman |

---

## Jenis Transaksi

| No | Jenis Transaksi |
|----|----------------|
| 1 | Aset Masuk |
| 2 | Aset Keluar |
| 3 | Pemindahan Saldo / Aset |

---

## Kode Akun

### 1. Aset

#### 1.1 Aset Lancar

| Kode Akun | Nama Akun |
|-----------|-----------|
| 1.1.01.01 | Kas Tunai |
| 1.1.01.02 | Kas Kecil |
| 1.1.01.03 | Kas di Bank Operasional |
| 1.1.01.04 | Kas di Bank SPP |
| 1.1.01.05 | Kas di Bank Bumdesma |
| 1.1.02.01 | Deposito |
| 1.1.02.02 | Saham |
| 1.1.02.03 | Obligasi |
| 1.1.03.01 | Piutang Masyarakat SPP (Pokok) |
| 1.1.03.02 | Piutang Masyarakat UEP (Pokok) |
| 1.1.03.03 | Piutang Lembaga Lain (Pokok) |
| 1.1.03.04 | Piutang Jasa SPP |
| 1.1.03.05 | Piutang Jasa UEP |
| 1.1.03.06 | Piutang Jasa Lembaga Lain |
| 1.1.03.07 | Piutang Dividen |
| 1.1.03.08 | Piutang lain |
| 1.1.04.01 | Cadangan Kerugian Piutang Pokok SPP |
| 1.1.04.02 | Cadangan Kerugian Piutang Pokok UEP |
| 1.1.04.03 | Cadangan Kerugian Piutang Pokok Lembaga Lain |
| 1.1.04.04 | Cadangan Kerugian Piutang Jasa SPP |
| 1.1.04.05 | Cadangan Kerugian Piutang Jasa UEP |
| 1.1.04.06 | Cadangan Kerugian Piutang Jasa Lembaga Lain |
| 1.1.04.07 | Cadangan Kerugian Piutang Lain |
| 1.1.05.01 | Rekening antar Kantor (RK unit Usaha 1) |
| 1.1.05.02 | Rekening antar Kantor (RK unit Usaha 2) |
| 1.1.05.03 | Rekening antar Kantor (RK unit Usaha 3) |
| 1.1.06.01 | Investasi unit Usaha 1 |
| 1.1.06.02 | Investasi unit Usaha 2 |
| 1.1.06.03 | Investasi unit Usaha 3 |

#### 1.2 Aset Tetap

| Kode Akun | Nama Akun |
|-----------|-----------|
| 1.2.01.01 | Tanah |
| 1.2.01.02 | Gedung & Bangunan |
| 1.2.01.03 | Kendaraan dan Mesin |
| 1.2.01.04 | Inventaris/Peralatan |
| 1.2.02.01 | Akumulasi penyusutan Gedung dan Bangunan |
| 1.2.02.02 | Akumulasi penyusutan Kendaraan dan Mesin |
| 1.2.02.03 | Akumulasi penyusutan Inventaris/Peralatan |
| 1.2.03.01 | Biaya Pendirian Organisasi |
| 1.2.03.02 | Lisensi |
| 1.2.03.03 | Sewa dibayar dimuka |
| 1.2.03.04 | Asuransi dibayar dimuka |
| 1.2.04.01 | Akumulasi Amortisasi Biaya Pendirian Organisasi |
| 1.2.04.02 | Akumulasi Amortisasi Lisensi |
| 1.2.04.03 | Akumulasi Amortisasi Sewa dibayar dimuka |
| 1.2.04.04 | Akumulasi Amortisasi Asuransi dibayar dimuka |
| 1.2.05.01 | Konstruksi Dalam Pengerjaan dan Uang Muka |

#### 1.3 Aset Lain-lain

| Kode Akun | Nama Akun |
|-----------|-----------|
| 1.3.01.01 | Aset Lain-lain |

### 2. Kewajiban

#### 2.1 Kewajiban Jangka Pendek

| Kode Akun | Nama Akun |
|-----------|-----------|
| 2.1.01.01 | Utang Bank 1 |
| 2.1.01.02 | Utang Bank 2 |
| 2.1.02.01 | Utang Gaji |
| 2.1.02.02 | Utang Honor |
| 2.1.02.03 | Utang Tunjangan |
| 2.1.02.04 | Utang Bonus Prestasi Kerja |
| 2.1.02.05 | Utang Biaya Operasional lainnya |
| 2.1.03.01 | Utang Pajak |
| 2.1.04.01 | Utang Laba Bagian Masyarakat |
| 2.1.04.02 | Utang Laba Bagian Desa |
| 2.1.04.03 | Utang Laba Bagian Penyerta Modal |
| 2.1.05.01 | Utang Jangka Pendek Lainnya |

#### 2.2 Kewajiban Jangka Panjang

| Kode Akun | Nama Akun |
|-----------|-----------|
| 2.2.01.01 | Utang Bank 1 |
| 2.2.01.02 | Utang Bank 2 |
| 2.2.02.01 | Utang Jangka Panjang Lainnya |

### 3. Modal

| Kode Akun | Nama Akun |
|-----------|-----------|
| 3.1.01.01 | Modal Masyarakat Desa (Eks. PNPM) |
| 3.1.01.02 | Modal Desa Pendiri |
| 3.1.01.03 | Modal Masyarakat |
| 3.1.02.01 | Modal Lain-lain |
| 3.2.01.01 | Laba Ditahan s/d Tahun lalu |
| 3.2.02.01 | Laba/Rugi Tahun Berjalan |

### 4. Pendapatan

| Kode Akun | Nama Akun |
|-----------|-----------|
| 4.1.01.01 | Pendapatan Jasa Piutang SPP |
| 4.1.01.02 | Pendapatan Jasa Piutang UEP |
| 4.1.01.03 | Pendapatan Jasa Piutang Lembaga Lain |
| 4.1.01.04 | Pendapatan Denda Piutang SPP |
| 4.1.01.05 | Pendapatan Denda Piutang UEP |
| 4.1.01.06 | Pendapatan Denda Piutang Lembaga Lain |
| 4.1.02.01 | Pendapatan Dividen Unit Usaha 1 |
| 4.1.02.02 | Pendapatan Dividen Unit Usaha 2 |
| 4.1.02.03 | Pendapatan Dividen Unit Usaha 3 |
| 4.1.02.99 | Pendapatan Usaha Lainnya |
| 4.2.01.01 | Pendapatan Bunga Bank |
| 4.2.01.02 | Pendapatan Bunga Deposito |
| 4.2.01.03 | Pendapatan Surat Berharga |
| 4.2.01.04 | Pertambahan Nilai Penjualan Aset |
| 4.2.01.05 | Pendapatan Hadiah |
| 4.2.01.06 | Pendapatan Hibah |
| 4.2.01.07 | Pendapatan Non Usaha Lainnya |
| 4.3.01.01 | Pendapatan revaluasi Aset |
| 4.3.01.02 | Pendapatan revaluasi Saham |
| 4.3.01.03 | Pendapatan lain-lain Lainnya |

### 5. Beban

| Kode Akun | Nama Akun |
|-----------|-----------|
| 5.1.01.01 | Beban Gaji PO |
| 5.1.01.02 | Beban Gaji Pegawai |
| 5.1.01.03 | Beban Honor Verifikator |
| 5.1.01.04 | Beban Honor Pengawas |
| 5.1.01.05 | Beban Honor Penasihat |
| 5.1.01.06 | Beban Honor Tim Penanganan Masalah |
| 5.1.01.07 | Beban Honor Tim Pendanaan |
| 5.1.01.08 | Beban Honor Petugas Keamanan dan Kebersihan |
| 5.1.02.01 | Beban Tunjangan Jabatan |
| 5.1.02.02 | Beban Tunjangan Komunikasi |
| 5.1.02.03 | Beban Tunjangan Hari Raya |
| 5.1.02.04 | Beban Tunjangan Asuransi/BPJS |
| 5.1.02.05 | Bonus Prestasi Kerja |
| 5.1.03.01 | Beban Administrasi dan Umum |
| 5.1.03.02 | Beban Listrik |
| 5.1.03.03 | Beban Internet |
| 5.1.03.04 | Beban Pemeliharaan & Perbaikan Aset |
| 5.1.04.01 | Konsumsi Kantor dan Tamu |
| 5.1.04.02 | Beban Iuran Organisasi |
| 5.1.04.03 | Beban Biaya Audit |
| 5.1.05.01 | Beban Rapat / MAD |
| 5.1.05.02 | Beban Peningkatan Kapasitas |
| 5.1.05.03 | Beban Pembinaan Kelompok Bermasalah |
| 5.1.06.01 | Beban Perjalanan Dinas |
| 5.1.06.02 | Beban Transportasi |
| 5.1.07.01 | Beban Penyisihan Kerugian Piutang SPP |
| 5.1.07.02 | Beban Penyisihan Kerugian Piutang UEP |
| 5.1.07.03 | Beban Penyisihan Kerugian Piutang Lembaga Lain |
| 5.1.07.04 | Beban Penyisihan Kerugian Piutang Jasa SPP |
| 5.1.07.05 | Beban Penyisihan Kerugian Piutang Jasa UEP |
| 5.1.07.06 | Beban Penyisihan Kerugian Piutang Jasa Lembaga Lain |
| 5.1.07.07 | Beban Penyisihan Kerugian Piutang Lain |
| 5.1.07.08 | Beban Penyusutan Gedung dan Bangunan |
| 5.1.07.09 | Beban Penyusutan Kendaraan & Mesin |
| 5.1.07.10 | Beban Penyusutan Inventaris |
| 5.1.07.11 | Beban Amortisasi Biaya Pendirian Organisasi |
| 5.1.07.12 | Beban Amortisasi Lisensi |
| 5.1.07.13 | Beban Amortisasi Sewa dibayar dimuka |
| 5.1.07.14 | Beban Amortisasi Asuransi dibayar dimuka |
| 5.1.08.01 | Beban Bunga Utang Bank |
| 5.1.09.01 | Beban Usaha Lainnya |
| 5.2.01.01 | Beban IPTW |
| 5.2.01.02 | Beban Seragam PO dan Pegawai |
| 5.2.01.03 | Beban Spanduk/Papan Nama |
| 5.2.01.04 | Beban Pemasaran lainnya |
| 5.3.01.01 | Beban Pajak Bank |
| 5.3.01.02 | Beban Administrasi Bank |
| 5.3.02.01 | Beban Penghapusan Aset Tetap |
| 5.3.03.01 | Beban Sumbangan Kegiatan Kemasyarakatan |
| 5.3.03.02 | Beban Kegiatan Sosial |
| 5.3.04.01 | Beban Non Usaha Lainnya |
| 5.4.01.01 | Taksiran PPh |

---

## Transaksi 1 — Tarik dan Setor Bank

### Tarik Bank

| Field | Nilai |
|-------|-------|
| Tanggal | …………. |
| Jenis Transaksi | Pemindahan Saldo / Aset |
| Sumber Dana | 1.1.01.04 Kas di Bank SPP |
| Rekening Tujuan | 1.1.01.01 Kas Tunai |
| Relasi | Bank ….. |
| Keterangan | Tarik dari Bank ….. |

> **Catatan:**
> - Apabila saldo buku kas sudah mencukupi alokasi pencairan, tidak perlu melakukan Penarikan Bank.
> - Apabila saldo buku kas tidak mencukupi alokasi pencairan, maka perlu melakukan Penarikan Bank.

### Setor Bank

| Field | Nilai |
|-------|-------|
| Tanggal | …………. |
| Jenis Transaksi | Pemindahan Saldo / Aset |
| Sumber Dana | 1.1.01.01 Kas Tunai |
| Rekening Tujuan | 1.1.01.04 Kas di Bank SPP |
| Relasi | Bank ….. |
| Keterangan | Setor ke Bank ….. |

> *Sesuaikan dengan Rekening Tujuan Bank

---

## Transaksi 2 — Bunga, Pajak, dan Administrasi Bank

### Bunga Bank

| Field | Nilai |
|-------|-------|
| Tanggal | …………. |
| Jenis Transaksi | Aset Masuk |
| Sumber Dana | 4.2.01.01 Pendapatan Bunga Bank |
| Rekening Tujuan | 1.1.01.03 Kas di Bank Operasional |
| Relasi | Bank … |
| Keterangan | Pendapatan Bunga Bank ….. |

> *Sesuaikan nama bank tujuan

### Pajak Bank

| Field | Nilai |
|-------|-------|
| Tanggal | …………. |
| Jenis Transaksi | Aset Keluar |
| Sumber Dana | 1.1.01.03 Kas di Bank Operasional |
| Rekening Tujuan | 5.3.01.01 Beban Pajak Bank |
| Relasi | Bank … |
| Keterangan | Beban Pajak Bank ….. |

> *Sesuaikan nama bank tujuan

### Administrasi Bank

| Field | Nilai |
|-------|-------|
| Tanggal | …………. |
| Jenis Transaksi | Aset Keluar |
| Sumber Dana | 1.1.01.03 Kas di Bank Operasional |
| Rekening Tujuan | 5.3.01.02 Beban Administrasi Bank |
| Relasi | Bank … |
| Keterangan | Beban Administrasi Bank ….. |

> *Sesuaikan nama bank tujuan

---

## Transaksi 3 — Pembelian ATI

### Masa Manfaat Aset Tetap

| Jenis Aset | Umur (Bulan) |
|-----------|-------------|
| Bangunan dan Prasarana | 240 |
| Mesin dan Instalasi | 60 |
| Komputer / Peralatan Komputer | 48 |
| Truk, Alat Mekanik, Alat Berat | 96 |
| Kendaraan | 120 |
| Inventaris dan Peralatan Kantor | 60 |

### Pembelian Tanah

| Field | Nilai |
|-------|-------|
| Tanggal | …………. |
| Jenis Transaksi | Pemindahan Saldo / Aset |
| Sumber Dana | 1.1.01.01 Kas Tunai |
| Rekening Tujuan | 1.2.01.01 Tanah |
| Relasi | (Nama tempat/orang penjual) |
| Nama Barang | … |
| Kategori | Umum |
| Jumlah | 1 |
| Harga Satuan | … |

### Pembelian Gedung / Bangunan

| Field | Nilai |
|-------|-------|
| Tanggal | …………. |
| Jenis Transaksi | Pemindahan Saldo / Aset |
| Sumber Dana | 1.1.01.01 Kas Tunai |
| Rekening Tujuan | 1.2.01.02 Gedung & Bangunan |
| Relasi | (Nama tempat/orang penjual) |
| Nama Barang | Gedung Serbaguna |
| Kategori | Gedung dan Bangunan |
| Jumlah | 1 |
| Harga Satuan | 100.000.000 |
| Umur (bulan) | 240 |

### Kendaraan dan Alat Berat

| Field | Nilai |
|-------|-------|
| Tanggal | …………. |
| Jenis Transaksi | Pemindahan Saldo / Aset |
| Sumber Dana | 1.1.01.01 Kas Tunai |
| Rekening Tujuan | 1.2.01.03 Kendaraan dan Mesin |
| Relasi | (Nama tempat/orang penjual) |
| Nama Barang | Sepeda Motor |
| Kategori | Kendaraan dan Alat Berat |
| Jumlah | 1 |
| Harga Satuan | 25.000.000 |
| Umur (bulan) | 120 |

### Pembelian Inventaris / Peralatan

| Field | Nilai |
|-------|-------|
| Tanggal | …………. |
| Jenis Transaksi | Pemindahan Saldo / Aset |
| Sumber Dana | 1.1.01.01 Kas Tunai |
| Rekening Tujuan | 1.2.01.04 Inventaris/Peralatan |
| Relasi | (Nama tempat/orang penjual) |
| Nama Barang | …… |
| Kategori | Umum |
| Jumlah | 1 |
| Harga Satuan | ……. |
| Umur (bulan) | 60 |

---

## Transaksi 4 — Aset Tak Berwujud

### Biaya Pendirian Organisasi

| Field | Nilai |
|-------|-------|
| Tanggal | …………. |
| Jenis Transaksi | Pemindahan Saldo / Aset |
| Sumber Dana | 1.1.01.01 Kas Umum |
| Rekening Tujuan | 1.2.03.01 Biaya Pendirian Organisasi |
| Relasi | (Nama Organisasi) |
| Nama Barang | Akta Pendirian PT LKM |
| Kategori | Umum |
| Jumlah | 1 |
| Harga Satuan | 5.000.000 |
| Umur (bulan) | 12 (opsional) |

### Lisensi

| Field | Nilai |
|-------|-------|
| Tanggal | …………. |
| Jenis Transaksi | Pemindahan Saldo / Aset |
| Sumber Dana | 1.1.01.01 Kas Tunai |
| Rekening Tujuan | 1.2.03.02 Lisensi |
| Relasi | (Nama Lisensi) |
| Nama Barang | Aplikasi SI DBM |
| Kategori | Umum |
| Jumlah | 1 |
| Harga Satuan | 12.500.000 |
| Umur (bulan) | 120 (ketik sesuai angka ini) |

### Sewa Dibayar Dimuka

| Field | Nilai |
|-------|-------|
| Tanggal | …………. |
| Jenis Transaksi | Pemindahan Saldo / Aset |
| Sumber Dana | 1.1.01.01 Kas Tunai |
| Rekening Tujuan | 1.2.03.03 Sewa dibayar dimuka |
| Nama Barang | Sewa Gedung |
| Kategori | Umum |
| Jumlah | 1 |
| Harga Satuan | 5.000.000 |
| Umur (bulan) | 12 |

### Asuransi

| Field | Nilai |
|-------|-------|
| Tanggal | …………. |
| Jenis Transaksi | Pemindahan Saldo / Aset |
| Sumber Dana | 1.1.01.01 Kas Tunai |
| Rekening Tujuan | 1.2.03.04 Asuransi dibayar dimuka |
| Relasi | (Nama Orang) |
| Nama Barang | ……. |
| Kategori | Umum |
| Jumlah | 1 |
| Harga Satuan | 1.200.000 |
| Umur (bulan) | 12 (opsional) |

---

## Transaksi 5 — Utang Laba

### Utang Laba Masyarakat

| Field | Nilai |
|-------|-------|
| Tanggal | …………. |
| Jenis Transaksi | Aset Keluar |
| Sumber Dana | 1.1.01.01 Kas Tunai |
| Keperluan | 2.1.04.01 Utang Laba Bagian Masyarakat |
| Relasi | … |
| Keterangan | … |

> **Laba Bagian Masyarakat meliputi:**
> - Kegiatan sosial kemasyarakatan dan bantuan RTM
> - Pengembangan Kapasitas
> - Pelatihan masyarakat dan kelompok pemanfaat

### Utang Laba Desa

| Field | Nilai |
|-------|-------|
| Tanggal | …………. |
| Jenis Transaksi | Aset Keluar |
| Sumber Dana | 1.1.01.01 Kas Tunai |
| Keperluan | 2.1.04.02 Utang Laba Bagian Desa |
| Relasi | … |
| Keterangan | … |

### Utang Penyerta Modal

| Field | Nilai |
|-------|-------|
| Tanggal | …………. |
| Jenis Transaksi | Aset Keluar |
| Sumber Dana | 1.1.01.01 Kas Tunai |
| Keperluan | 2.1.04.03 Utang Laba Bagian Penyerta Modal |
| Relasi | … |
| Keterangan | … |

---

## Transaksi 6 — Pinjaman Pihak ke-3

### Penerimaan Utang (Jangka Pendek — < 1 tahun)

| Field | Nilai |
|-------|-------|
| Tanggal | …………. |
| Jenis Transaksi | Aset Masuk |
| Sumber Dana | 2.1.05.01 Utang Jangka Pendek Lainnya |
| Rekening Tujuan | 1.1.01.01 Kas Tunai |
| Keterangan | Penerimaan Utang Pihak Ke 3 |

### Pengembalian Utang (Jangka Pendek)

| Field | Nilai |
|-------|-------|
| Tanggal | …………. |
| Jenis Transaksi | Aset Keluar |
| Sumber Dana | 1.1.01.01 Kas Tunai |
| Rekening Tujuan | 2.1.05.01 Utang Jangka Pendek Lainnya |
| Keterangan | Pembayaran Utang Ke …. |

### Penerimaan Utang (Jangka Panjang — > 1 tahun)

| Field | Nilai |
|-------|-------|
| Tanggal | …………. |
| Jenis Transaksi | Aset Masuk |
| Sumber Dana | 2.2.02.01 Utang Jangka Panjang Lainnya |
| Rekening Tujuan | 1.1.01.01 Kas Tunai |
| Keterangan | Penerimaan Utang Pihak Ke 3 |

### Pengembalian Utang (Jangka Panjang)

| Field | Nilai |
|-------|-------|
| Tanggal | …………. |
| Jenis Transaksi | Aset Keluar |
| Sumber Dana | 1.1.01.01 Kas Tunai |
| Rekening Tujuan | 2.2.02.01 Utang Jangka Panjang Lainnya |
| Keterangan | Pembayaran Utang Ke …. |

---

## Transaksi 7 — Penyertaan Modal Desa

| Field | Nilai |
|-------|-------|
| Tanggal | …………. |
| Jenis Transaksi | Aset Masuk |
| Sumber Dana | 3.1.01.02 Modal Desa Pendiri |
| Disimpan Ke | 1.1.01.01 Kas Tunai (Opsional: Kas / Bank) |
| Relasi | … |
| Keterangan | Setoran Modal Desa … |

---

## Transaksi 8 — Taksiran PPh dan Pembayaran Pajak

### 1. Taksiran PPh ke Utang Pajak

| Field | Nilai |
|-------|-------|
| Tanggal | …… |
| Jenis Transaksi | Aset Keluar |
| Sumber Dana | 2.1.03.01 Utang Pajak |
| Untuk Keperluan | 5.4.01.01 Taksiran PPh |
| Relasi | … |
| Keterangan | … |

### 2. Pembayaran Pajak

| Field | Nilai |
|-------|-------|
| Tanggal | …. |
| Jenis Transaksi | Aset Keluar |
| Sumber Dana | 1.1.01.01 Kas Tunai |
| Untuk Keperluan | 2.1.03.01 Utang Pajak |
| Relasi | … |
| Keterangan | … |

---

## Transaksi 9 — Beban Penyusutan

> Penyusutan dilakukan setiap **akhir bulan** sesuai masing-masing kategori ATI.
>
> Laporan yang harus dibuka: Menu Pelaporan → Periode bulan yang bersangkutan
> - A. Laporan Catatan atas Laporan Keuangan (CaLK)
> - B. Laporan Daftar Aset Tetap dan Inventaris

### Beban Penyusutan Gedung

| Field | Nilai |
|-------|-------|
| Tanggal | (akhir bulan) |
| Jenis Transaksi | Aset Keluar |
| Sumber Dana | 1.2.02.01 Akumulasi penyusutan Gedung dan Bangunan |
| Untuk Keperluan | 5.1.07.08 Beban Penyusutan Gedung dan Bangunan |
| Keterangan | Beban Penyusutan Gedung dan Bangunan (Bulan ….) |

### Beban Penyusutan Kendaraan

| Field | Nilai |
|-------|-------|
| Tanggal | (akhir bulan) |
| Jenis Transaksi | Aset Keluar |
| Sumber Dana | 1.2.02.02 Akumulasi penyusutan Kendaraan dan Mesin |
| Untuk Keperluan | 5.1.07.09 Beban Penyusutan Kendaraan & Mesin |
| Keterangan | Beban Penyusutan Kendaraan & Mesin (Bulan ….) |

### Beban Penyusutan Inventaris

| Field | Nilai |
|-------|-------|
| Tanggal | (akhir bulan) |
| Jenis Transaksi | Aset Keluar |
| Sumber Dana | 1.2.02.03 Akumulasi penyusutan Inventaris/Peralatan |
| Untuk Keperluan | 5.1.07.10 Beban Penyusutan Inventaris |
| Keterangan | Beban Penyusutan Inventaris (Bulan ….) |

---

## Transaksi 10 — Beban Amortisasi

> Amortisasi dilakukan setiap **akhir bulan** sesuai masing-masing kategori Aset Tak Berwujud.
>
> Laporan yang harus dibuka: Menu Pelaporan → Periode bulan yang bersangkutan
> - A. Laporan Catatan atas Laporan Keuangan (CaLK)
> - B. Laporan Daftar Aset Tak Berwujud

### Amortisasi Biaya Pendirian Organisasi

| Field | Nilai |
|-------|-------|
| Tanggal | (akhir bulan) |
| Jenis Transaksi | Aset Keluar |
| Sumber Dana | 1.2.04.01 Akumulasi Amortisasi Biaya Pendirian Organisasi |
| Untuk Keperluan | 5.1.07.11 Beban Amortisasi Biaya Pendirian Organisasi |
| Keterangan | Beban Amortisasi Biaya Pendirian Organisasi (Bulan ….) |

### Amortisasi Lisensi

| Field | Nilai |
|-------|-------|
| Tanggal | (akhir bulan) |
| Jenis Transaksi | Aset Keluar |
| Sumber Dana | 1.2.04.02 Akumulasi Amortisasi Lisensi |
| Untuk Keperluan | 5.1.07.12 Beban Amortisasi Lisensi |
| Keterangan | Beban Amortisasi Lisensi (Bulan ….) |

### Amortisasi Sewa

| Field | Nilai |
|-------|-------|
| Tanggal | (akhir bulan) |
| Jenis Transaksi | Aset Keluar |
| Sumber Dana | 1.2.04.03 Akumulasi Amortisasi Sewa dibayar dimuka |
| Untuk Keperluan | 5.1.07.13 Beban Amortisasi Sewa dibayar dimuka |
| Keterangan | Beban Amortisasi Sewa dibayar dimuka (Bulan ….) |

### Amortisasi Asuransi

| Field | Nilai |
|-------|-------|
| Tanggal | (akhir bulan) |
| Jenis Transaksi | Aset Keluar |
| Sumber Dana | 1.2.04.04 Akumulasi Amortisasi Asuransi dibayar dimuka |
| Untuk Keperluan | 5.1.07.14 Beban Amortisasi Asuransi dibayar dimuka |
| Keterangan | Beban Amortisasi Asuransi dibayar dimuka (Bulan ….) |

---

## Transaksi 11 — Transaksi antar Kas / antar Bank

### Kas Umum ke Kas Kecil

| Field | Nilai |
|-------|-------|
| Tanggal | …………. |
| Jenis Transaksi | Pemindahan Saldo / Aset |
| Sumber Dana | 1.1.01.01 Kas Tunai |
| Rekening Tujuan | 1.1.01.02 Kas Kecil |
| Keterangan | Pemindahan Saldo dari Kas Umum ke Kas Kecil |

### Bank 1 ke Bank 2

| Field | Nilai |
|-------|-------|
| Tanggal | …………. |
| Jenis Transaksi | Pemindahan Saldo / Aset |
| Sumber Dana | 1.1.01.04 Kas di Bank SPP |
| Rekening Tujuan | 1.1.01.05 Kas di Bank Bumdesma |
| Keterangan | Pemindahan Saldo dari Kas di Bank SPP ke Kas di Bank Bumdesma |

---

## Transaksi 12 — Transaksi Operasional (Beban)

| Field | Nilai |
|-------|-------|
| Tanggal | …………. |
| Jenis Transaksi | Aset Keluar |
| Sumber Dana | 1.1.01.01 Kas Tunai |
| Untuk Keperluan | *(sesuaikan dengan kode akun beban)* |
| Relasi | … |
| Keterangan | … |

> *Sesuaikan dengan Nama Akun Beban (lihat daftar kode akun kelompok 5.x)*

---

## Transaksi 13 — Investasi Unit Usaha

| Field | Nilai |
|-------|-------|
| Tanggal | …………. |
| Jenis Transaksi | Pemindahan Saldo / Aset |
| Sumber Dana | 1.1.01.01 Kas Tunai |
| Rekening Tujuan | 1.1.06.01 Investasi unit Usaha 1 |
| Relasi | ….. |
| Keterangan | Setoran Investasi unit Usaha …. |

---

## Transaksi 14 — Penghapusan ATI (Inventaris/Peralatan)

| Field | Nilai |
|-------|-------|
| Tanggal | …………. |
| Jenis Transaksi | Aset Keluar |
| Sumber Dana | 1.2.02.03 Akumulasi penyusutan Inventaris/Peralatan |
| Rekening Tujuan | 5.3.02.01 Beban Penghapusan Aset Tetap |
| Nama Barang | Laptop SNSV core i7 |
| Alasan Dihapus | Rusak / Hilang / Hapus / Dijual |
| Unit Dihapus | 1 |
| Nilai Buku | 1 |

### Nilai Buku Berdasarkan Status Kondisi

| Status Kondisi | Nilai Buku | Keterangan |
|----------------|-----------|------------|
| Rusak | 1 | Barang masih ada, kondisi rusak |
| Hilang | 0 | Barang sudah tidak ada |
| Hapus | 0 | Barang masih ada/tidak ada, namun sudah tidak layak pakai |

---

## Transaksi 15 — Penyisihan Cadangan Penghapusan (CKP)

| Field | Nilai |
|-------|-------|
| Tanggal | (sesuaikan tanggal) |
| Jenis Transaksi | Aset Keluar |
| Sumber Dana | 1.1.04.01 Cadangan Kerugian Piutang Pokok SPP |
| Rekening Tujuan | 5.1.07.01 Beban Penyisihan Kerugian Piutang SPP |
| Relasi | ….. |
| Keterangan | Beban Penyisihan Kerugian Piutang SPP |
| Nominal | (Nilai Penyisihan) |

> *Transaksi dilakukan pada bulan Desember.*
>
> **Lokasi data:** Menu Pelaporan → Daftar Perkembangan Piutang → Cadangan Penyisihan Penghapusan Kredit

---

## Transaksi 16 — Utang Bonus Prestasi Kerja

### 1. Utang Bonus Prestasi

| Field | Nilai |
|-------|-------|
| Tanggal | …… |
| Jenis Transaksi | Aset Keluar |
| Sumber Dana | 2.1.02.04 Utang Bonus Prestasi Kerja |
| Untuk Keperluan | 5.1.02.05 Bonus Prestasi Kerja |
| Relasi | … |
| Keterangan | … |

> *Transaksi dilakukan pada bulan Desember.*

### 2. Pembayaran Utang Bonus

| Field | Nilai |
|-------|-------|
| Tanggal | ….. |
| Jenis Transaksi | Aset Keluar |
| Sumber Dana | 1.1.01.01 Kas Tunai |
| Untuk Keperluan | 2.1.02.04 Utang Bonus Prestasi Kerja |
| Relasi | … |
| Keterangan | … |

---

## Transaksi 17 — Pembelian Barang Secara Tempo

### 1. Pencatatan Transaksi DP

| Field | Nilai |
|-------|-------|
| Tanggal | …… |
| Jenis Transaksi | Pemindahan Saldo / Aset |
| Sumber Dana | 1.1.01.01 Kas Tunai |
| Untuk Keperluan | 1.2.05.01 Konstruksi Dalam Pengerjaan dan Uang Muka |
| Relasi | (Nama tempat/orang penjual) |
| Keterangan | DP Mobil ……… |

### 2. Pencatatan Transaksi Pelunasan

| Field | Nilai |
|-------|-------|
| Tanggal | ……. |
| Jenis Transaksi | Pemindahan Saldo / Aset |
| Sumber Dana | 1.1.01.01 Kas Tunai |
| Untuk Keperluan | 1.2.05.01 Konstruksi Dalam Pengerjaan dan Uang Muka |
| Relasi | (Nama tempat/orang penjual) |
| Keterangan | Pelunasan Mobil ….. |

### 3. Pencatatan Pengakuan Menjadi Aset

| Field | Nilai |
|-------|-------|
| Tanggal | ….. |
| Jenis Transaksi | Pemindahan Saldo / Aset |
| Sumber Dana | 1.2.05.01 Konstruksi Dalam Pengerjaan dan Uang Muka |
| Rekening Tujuan | 1.2.01.03 Kendaraan dan Mesin |
| Relasi | (Nama tempat/orang penjual) |
| Nama Barang | Mobil |
| Kategori | Kendaraan dan mesin |
| Jumlah | 1 |
| Harga Satuan | 25.000.000 |
| Umur (bulan) | 120 |

---

## Pencairan Pinjaman

### A. Pencairan Pinjaman

#### 1. Tarik Bank

| Field | Nilai |
|-------|-------|
| Tanggal | …………. |
| Jenis Transaksi | Pemindahan Saldo / Aset |
| Sumber Dana | 1.1.01.04 Kas di Bank SPP |
| Rekening Tujuan | 1.1.01.01 Kas Umum |
| Relasi | Bank ….. |
| Keterangan | Tarik dari Bank ….. |

> **Catatan:**
> - Apabila saldo buku kas sudah mencukupi alokasi pencairan, tidak perlu melakukan Penarikan Bank.
> - Apabila saldo buku kas tidak mencukupi alokasi pencairan, maka perlu melakukan Penarikan Bank.
