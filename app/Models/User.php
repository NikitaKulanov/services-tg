<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Services\Telegram\DTO\UpdateMessage\Sender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_tg',
        'first_name',
        'last_name',
        'user_name',
        'language_code',
        'storage',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Save to json storage
     * Default cannot save an empty array
     *
     * @param array|string $value
     * @return void
     */
    public function setStorageAttribute(array|string $value): void
    {
        $this->attributes['storage'] = is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;
    }

    /**
     * Get from json storage
     *
     * @param string $value
     * @return array
     */
    public function getStorageAttribute(string $value): array
    {
        return json_decode($value, true);
    }

    /**
     * Store in storage
     *
     * @param array $payload
     * @return $this
     */
    public function saveToStorage(array $payload): User
    {
        $this->storage = array_merge($this->storage, $payload);
        $this->save();
        return $this;
    }

    /**
     * Get value from storage
     *
     * @param array|string $payload
     * @param null $default
     * @param string|null $key
     * @return array|string|int|bool|null
     */
    public function getFromStorage(
        array|string $payload,
        $default = null,
        ?string $key = null
    ): array|string|int|null|bool
    {
        if (is_string($payload)) {
            if (is_null($key)) {
                return $this->storage[$payload] ?? $default;
            } else {
                return $this->storage[$key][$payload] ?? $default;
            }
        } else {
            $array = [];
            if (is_null($key)) {
                foreach ($payload as $item) {
                    if (array_key_exists($item, $this->storage)) {
                        $array[$item] = $this->storage[$item];
                    } else {
                        $array[$item] = $default;
                    }
                }
            } else {
                foreach ($payload as $item) {
                    if (array_key_exists($item, $this->storage[$key])) {
                        $array[$item] = $this->storage[$key][$item];
                    } else {
                        $array[$item] = $default;
                    }
                }
            }
            return $array !== [] ? $array : $default;
        }
    }

    /**
     * @param User $user
     * @param string $key
     * @param array|string|int|null $value
     * @return User
     */
    public static function storeToStorage(
        User                  $user,
        string                $key,
        array|string|int|null $value = null
    ): User
    {
        $array = $user->storage;
        $array[$key] = $value;
        $user->storage = $array;
        $user->save();
        return $user;
    }

    /**
     * @param Sender $sender
     * @return User
     */
    public static function createFromSender(Sender $sender): User
    {
        return User::create([
            'id_tg' => $sender->id,
            'first_name' => $sender->firstName,
            'last_name' => $sender->lastName ?? null,
            'user_name' => $sender->userName ?? null,
            'language_code' => $sender->languageCode ?? null,
            'storage' => [],
        ]);
    }
}
