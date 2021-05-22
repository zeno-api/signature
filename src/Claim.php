<?php

declare(strict_types=1);

namespace Zeno\Signature;

use DateTime;
use DateTimeZone;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class Claim
{
    private string $targetPath;
    private ?array $body = [];
    private ?string $requestId;
    private ?DateTime $requestDate;

    public function __construct(string $targetPath, ?array $body = [], ?string $requestId = null, ?DateTime $requestDate = null)
    {
        $this->targetPath = $targetPath;
        $this->body = $body;
        $this->requestId = $requestId;
        $this->requestDate = $requestDate;

        if (null === $this->requestId) {
            $this->makeRequestId();
        }

        if (null === $this->requestDate) {
            $this->makeRequestDate();
        }
    }

    public function makeRequestId(): void
    {
        $this->requestId = (string) Str::uuid();
    }

    public function makeRequestDate(): void
    {
        $this->requestDate = new DateTime('now', new DateTimeZone('UTC'));
    }

    public function setRequestId(string $requestId): void
    {
        $this->requestId = $requestId;
    }

    public function setRequestDate(DateTime $requestDate): void
    {
        $this->requestDate = $requestDate;
    }

    public function getTargetPath(): string
    {
        return $this->targetPath;
    }

    public function getRequestId(): string
    {
        return $this->requestId;
    }

    public function getRequestDate(): DateTime
    {
        return $this->requestDate;
    }

    public function getRequestDateString(): string
    {
        $date = $this->requestDate->format('c');

        return substr($date, 0, strpos($date, '+')) . 'Z';
    }

    public function getBody(): ?array
    {
        return $this->body;
    }

    public function getBodyString(): ?string
    {
        return null !== $this->body ? json_encode($this->body) : null;
    }

    public function getDigest(): ?string
    {
        if (null === $this->getBodyString()) {
            return null;
        }

        return base64_encode(hash('sha256', $this->getBodyString(), true));
    }

    public function toArray(): array
    {
        return [
            $this->getRequestId(),
            $this->getRequestDateString(),
            $this->getTargetPath(),
            $this->getDigest(),
        ];
    }
}
