<?php
/**
 * 加密解密token
 */

namespace Perry\MsgSwoole\Contracts;


use phpDocumentor\Reflection\Types\Integer;

interface EncryptionInterface
{
    //加密
    public function encrypt($receiver_id);

    //得到接收人id
    public function getReceiverId(string $ciphertext);
}