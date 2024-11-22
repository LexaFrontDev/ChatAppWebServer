<?php


namespace App\Service\EncryptMessages;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsService]
class EncryptionService
{
    private string $publicKeyPath;

    public function __construct(ParameterBagInterface $params)
    {
        $this->publicKeyPath = $params->get('jwt_public_key');
    }


    public function encryptMessage(string $message): array
    {

        $secretKey = file_get_contents($this->publicKeyPath);

        if ($secretKey === false) {
            throw new \RuntimeException('Не удалось прочитать публичный ключ.');
        }

        $iv = openssl_random_pseudo_bytes(16);
        $encryptedMessage = openssl_encrypt($message, 'aes-256-cbc', $secretKey, 0, $iv);

        return [
            'encrypted_message' => $encryptedMessage,
            'iv' => base64_encode($iv),
        ];
    }

    public function decryptMessage(string $encryptedMessage, string $iv): string
    {

        $secretKey = file_get_contents($this->publicKeyPath);

        if ($secretKey === false) {
            throw new \RuntimeException('Не удалось прочитать публичный ключ.');
        }

        $iv = base64_decode($iv);
        return openssl_decrypt($encryptedMessage, 'aes-256-cbc', $secretKey, 0, $iv);
    }
}
