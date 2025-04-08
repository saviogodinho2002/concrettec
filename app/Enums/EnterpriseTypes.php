<?php

namespace App\Enums;

enum EnterpriseTypes: string
{
    case CONSTRUCTION = 'construcao';
    case CONCRETE = 'concreteira';

    public static function fromLabel(string $label): self
    {
        return match (strtolower($label)) {
            'construtora' => self::CONSTRUCTION,
            'construcao' => self::CONSTRUCTION,
            'concreteira' => self::CONCRETE,
            default => throw new \ValueError("\"$label\" não é um tipo válido")
        };
    }

    public function label(): string
    {
        return match($this) {
            self::CONSTRUCTION => 'Construtora',
            self::CONCRETE => 'Concreteira'
        };
    }
}
