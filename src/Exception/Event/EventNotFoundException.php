<?php

declare(strict_types=1);

namespace App\Exception\Event;

use ApiPlatform\Metadata\ErrorResource;
use ApiPlatform\Metadata\Exception\ProblemExceptionInterface;

#[ErrorResource(
    outputFormats: ['jsonld' => ['application/ld+json']],
    normalizationContext: [
        'groups' => ['jsonld'],
        'skip_null_values' => true,
        'ignored_attributes' => ['trace', 'file', 'line', 'code', 'message', 'traceAsString', 'previous'],
    ],
)]
final class EventNotFoundException extends \Exception implements ProblemExceptionInterface
{
    public ?string $description = null;

    public function __construct(
        private readonly ?string $resourceClass,
        private readonly mixed $identifier,
    ) {
        $message = $resourceClass && $identifier
            ? \sprintf('The %s "%s" does not exist.', $resourceClass, $identifier)
            : 'Resource not found.';

        parent::__construct($message);
        $this->description = $message;
    }

    public function getType(): string
    {
        return '/errors/404';
    }

    public function getTitle(): string
    {
        return 'An error occurred';
    }

    public function getStatus(): int
    {
        return 404;
    }

    public function getDetail(): ?string
    {
        return null;
    }

    public function getInstance(): ?string
    {
        return null;
    }
}
