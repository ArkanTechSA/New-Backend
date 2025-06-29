<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'latest_name',
        'second_name',
        'third_name',
        'fourth_name',
        'full_name',
        'country',
        'city',
        'region',
        'email',
        'email_verified_at',
        'mobile_number',
        'otp',
        'mobile_verified_at',
        'address',
        'role',
        'is_old',
        'token',
        'photo',
        'last_login',
        'ip',
        'password',
        'lat',
        'long',
        'referral_code',
        'referred_by',
        'mobile_country_code',
        'gender',
        'nationality_id',
        'is_active',
        'referral_code',
        'referred_by',
        'json1',
        'json2',
        'json3',
        'json4',
        'json5',
        'column1',
        'column2',
        'column3',
        'column4',
        'column5',
        'longtext1',
        'longtext2',
        'longtext3',
        'longtext4',
        'longtext5',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'token',
        'otp',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'mobile_verified_at' => 'datetime',
        'last_login' => 'datetime',
        'is_old' => 'boolean',
        'lat' => 'decimal:7',
        'long' => 'decimal:7',
        'json1' => 'array',
        'json2' => 'array',
        'json3' => 'array',
        'json4' => 'array',
        'json5' => 'array',
        'referred_by' => 'integer',

    ];

    public const ROLE_LAWYER = 1;

    public const ROLE_CLIENT = 2;

    public const ROLE_ADMIN = 3;

    public const ROLE_SUPERVISOR = 4;

    /**
     * {@inheritDoc}
     */
    public function getJWTCustomClaims()
    {
        return [
            'user' => $this->only([
                'id',
                'first_name',
                'second_name',
                'third_name',
                'fourth_name',
                'latest_name',
                'full_name',
                'country',
                'city',
                'region',
                'email',
                'email_verified_at',
                'mobile_country_code',
                'gender',
                'nationality_id ',
                'mobile_number',
                'otp',
                'mobile_verified_at',
                'address',
                'refill_code',
                'role',
                'is_old',
                'token',
                'photo',
                'last_login',
                'ip',
                'is_active',
                'referral_code',
                'referred_by',
                'lat',
                'long',
                'json1',
                'json2',
                'json3',
                'json4',
                'json5',
                'column1',
                'column2',
                'column3',
                'column4',
                'column5',
                'longtext1',
                'longtext2',
                'longtext3',
                'longtext4',
                'longtext5',
            ]),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function isLawyer(): bool
    {
        return $this->role === self::ROLE_LAWYER;
    }

    public function isClient(): bool
    {
        return $this->role === self::ROLE_CLIENT;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function isSupervisor(): bool
    {
        return $this->role === self::ROLE_SUPERVISOR;
    }

    public function getRoleNameAttribute(): string
    {
        return match ($this->role) {
            self::ROLE_LAWYER => 'Lawyer',
            self::ROLE_CLIENT => 'Client',
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_SUPERVISOR => 'Supervisor',
            default => 'Unknown',
        };
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                $user->referral_code = self::generateReferralCode();
            }
        });
    }

    public static function generateReferralCode($length = 8)
    {
        do {
            $code = strtoupper(substr(bin2hex(random_bytes($length)), 0, $length));
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }

    public function nationality()
    {
        return $this->belongsTo(Nationality::class, 'nationality_id');
    }

    // public function country()
    // {
    //     return $this->belongsTo(Country::class, 'country', 'id');
    // }
    // public function region()
    // {
    //     return $this->belongsTo(Region::class, 'region');
    // }
    // public function city()
    // {
    //     return $this->belongsTo(City::class, 'city');
    // }

    public function countryRelation()
    {
        return $this->belongsTo(Country::class, 'country');
    }

    public function regionRelation()
    {
        return $this->belongsTo(Region::class, 'region');
    }

    public function cityRelation()
    {
        return $this->belongsTo(City::class, 'city');
    }

    public function isProfileComplete(): bool
    {
        $requiredFields = [
            'first_name',
            'email',
            'mobile_number',
            'gender',
            'country',
        ];

        foreach ($requiredFields as $field) {
            if (empty($this->$field)) {
                return false;
            }
        }

        return true;
    }
}
