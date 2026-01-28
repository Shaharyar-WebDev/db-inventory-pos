<?php

namespace App\Enums;

enum PanelId: string
{
    case ADMIN = 'admin';
    case OUTLET = 'outlet';

    public function id(): string
    {
        return $this->value;
    }

    public function path(): string
    {
        return match ($this->value) {
            self::ADMIN->value => '',
            self::OUTLET->value => 'outlet',
        };
    }
}
