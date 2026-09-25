<?php

declare(strict_types=1);

class GuestBook
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO(
            'sqlite:' . __DIR__ . '/guestbook.sqlite'
        );

        $this->pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );

        $this->createTable();
    }

    private function createTable(): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS buku_tamu (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nama TEXT NOT NULL,
                email TEXT NOT NULL,
                pesan TEXT NOT NULL,
                tanggal_kirim DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ";

        $this->pdo->exec($sql);
    }
}

$guestBook = new GuestBook();

echo 'Database berhasil dibuat.';