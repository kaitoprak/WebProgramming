<?php

declare(strict_types=1);

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once __DIR__ . '/GuestBook.php';

$guestBook = new GuestBook();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'], $csrfToken)) {
        die('CSRF token tidak valid.');
    }

    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pesan = trim($_POST['pesan'] ?? '');

    if ($nama === '') {
        die('Nama wajib diisi.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Format email tidak valid.');
    }

    if (mb_strlen($pesan) < 5) {
        die('Pesan minimal 5 karakter.');
    }

    $guestBook->saveMessage($nama, $email, $pesan);
}

$messages = $guestBook->getMessages();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu Perpustakaan</title>
</head>
<body>

    <h1>Buku Tamu Perpustakaan</h1>

    <h2>Tulis Pesan</h2>

    <form method="POST" action="">
        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"
        >

        <label for="nama">Nama:</label>
        <input type="text" name="nama" id="nama">

        <br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email">

        <br><br>

        <label for="pesan">Pesan:</label>
        <textarea name="pesan" id="pesan"></textarea>

        <br><br>

        <button type="submit">Kirim</button>
    </form>
 
    <h2>Daftar Pesan</h2>

<?php if (empty($messages)): ?>

    <p>Belum ada pesan.</p>

<?php else: ?>

    <table border="1">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Pesan</th>
                <th>Tanggal Kirim</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($messages as $message): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars($message['nama'], ENT_QUOTES, 'UTF-8') ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($message['email'], ENT_QUOTES, 'UTF-8') ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($message['pesan'], ENT_QUOTES, 'UTF-8') ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($message['tanggal_kirim'], ENT_QUOTES, 'UTF-8') ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php endif; ?>

</body>
</html>