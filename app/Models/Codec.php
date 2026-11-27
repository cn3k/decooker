<?php
class Codec
{
    private const HASHES = ['md5', 'sha1', 'sha256', 'sha512'];

    public static function formats(): array
    {
        return [
            'base64'    => 'base64',
            'base64url' => 'base64 url-safe / unpadded',
            'hex'       => 'hex',
            'url'       => 'url',
            'binary'    => 'binary',
            'html'      => 'html entities',
            'rot13'     => 'rot13',
            'md5'       => 'md5 (hash, encode only)',
            'sha1'      => 'sha-1 (hash, encode only)',
            'sha256'    => 'sha-256 (hash, encode only)',
            'sha512'    => 'sha-512 (hash, encode only)',
        ];
    }

    public static function label(string $format): string
    {
        return self::formats()[$format] ?? $format;
    }

    public static function canDecode(string $format): bool
    {
        return isset(self::formats()[$format]) && !in_array($format, self::HASHES, true);
    }

    public static function encode(string $format, string $text): string
    {
        switch ($format) {
            case 'base64':
                return base64_encode($text);
            case 'base64url':
                return rtrim(strtr(base64_encode($text), '+/', '-_'), '=');
            case 'hex':
                return bin2hex($text);
            case 'url':
                return rawurlencode($text);
            case 'binary':
                return implode(' ', array_map(
                    fn($char) => str_pad(decbin(ord($char)), 8, '0', STR_PAD_LEFT),
                    str_split($text)
                ));
            case 'html':
                return htmlentities($text, ENT_QUOTES, 'UTF-8');
            case 'rot13':
                return str_rot13($text);
            default: // md5, sha1, sha256, sha512
                return hash($format, $text);
        }
    }

    public static function decode(string $format, string $text): ?string
    {
        $text = trim($text);

        switch ($format) {
            case 'base64':
                $result = base64_decode(preg_replace('/\s+/', '', $text), true);
                return $result === false ? null : $result;

            case 'base64url':
                $clean = strtr(preg_replace('/\s+/', '', $text), '-_', '+/');
                $clean .= str_repeat('=', (4 - strlen($clean) % 4) % 4);
                $result = base64_decode($clean, true);
                return $result === false ? null : $result;

            case 'hex':
                $clean = preg_replace('/^0x|\s+/i', '', $text);
                if (!preg_match('/^([0-9a-f]{2})+$/i', $clean)) {
                    return null;
                }
                return hex2bin($clean);

            case 'binary':
                $clean = preg_replace('/\s+/', '', $text);
                if (!preg_match('/^([01]{8})+$/', $clean)) {
                    return null;
                }
                return implode(array_map(fn($byte) => chr(bindec($byte)), str_split($clean, 8)));

            case 'url':
                return urldecode($text);

            case 'html':
                return html_entity_decode($text, ENT_QUOTES, 'UTF-8');

            case 'rot13':
                return str_rot13($text);

            default:
                return null;
        }
    }

    public static function detect(string $text): ?array
    {
        $text = trim($text);

        foreach (['binary', 'hex', 'base64', 'url', 'base64url', 'html'] as $format) {
            if (!self::looksLike($format, $text)) {
                continue;
            }
            $output = self::decode($format, $text);
            if ($output !== null && $output !== '' && self::isReadable($output)) {
                return [$format, $output];
            }
        }
        return null;
    }

    private static function looksLike(string $format, string $text): bool
    {
        switch ($format) {
            case 'binary':
                return (bool) preg_match('/^[01\s]+$/', $text);
            case 'hex':
                return (bool) preg_match('/^(0x)?[0-9a-f\s]+$/i', $text);
            case 'base64':
                $clean = preg_replace('/\s+/', '', $text);
                return preg_match('/^[A-Za-z0-9+\/]+={0,2}$/', $clean) && strlen($clean) % 4 === 0;
            case 'base64url':
                return (bool) preg_match('/^[A-Za-z0-9_\-]+$/', $text);
            case 'url':
                return (bool) preg_match('/%[0-9a-f]{2}/i', $text);
            case 'html':
                return (bool) preg_match('/&(#\d+|#x[0-9a-f]+|[a-z]+);/i', $text);
        }
        return false;
    }

    private static function isReadable(string $text): bool
    {
        return preg_match('/^[\P{C}\t\r\n]+$/u', $text) === 1;
    }
}
