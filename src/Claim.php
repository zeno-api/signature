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
    private $body;
    private ?DateTime $requestDate;

    public function __construct(string $targetPath, $body, ?DateTime $requestDate = null)
    {
        $this->targetPath = $targetPath;
        $this->requestDate = $requestDate;

        if (null === $this->requestDate) {
            $this->makeRequestDate();
        }

        if (is_array($body)) {
            $this->body = json_encode($body);
        } else {
            $this->body = $body;
        }
    }

    public function makeRequestDate(): void
    {
        $this->requestDate = new DateTime('now', new DateTimeZone('UTC'));
    }

    public function setRequestDate(DateTime $requestDate): void
    {
        $this->requestDate = $requestDate;
    }

    public function getTargetPath(): string
    {
        return $this->targetPath;
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

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function getDigest(): ?string
    {
        if (null === $this->getBody()) {
            return null;
        }

        return base64_encode(hash('sha256', $this->getBody(), true));
    }

    public function toArray(): array
    {
        return [
            $this->getRequestDateString(),
            $this->getTargetPath(),
            $this->getDigest(),
        ];
    }
}
