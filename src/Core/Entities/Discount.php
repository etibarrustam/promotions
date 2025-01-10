<?php

namespace App\Core\Entities;

class Discount
{
    public function __construct(
        private int $id,
        private string $type,
        private int $targetId,
        private int $percentage
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getTargetId(): int
    {
        return $this->targetId;
    }

    public function getPercentage(): int
    {
        return $this->percentage;
    }
}
