<?php

declare(strict_types=1);

class Transaction
{
    public function __construct(
        private int $id,
        private string $type,
        private float $amount
    ) {
    }

    public function process(): bool
    {
        $balance = $_SESSION['balance'] ?? 0.0;

        return match ($this->type) {
            'deposit' => $this->updateBalance($balance + $this->amount),
            'withdraw' => $balance >= $this->amount
                ? $this->updateBalance($balance - $this->amount)
                : false,
            default => false,
        };
    }

    private function updateBalance(float $newBalance): bool
    {
        $_SESSION['balance'] = $newBalance;

        return true;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
    
}