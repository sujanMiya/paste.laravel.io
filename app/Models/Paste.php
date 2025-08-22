<?php

namespace App\Models;

use App\Enums\ProtectedPasteEnum;
use Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class Paste extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pastes';
    /**
     *
     * @var array
     */
    protected $casts = [
        'is_protected' => ProtectedPasteEnum::class,
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'hash',
        'author_id',
        'parent_id',
        'ip',
        'is_protected'
    ];
    public function isProtected(): bool
    {
        return $this->is_protected === ProtectedPasteEnum::PROTECTED;
    }

    public function isPublic(): bool
    {
        return $this->is_protected === ProtectedPasteEnum::PUBLIC;
    }
    public static function fromRequest(Request $request): self
    {
        return static::createNew(new static, $request);
    }

    public static function fromFork(self $fork, Request $request): self
    {
        $paste = new static;
        $paste->parent_id = $fork->id;

        return static::createNew($paste, $request);
    }

    private static function createNew(self $paste, Request $request): self
    {
        $paste->code = $request->get('code');
        $paste->hash = Uuid::uuid4()->toString();
        if ($request->filled('password')) {
            $paste->setPassword($request->get('password'));
            $paste->is_protected = ProtectedPasteEnum::PROTECTED;
        } else {
            $paste->password = null;
            $paste->is_protected = ProtectedPasteEnum::PUBLIC;
        }
        $paste->save();

        return $paste;
    }
    /**
     * Securely set the password using bcrypt hashing
     *
     * @param string $password
     * @return void
     */
    public function setPassword(string $password): void
    {
        if (strlen($password) < 3) {
            throw new \InvalidArgumentException('Password must be at least 3 characters long');
        }

        if (strlen($password) > 255) {
            throw new \InvalidArgumentException('Password is too long');
        }

        $this->password = Hash::make($password);
    }
    /**
     * Check if the provided password is correct
     *
     * @param string $password
     * @return bool
     */
    public function checkPassword(string $password): bool
    {
        if (!$this->isProtected() || !$this->password) {
            return false;
        }

        return Hash::check($password, $this->password);
    }
    public function scopePublic($query)
    {
        return $query->where('is_protected', ProtectedPasteEnum::PUBLIC);
    }

    public function scopeProtected($query)
    {
        return $query->where('is_protected', ProtectedPasteEnum::PROTECTED);
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'hash';
    }
}
