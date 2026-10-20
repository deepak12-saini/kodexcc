<?php
declare(strict_types=1);

namespace App\Utility;

use Cake\Utility\Security;

/**
 * One-day link that lets a staff login set a new password.
 * The link stops working once the password changes or the day passes.
 */
class StaffPasswordReset
{
    public static function token(int $userId, string $passwordHash, ?int $now = null): string
    {
        $expires = ($now ?? time()) + 86400;
        $payload = $userId . '.' . $expires . '.' . self::stamp($passwordHash);
        $signed = $payload . '.' . hash_hmac('sha256', $payload, Security::getSalt());

        return rtrim(strtr(base64_encode($signed), '+/', '-_'), '=');
    }

    /**
     * @return array{id: int, stamp: string}|null
     */
    public static function read(string $token, ?int $now = null): ?array
    {
        $raw = base64_decode(strtr($token, '-_', '+/'), true);
        if ($raw === false) {
            return null;
        }
        $parts = explode('.', $raw);
        if (count($parts) !== 4) {
            return null;
        }
        [$id, $expires, $stamp, $signature] = $parts;
        $payload = $id . '.' . $expires . '.' . $stamp;
        $expected = hash_hmac('sha256', $payload, Security::getSalt());
        if (!hash_equals($expected, $signature)) {
            return null;
        }
        if (!ctype_digit($id) || !ctype_digit($expires) || (int)$expires < ($now ?? time())) {
            return null;
        }
        if (!preg_match('/^[a-f0-9]{20}$/', $stamp)) {
            return null;
        }

        return ['id' => (int)$id, 'stamp' => $stamp];
    }

    public static function stamp(string $passwordHash): string
    {
        return substr(hash_hmac('sha256', $passwordHash, Security::getSalt()), 0, 20);
    }
}
