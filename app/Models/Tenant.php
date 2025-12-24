<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasUlids, SoftDeletes, HasFactory;

    /**
     * Get the current tenant from cache or database.
     */
    public static function current(): ?self
    {
        if (!auth()->check() || !auth()->user()->tenant_id) {
            return null;
        }

        $tenantId = auth()->user()->tenant_id;

        return \Illuminate\Support\Facades\Cache::remember("tenant_{$tenantId}", 3600, function () use ($tenantId) {
            return self::find($tenantId);
        });
    }

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'title',
        'description',
        'doctor_name',
        'doctor_qualification',
        'doctor_specialty',
        'domain',
        'address',
        'phone',
        'email',
        'subscription_plan',
        'subscription_status',
        'subscription_ends_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subscription_ends_at' => 'date',
    ];

    /**
     * Get the users for the tenant.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // Add other relationships as they are created (patients, visits, etc.)
}
