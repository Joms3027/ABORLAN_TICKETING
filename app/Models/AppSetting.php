<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AppSetting extends Model
{
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['key', 'value'];

    public static function get(string $key, mixed $default = null): mixed
    {
        $row = static::query()->find($key);

        return $row?->value ?? $default;
    }

    public static function put(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        Cache::forget('app_setting:'.$key);
    }

    public static function getInt(string $key, int $default = 0): int
    {
        $value = static::get($key, $default);

        return is_numeric($value) ? (int) $value : $default;
    }
}
