<?php

declare(strict_types=1);

namespace Zeno\Signature;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
final class Signer
{
    public function sign(string $clientId, string $clientSecret, Claim $claim): Signature
    {
        return new Signature(
            $this->hash(
                $clientSecret,
                implode('|', [$clientId, ...$claim->toArray()])
            ),
            $clientId,
            $claim,
        );
    }

    private function hash(string $secret, string $text): string
    {
        return hash_hmac('sha256', $text, $secret);
    }
}
