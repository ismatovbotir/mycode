<?php
// app/Models/IntegrationField.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['integration_type', 'field_key', 'label', 'type', 'placeholder', 'help_text', 'is_required', 'sort_order', 'is_active'])]
class IntegrationField extends Model
{
    use HasUuids;

    protected $casts = [
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
