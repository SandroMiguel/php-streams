<?php

/**
 * InvalidStreamException
 *
 * @package PhpStreams
 * @license MIT https://github.com/SandroMiguel/php-streams/blob/main/LICENSE
 * @author Sandro Miguel Marques <sandromiguel@sandromiguel.com>
 * @link https://github.com/SandroMiguel/php-streams
 * @version 1.0.0 (2024-03-13)
 */

declare(strict_types=1);

namespace PhpStreams\Exceptions;

/**
 * Exception thrown when an invalid stream resource is provided.
 */
final class InvalidStreamException extends \PhpStreams\Exceptions\StreamException
{
    /**
     * Constructor.
     *
     * @param string $message The exception message.
     * @param int $code The exception code.
     * @param \Throwable|null $previous The previous exception.
     */
    public function __construct(
        string $message = 'Invalid or non-stream resource provided.',
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct(
            $message,
            $code,
            $previous
        );
    }
}
