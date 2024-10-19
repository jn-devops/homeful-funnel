<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;
use App\States\ContactState;

/**
 * Class Contract
 *
 * @property string $id
 * @property string $mobile
 * @property ContactState $state
 *
 * @method int getKey()
 */
class Contact extends Model
{
    /** @use HasFactory<\Database\Factories\ContactFactory> */
    use HasFactory;
    use HasStates;
    use HasUuids;

    protected $casts = [
        'state' => ContactState::class
    ];

    public function getRouteKeyName(): string
    {
        return 'mobile';
    }
}
