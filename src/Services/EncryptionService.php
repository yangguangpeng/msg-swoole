<?php


namespace Perry\MsgSwoole\Services;

use Perry\MsgSwoole\Contracts\EncryptionInterface;
use phpDocumentor\Reflection\Types\Integer;

class EncryptionService implements EncryptionInterface
{
    public function __construct()
    {
        $this->key = config('jwt_key');
    }

    public function encrypt($receiver_id)
    {
        $payload = array(
            "data" => [
                "id" => $receiver_id,
            ],
            "iss" => "http://example.org",
            "sub" => "1234567890",
        );

        return jwt_encode($payload, $this->key);

    }

    public function getReceiverId(string $ciphertext)
    {
        return $this->decrypt($ciphertext)['data']['id']??'';
    }

    protected function decrypt(string $ciphertext)
    {
        return jwt_decode($ciphertext, $this->key);
    }
}