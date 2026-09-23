<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/Transaction.php';

// Inisialisasi saldo.
if (!isset($_SESSION['balance'])) {
    $_SESSION['balance'] = 0.0;
}

// Inisialisasi riwayat transaksi.
if (!isset($_SESSION['transactions'])) {
    $_SESSION['transactions'] = [];
}

// Membuat token CSRF jika belum tersedia.
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';

    // Validasi CSRF.
    if (
        !is_string($csrfToken)
        || !hash_equals($_SESSION['csrf_token'], $csrfToken)
    ) {
        $message = 'Token CSRF tidak valid.';
        $messageType = 'error';
    } else {
        $type = $_POST['type'] ?? '';
        $amountInput = trim($_POST['amount'] ?? '');

        // Validasi jenis transaksi dengan match.
        $transactionType = match ($type) {
            'deposit' => 'deposit',
            'withdraw' => 'withdraw',
            default => null,
        };

        // Validasi jumlah sebagai angka desimal positif.
        $isValidAmount = preg_match(
            '/^\d+(?:\.\d+)?$/',
            $amountInput
        ) === 1;

        if ($transactionType === null) {
            $message = 'Jenis transaksi tidak valid.';
            $messageType = 'error';
        } elseif (!$isValidAmount) {
            $message = 'Jumlah transaksi harus berupa angka desimal positif.';
            $messageType = 'error';
        } else {
            $amount = (float) $amountInput;

            if ($amount <= 0) {
                $message = 'Jumlah transaksi harus lebih besar dari 0.';
                $messageType = 'error';
            } else {
                $id = count($_SESSION['transactions']) + 1;

                $transaction = new Transaction(
                    $id,
                    $transactionType,
                    $amount
                );

                if ($transaction->process()) {
                    $_SESSION['transactions'][] = [
                        'id' => $transaction->getId(),
                        'type' => $transaction->getType(),
                        'amount' => $transaction->getAmount(),
                    ];

                    $message = 'Transaksi berhasil diproses.';
                    $messageType = 'success';
                } else {
                    $message = 'Penarikan gagal karena saldo tidak mencukupi.';
                    $messageType = 'error';
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Keuangan</title>
</head>
<body>

    <h1>Sistem Manajemen Keuangan Sederhana</h1>

    <?php if ($message !== ''): ?>
        <p>
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <p>
        Saldo saat ini:
        <strong>
            Rp <?= number_format($_SESSION['balance'], 2, ',', '.') ?>
        </strong>
    </p>

    <h2>Transaksi</h2>

    <form method="POST" action="">
        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"
        >

        <label for="type">Jenis transaksi:</label>

        <select name="type" id="type" required>
            <option value="">-- Pilih transaksi --</option>
            <option value="deposit">Deposit</option>
            <option value="withdraw">Penarikan</option>
        </select>

        <br><br>

        <label for="amount">Jumlah:</label>

        <input
            type="text"
            name="amount"
            id="amount"
            inputmode="decimal"
            placeholder="Contoh: 100000.50"
            required
        >

        <br><br>

        <button type="submit">Proses Transaksi</button>
    </form>

    <h2>Riwayat Transaksi</h2>

    <?php if (empty($_SESSION['transactions'])): ?>

        <p>Belum ada transaksi.</p>

    <?php else: ?>

        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($_SESSION['transactions'] as $transaction): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars(
                                (string) $transaction['id'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                (string) $transaction['type'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>

                        <td>
                            Rp <?= htmlspecialchars(
                                number_format(
                                    (float) $transaction['amount'],
                                    2,
                                    ',',
                                    '.'
                                ),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

</body>
</html>