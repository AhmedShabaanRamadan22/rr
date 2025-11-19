<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiClient extends Model
{
    use HasFactory;

    protected $table = 'api_clients';
    protected $fillable = [
        'name',
        'token',
        'allowed_ips',
        'scopes',
        'active',
    ];

    protected $casts = [
        'allowed_ips' => 'array',
    ];

    public function scopeToken($query, $token)
    {
        return $query->where('token', $token);
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function isIpAllowed(?string $ip): bool
    {
        // Support for CIDR notation (e.g. 192.168.1.0/24)
        foreach ($this->allowed_ips??[] as $allowed) {
            if ($this->ipMatches($ip, $allowed)) {
                return true;
            }
        }

        return false;
    }

    protected function ipMatches(string $ip, string $allowed): bool
    {
        if (str_contains($allowed, '/')) {
            return $this->cidrMatch($ip, $allowed);
        }

        return $ip === $allowed;
    }

    protected function cidrMatch(string $ip, string $cidr): bool
    {
        [$subnet, $mask] = explode('/', $cidr);
        return (ip2long($ip) & ~((1 << (32 - $mask)) - 1)) === ip2long($subnet);
    }

}
