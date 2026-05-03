# Transaction Module untuk Laravel

**Transaction Module** adalah package Laravel yang menyediakan sistem manajemen transaksi yang lengkap dan siap pakai. Dengan satu package ini, kamu mendapatkan model, migrasi, DTO berbasis [Spatie Laravel Data](https://github.com/spatie/laravel-data), hingga UI admin berbasis [Filament](https://filamentphp.com) — semua sudah terintegrasi.

Package ini cocok digunakan pada aplikasi e-commerce, kasir, atau sistem pemesanan yang membutuhkan pencatatan transaksi secara terstruktur.

---

## Daftar Isi

- [Fitur Utama](#fitur-utama)
- [Persyaratan](#persyaratan)
- [Instalasi](#instalasi)
- [Konfigurasi](#konfigurasi)
- [Struktur Database](#struktur-database)
- [Penggunaan](#penggunaan)
  - [Membuat Transaksi](#membuat-transaksi)
  - [Membuat Customer](#membuat-customer)
  - [Walk-In Customer](#walk-in-customer)
  - [Mengambil Data Transaksi](#mengambil-data-transaksi)
  - [Mencari Transaksi](#mencari-transaksi)
  - [Menandai Status Pembayaran](#menandai-status-pembayaran)
  - [Mengubah Status Transaksi](#mengubah-status-transaksi)
  - [Mengubah Status Pembayaran via Facade](#mengubah-status-pembayaran-via-facade)
- [Events](#events)
- [Enums](#enums)
- [Data Transfer Objects (DTO)](#data-transfer-objects-dto)
- [Meng-extend Model](#meng-extend-model)
- [Filament Admin UI](#filament-admin-ui)
- [Testing](#testing)
- [Changelog](#changelog)
- [Lisensi](#lisensi)

---

## Fitur Utama

- **Manajemen Transaksi** — buat, lacak, dan kelola transaksi beserta item-itemnya.
- **Manajemen Customer** — simpan data pelanggan dengan dukungan walk-in customer.
- **Manajemen Diskon** — kode diskon dengan tipe persentase atau nominal tetap.
- **Polymorphic Transaction Items** — item transaksi bisa mereferensikan model apapun (produk, layanan, paket, dll.) tanpa mengubah skema database.
- **Type-safe DTOs** — menggunakan Spatie Laravel Data untuk transfer data yang aman dan terstruktur.
- **Status Otomatis** — status transaksi dan pembayaran dikelola via PHP Enum dengan casting otomatis.
- **Filament Admin UI** — panel administrasi lengkap untuk mengelola semua data.
- **Extensible** — semua model bisa di-override via konfigurasi.

---

## Persyaratan

| Dependensi | Versi |
|---|---|
| PHP | ^8.4 |
| Laravel | ^11.0 \|\| ^12.0 \|\| ^13.0 |
| Spatie Laravel Data | ^4.22 |

---

## Instalasi
Sebelum menginstal Transaction Module, kamu harus membuat akun terlebih dahulu di : [sini](https://dikiakbarasyidiq.dev/auth/register). Setelah membuat akun buka halaman (Dashboard → Account) untuk melihat license key kamu.

Copy license key kamu lalu jalankan command ini :

```bash
composer config bearer.dikiakbarasyidiq.dev <license_key>
```

Setelah menjalankan command diatas, tambahkan repository berikut di file composer.json. (Jika Belum Ada)
```
{
"repositories": [
        {
            "type" : "composer",
            "url" : "https://dikiakbarasyidiq.dev"
        }
    ]
}
```

Setelah menambahkan repository, update composer terlebih dahulu:

```bash
composer update
```

Lalu kamu akan bisa melakukan installasi via composer di project kamu dengan command :

```bash
composer require codewithdiki/transaction-module
php artisan vendor:publish --tag="transaction-module-migrations"
php artisan vendor:publish --tag="transaction-module-config"
php artisan migrate
```

Jika aplikasimu membutuhkan walk-in customer (misalnya untuk transaksi kasir tanpa akun):

```bash
php artisan transaction-module:create-walk-in-customer
```

---

## Konfigurasi

Setelah publish, file konfigurasi tersedia di `config/transaction-module.php`:

```php
return [
    // Model yang digunakan — bisa diganti dengan model custom kamu
    "customer_class"          => \CodeWithDiki\TransactionModule\Models\Customer::class,
    "transaction_class"       => \CodeWithDiki\TransactionModule\Models\Transaction::class,
    "transaction_item_class"  => \CodeWithDiki\TransactionModule\Models\TransactionItem::class,
    "discount_class"          => \CodeWithDiki\TransactionModule\Models\Discount::class,
    "log_class"               => \CodeWithDiki\TransactionModule\Models\TransactionLog::class,

    // Model User aplikasimu (digunakan untuk mencatat siapa yang mengubah status)
    "user_class"              => \App\Models\User::class,

    // Persentase pajak (baca dari .env)
    "tax" => env("TAX_PERCENTAGE", 0),

    // Status transaksi setelah pembayaran berhasil
    "status_after_payment" => \CodeWithDiki\TransactionModule\Enums\TransactionStatus::PROCESSING,

    // Listener untuk event TransactionStatusChangedEvent
    "listeners" => [
        \CodeWithDiki\TransactionModule\Events\TransactionStatusChangedEvent::class => [
            // Tambahkan listener class kamu di sini
        ],
    ],
];
```

Tambahkan variabel berikut di file `.env` jika diperlukan:

```env
TAX_PERCENTAGE=11
```

---

## Struktur Database

Package ini membuat 4 tabel saat migrasi dijalankan:

### `customers`
Menyimpan data pelanggan.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint | Primary key |
| `name` | string | Nama pelanggan |
| `email` | string, nullable | Email unik |
| `phone_number` | string, nullable | Nomor telepon |
| `address` | string, nullable | Alamat |

### `transactions`
Menyimpan header transaksi.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint | Primary key |
| `customer_id` | foreign key | Relasi ke `customers` |
| `trx_id` | string, unique | ID transaksi unik |
| `total_amount` | double | Total sebelum pajak |
| `tax_amount` | double | Nominal pajak |
| `grand_total` | double | Total akhir |
| `payment_status` | string | `PENDING`, `PAID`, atau `FAILED` |
| `status` | string | Status pengiriman/proses |
| `notes` | longText, nullable | Catatan tambahan |
| `paid_at` | dateTime, nullable | Waktu pembayaran berhasil |
| `failed_at` | dateTime, nullable | Waktu pembayaran gagal |

### `transaction_items`
Menyimpan item di dalam setiap transaksi. Menggunakan polymorphic relation sehingga bisa mereferensikan model apapun.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint | Primary key |
| `transaction_id` | foreign key | Relasi ke `transactions` |
| `itemable_type` | string | Nama class model item |
| `itemable_id` | bigint | ID record model item |
| `name` | string | Nama item |
| `description` | string, nullable | Deskripsi item |
| `price` | double | Harga satuan |
| `quantity` | integer | Jumlah |
| `total` | double | Subtotal item |

### `discounts`
Menyimpan kode diskon.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint | Primary key |
| `name` | string | Nama diskon |
| `code` | string | Kode diskon |
| `is_active` | boolean | Status aktif |
| `type` | string | `Percentage` atau `FixedAmount` |
| `value` | double | Nilai diskon |

### `transaction_logs`
Mencatat riwayat perubahan status transaksi (baik `status` maupun `payment_status`).

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint | Primary key |
| `transaction_id` | foreign key | Relasi ke `transactions` |
| `user_id` | foreign key, nullable | User yang melakukan perubahan |
| `from_status` | string | Status sebelumnya |
| `to_status` | string | Status sesudahnya |
| `note` | longText, nullable | Catatan perubahan |

---

## Penggunaan

Semua fungsionalitas utama bisa diakses melalui facade `TransactionModule`.

```php
use CodeWithDiki\TransactionModule\Facades\TransactionModule;
```

### Membuat Transaksi

Gunakan `TransactionData` dan `TransactionItemData` untuk membuat transaksi beserta item-itemnya secara sekaligus. Package akan otomatis memvalidasi bahwa total item sesuai dengan `total_amount`.

```php
use CodeWithDiki\TransactionModule\Data\TransactionData;
use CodeWithDiki\TransactionModule\Data\TransactionItemData;
use CodeWithDiki\TransactionModule\Enums\PaymentStatus;
use CodeWithDiki\TransactionModule\Enums\TransactionStatus;
use CodeWithDiki\TransactionModule\Facades\TransactionModule;
use Illuminate\Support\Collection;

// Siapkan data transaksi
$transactionData = new TransactionData(
    trx_id: 'TRX-' . now()->format('YmdHis'),
    customer_id: 1,
    total_amount: 150000,
    tax_amount: 16500,
    grand_total: 166500,
    payment_status: PaymentStatus::PENDING,
    status: TransactionStatus::ONHOLD,
    notes: 'Pesanan via WhatsApp',
);

// Siapkan item-item transaksi (itemable bisa model apapun)
$items = collect([
    new TransactionItemData(
        itemable: $product, // instance dari model Product, Service, dsb.
        name: 'Kaos Polos Hitam',
        quantity: 2,
        price: 75000,
        total: 150000,
    ),
]);

// Buat transaksi
$transaction = TransactionModule::createTransaction($transactionData, $items);
```

> **Catatan:** Jika `total` pada `TransactionItemData` tidak diisi, package akan menghitungnya otomatis dari `quantity * price`. Package juga memvalidasi bahwa jumlah semua item sama dengan `total_amount` di header transaksi.

### Membuat Customer

```php
use CodeWithDiki\TransactionModule\Data\CustomerData;
use CodeWithDiki\TransactionModule\Facades\TransactionModule;

$customer = TransactionModule::createCustomer(new CustomerData(
    name: 'Budi Santoso',
    email: 'budi@example.com',
    phone_number: '081234567890',
    address: 'Jl. Merdeka No. 1, Jakarta',
));
```

### Walk-In Customer

Walk-in customer adalah pelanggan default untuk transaksi tanpa akun (misalnya transaksi kasir). Gunakan command artisan untuk membuatnya:

```bash
php artisan transaction-module:create-walk-in-customer
```

Kemudian ambil datanya kapanpun dibutuhkan:

```php
$walkIn = TransactionModule::getWalkInCustomer();
```

### Mengambil Data Transaksi

Semua parameter bersifat opsional dan bisa dikombinasikan:

```php
use CodeWithDiki\TransactionModule\Enums\PaymentStatus;

// Semua transaksi
$transaksi = TransactionModule::getTransactions();

// Filter berdasarkan status pembayaran
$lunas = TransactionModule::getTransactions(status: PaymentStatus::PAID);

// Filter berdasarkan rentang tanggal
$bulanIni = TransactionModule::getTransactions(
    from: '2026-05-01',
    to: '2026-05-31',
);

// Kombinasi filter + limit
$terbaru = TransactionModule::getTransactions(
    from: '2026-05-01',
    status: PaymentStatus::PAID,
    limit: 10,
);
```

**Menghitung jumlah transaksi:**

```php
$total = TransactionModule::getTransactionsCountsByDate(
    status: PaymentStatus::PAID,
    from: '2026-05-01',
    to: '2026-05-31',
);
```

**Menghitung total nominal transaksi:**

```php
$revenue = TransactionModule::getTransactionSumByDate(
    status: PaymentStatus::PAID,
    from: '2026-05-01',
    to: '2026-05-31',
);
```

### Mencari Transaksi

```php
// Cari transaksi berdasarkan trx_id
$transaction = TransactionModule::getTransactionByTrxId('TRX-20260503120000');

// Cari transaksi berdasarkan id
$transaction = TransactionModule::getTransactionById(1);

// Ambil semua transaksi milik customer berdasarkan email
$transactions = TransactionModule::getTransactionsByCustomerEmail('budi@example.com');
```

**Mencari Customer berdasarkan email:**

```php
$customer = TransactionModule::getCustomerByEmail('budi@example.com');
```

### Menandai Status Pembayaran

Setelah konfirmasi pembayaran diterima (misalnya dari callback payment gateway), gunakan method berikut langsung pada model `Transaction`:

```php
// Tandai sebagai lunas
// Otomatis: payment_status = PAID, paid_at = now(), status = status_after_payment dari config
$transaction->markAsPaid();

// Tandai sebagai gagal
// Otomatis: payment_status = FAILED, failed_at = now()
$transaction->markAsFailed();
```

### Mengubah Status Transaksi

Gunakan method ini untuk mengubah `status` (status pengiriman/proses) sebuah transaksi. Perubahan akan otomatis dicatat di `transaction_logs` dan men-dispatch `TransactionStatusChangedEvent`.

```php
use CodeWithDiki\TransactionModule\Enums\TransactionStatus;
use CodeWithDiki\TransactionModule\Facades\TransactionModule;

TransactionModule::setTransactionStatus(
    transaction: $transaction,
    status: TransactionStatus::PROCESSING,
    note: 'Pesanan sedang dikemas',   // opsional
);
```

### Mengubah Status Pembayaran via Facade

Alternatif dari memanggil `markAsPaid()` / `markAsFailed()` langsung pada model. Method ini juga mencatat log dan men-dispatch event.

```php
use CodeWithDiki\TransactionModule\Enums\PaymentStatus;
use CodeWithDiki\TransactionModule\Facades\TransactionModule;

TransactionModule::setPaymentStatus(
    transaction: $transaction,
    status: PaymentStatus::PAID,
    note: 'Konfirmasi dari payment gateway',  // opsional
);
```

> **Catatan:** Saat `status = PaymentStatus::PAID`, method ini secara internal memanggil `$transaction->markAsPaid()`. Saat `status = PaymentStatus::FAILED`, memanggil `$transaction->markAsFailed()`.

---

## Events

### `TransactionStatusChangedEvent`

Event ini di-dispatch setiap kali status transaksi berubah melalui `setTransactionStatus()` atau `setPaymentStatus()`. Event ini mengimplementasikan `ShouldBroadcast`.

| Property | Tipe | Keterangan |
|---|---|---|
| `$transaction` | `Transaction` | Instance transaksi yang berubah |
| `$log` | `TransactionLog` | Record log perubahan status |

**Broadcast channel:** `private-transaction-status-changed`

**Broadcast event name:** `cwd.transaction-module.transaction-status-changed`

Untuk mendaftarkan listener, tambahkan di `config/transaction-module.php`:

```php
"listeners" => [
    \CodeWithDiki\TransactionModule\Events\TransactionStatusChangedEvent::class => [
        \App\Listeners\HandleTransactionStatusChanged::class,
    ],
],
```

---

## Enums

### `PaymentStatus`

| Nilai | Keterangan |
|---|---|
| `PENDING` | Menunggu pembayaran |
| `PAID` | Pembayaran berhasil |
| `FAILED` | Pembayaran gagal |

### `TransactionStatus`

| Nilai | Keterangan |
|---|---|
| `ONHOLD` | Menunggu konfirmasi |
| `PROCESSING` | Sedang diproses |
| `ONDELIVERY` | Dalam pengiriman |
| `DELIVERED` | Sudah diterima |
| `CANCELLED` | Dibatalkan |
| `RETURNED` | Dikembalikan |
| `REFUNDED` | Dana dikembalikan |
| `FAILED` | Transaksi gagal |
| `COMPLETED` | Selesai |

### `DiscountType`

| Nilai | Keterangan |
|---|---|
| `Percentage` | Diskon dalam persen (mis. 10%) |
| `FixedAmount` | Diskon nominal tetap (mis. Rp 20.000) |

---

## Data Transfer Objects (DTO)

Package menggunakan [Spatie Laravel Data](https://github.com/spatie/laravel-data) untuk DTOs yang type-safe.

### `CustomerData`

```php
new CustomerData(
    name: string,           // wajib
    email: ?string,         // opsional
    phone_number: ?string,  // opsional
    address: ?string,       // opsional
)
```

### `TransactionData`

```php
new TransactionData(
    trx_id: string,                 // wajib, harus unik
    customer_id: int,               // wajib
    total_amount: float,            // wajib, total sebelum pajak
    payment_status: PaymentStatus,  // wajib
    status: TransactionStatus,      // wajib
    tax_amount: ?float,             // opsional
    grand_total: ?float,            // opsional
    notes: ?string,                 // opsional
)
```

### `TransactionItemData`

```php
new TransactionItemData(
    itemable: Model,        // wajib, instance model apapun (produk, layanan, dll.)
    name: string,           // wajib
    quantity: int,          // wajib
    price: float,           // wajib, harga satuan
    description: ?string,   // opsional
    total: ?float,          // opsional, jika null dihitung otomatis: quantity * price
)
```

---

## Meng-extend Model

Jika kamu perlu menambahkan kolom atau relasi tambahan, extend model bawaan dan daftarkan di konfigurasi:

```php
// app/Models/Transaction.php
namespace App\Models;

use CodeWithDiki\TransactionModule\Models\Transaction as BaseTransaction;

class Transaction extends BaseTransaction
{
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}
```

Kemudian daftarkan di `config/transaction-module.php`:

```php
"transaction_class" => \App\Models\Transaction::class,
```

Cara yang sama berlaku untuk model `Customer`, `TransactionItem`, `Discount`, dan `TransactionLog`.

> **Catatan:** Model `Transaction` memiliki relasi `logs()` ke `TransactionLog`, sehingga kamu bisa mengakses riwayat perubahan status dengan `$transaction->logs`.

---

## Filament Admin UI

Jika aplikasimu menggunakan [Filament](https://filamentphp.com), package ini menyertakan plugin Filament siap pakai. Daftarkan di `AdminPanelProvider`:

```php
use CodeWithDiki\TransactionModule\TransactionModuleFilament;

$panel->plugins([
    TransactionModuleFilament::make(),
]);
```

Resource yang tersedia di navigation group **"Transaction Management"**:

| Resource | Halaman |
|---|---|
| Customers | List, Create, Edit, View |
| Discounts | List, Create, Edit, View |
| Transactions | List, View |
| Transaction Items | List, View |

> **Catatan:** Transaksi tidak bisa dibuat atau diedit langsung dari UI. Buat transaksi hanya melalui `TransactionModule::createTransaction()` agar validasi business logic tetap berjalan.

---

## Testing

```bash
composer test
```

---

## Changelog

Lihat [CHANGELOG](CHANGELOG.md) untuk informasi perubahan di setiap versi.

---

## Lisensi

The MIT License (MIT). Lihat [License File](LICENSE.md) untuk detail lebih lanjut.
