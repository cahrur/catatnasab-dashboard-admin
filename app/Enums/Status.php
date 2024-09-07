<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Status: string implements HasLabel
{
    case Pending = 'pending';
    case Unpaid = 'unpaid';
    case Paid = 'paid';
    case Cancel = 'cancel';
    
    public function getLabel(): ?string
    {
        return $this->name;
        
        // or
    
        return match ($this) {
            self::Pending => 'Pending',
            self::Unpaid => 'Unpaid',
            self::Paid => 'Paid',
            self::Cancel => 'Cancel',
        };
    }
}