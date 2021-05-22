<?php

declare(strict_types=1);

namespace Zeno\Signature;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class Signature
{
    private string $token;
    private string $clientId;
    private Claim $claim;

    public function __construct(string $token, string $clientId, Claim $claim)
    {
        $this->token = $token;
        $this->clientId = $clientId;
        $this->claim = $claim;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getHeaders(): array
    {
        return [
            'Client-Id'         => $this->clientId,
            'Request-Id'        => $this->claim->getRequestId(),
            'Request-Timestamp' => $this->claim->getRequestDateString(),
            'Signature'         => 'HMACSHA256='.$this->token,
        ];
    }

    public function __toString(): string
    {
        return $this->token;
    }
}
