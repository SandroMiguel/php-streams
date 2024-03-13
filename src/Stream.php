<?php

/**
 * Stream
 *
 * @package PhpStreams
 * @license MIT https://github.com/SandroMiguel/php-streams/blob/main/LICENSE
 * @author Sandro Miguel Marques <sandromiguel@sandromiguel.com>
 * @link https://github.com/SandroMiguel/php-streams
 * @version 1.0.0 (2024-03-12)
 */

declare(strict_types=1);

namespace PhpStreams;

use Psr\Http\Message\StreamInterface;

/**
 * Stream class.
 */
class Stream implements StreamInterface
{
    /** @var resource|null Wrapped resource */
    private $resource;

    /**
     * Constructor.
     *
     * @param resource|bool $resource Resource to wrap.
     *
     * @throws \PhpStreams\Exceptions\InvalidStreamException If the resource is
     *  not a stream.
     */
    public function __construct($resource)
    {
        if (
            !\is_resource($resource)
            || \get_resource_type($resource) !== 'stream'
        ) {
            $resourceType = \is_resource($resource)
                ? \get_resource_type($resource)
                : 'non-resource';

            throw new \PhpStreams\Exceptions\InvalidStreamException(
                \sprintf(
                    'Invalid or non-stream resource provided. Provided resource type: %s',
                    $resourceType
                )
            );
        }

        $this->resource = $resource;
    }

    /**
     * Returns whether or not the stream is seekable.
     *
     * @return bool Returns true if the stream is seekable, false otherwise.
     */
    public function isSeekable(): bool
    {
        if (!$this->resource) {
            return false;
        }

        $meta = \stream_get_meta_data($this->resource);

        return $meta['seekable'];
    }

    /**
     * Returns whether or not the stream is writable.
     *
     * @return bool Returns true if the stream is writable, false otherwise.
     */
    public function isWritable(): bool
    {
        if (!$this->resource) {
            return false;
        }

        $meta = \stream_get_meta_data($this->resource);
        $mode = $meta['mode'];

        return \strstr($mode, 'x')
            || \strstr($mode, 'w')
            || \strstr($mode, 'c')
            || \strstr($mode, 'a')
            || \strstr($mode, '+');
    }

    /**
     * Returns whether or not the stream is readable.
     *
     * @return bool Returns true if the stream is readable, false otherwise.
     */
    public function isReadable(): bool
    {
        if (!$this->resource) {
            return false;
        }

        $meta = \stream_get_meta_data($this->resource);
        $mode = $meta['mode'];

        return \strstr($mode, 'r') || \strstr($mode, '+');
    }

    /**
     * Read data from the stream.
     *
     * @param int $length Read up to $length bytes from the object and return
     *  them. Fewer than $length bytes may be returned if underlying stream
     *  call returns fewer bytes.
     *
     * @return string Returns the data read from the stream, or an empty string
     *  if no bytes are available.
     *
     * @throws \PhpStreams\Exceptions\ReadException If an error occurs.
     */
    public function read(int $length): string
    {
        if (!$this->resource) {
            throw new \PhpStreams\Exceptions\ReadException(
                'Unable to read from stream: resource is not available.'
            );
        }

        if (!$this->isReadable()) {
            throw new \PhpStreams\Exceptions\ReadException(
                'Stream is not readable: unable to read from stream.'
            );
        }

        $length = \max(0, $length);

        $result = \fread($this->resource, $length);

        if ($result === false) {
            throw new \PhpStreams\Exceptions\ReadException(
                'Error reading from stream: unable to read data.'
            );
        }

        return $result;
    }

    /**
     * Write data to the stream.
     *
     * @param string $string The string that is to be written.
     *
     * @return int Returns the number of bytes written to the stream.
     *
     * @throws \PhpStreams\Exceptions\WriteException If an error occurs.
     */
    public function write(string $string): int
    {
        if (!$this->resource) {
            throw new \PhpStreams\Exceptions\WriteException();
        }

        if (!$this->isWritable()) {
            throw new \PhpStreams\Exceptions\WriteException(
                'Stream is not writable'
            );
        }

        $result = \fwrite($this->resource, $string);

        if ($result === false) {
            throw new \PhpStreams\Exceptions\WriteException(
                'Unable to write data to stream.'
            );
        }

        return $result;
    }

    /**
     * Closes the stream and any underlying resources.
     */
    public function close(): void
    {
        if (!$this->resource) {
            return;
        }

        if (!\is_resource($this->resource)) {
            return;
        }

        \fclose($this->resource);
    }

    /**
     * Returns true if the stream is at the end of the stream.
     *
     * @return bool Returns true if the stream is at the end of the stream.
     */
    public function eof(): bool
    {
        if (!$this->resource) {
            return true;
        }

        return \feof($this->resource);
    }

    /**
     * Returns the current position of the file read/write pointer
     *
     * @return int Position of the file pointer.
     *
     * @throws \RuntimeException On error.
     */
    public function tell(): int
    {
        if (!$this->resource) {
            throw new \PhpStreams\Exceptions\ReadException(
                'Unable to read from stream: resource is not available.'
            );
        }

        $result = \ftell($this->resource);

        if ($result === false) {
            throw new \RuntimeException('Unable to determine stream position');
        }

        return $result;
    }

    /**
     * Seek to the beginning of the stream.
     *
     * If the stream is not seekable, this method will raise an exception;
     * otherwise, it will perform a seek(0).
     *
     * @throws \PhpStreams\Exceptions\ReadException If resource is not
     *  available.
     * @throws \PhpStreams\Exceptions\SeekException If stream is not seekable.
     */
    public function rewind(): void
    {
        if (!$this->resource) {
            throw new \PhpStreams\Exceptions\ReadException(
                'Unable to read from stream: resource is not available.'
            );
        }

        if (!$this->isSeekable()) {
            throw new \PhpStreams\Exceptions\SeekException(
                'Stream is not seekable.'
            );
        }

        $result = \fseek($this->resource, 0, \SEEK_SET);

        if ($result === -1) {
            throw new \PhpStreams\Exceptions\SeekException(
                'Unable to rewind stream.'
            );
        }
    }

    /**
     * Get the size of the stream if known.
     *
     * @return int|null Returns the size in bytes if known, or null if unknown.
     */
    public function getSize(): ?int
    {
        if (!$this->resource) {
            return null;
        }

        $stats = \fstat($this->resource);

        return $stats['size'] ?? null;
    }

    /**
     * Returns the remaining contents in a string.
     *
     * @return string Returns the remaining contents in the stream as a string.
     *
     * @throws \PhpStreams\Exceptions\ReadException If error occurs.
     */
    public function getContents(): string
    {
        if (!$this->resource) {
            throw new \PhpStreams\Exceptions\ReadException(
                'Unable to read from stream: resource is not available.'
            );
        }

        if (\get_resource_type($this->resource) !== 'stream') {
            throw new \PhpStreams\Exceptions\ReadException(
                'Unable to read from stream: supplied resource is not a valid stream resource.'
            );
        }

        $result = \stream_get_contents($this->resource);

        if ($result === false) {
            throw new \PhpStreams\Exceptions\ReadException();
        }

        return $result;
    }

    /**
     * Separates any underlying resources from the stream.
     *
     * @return resource|null Underlying PHP stream, if any.
     */
    public function detach()
    {
        $detachedResource = $this->resource;
        $this->resource = null;

        return $detachedResource;
    }

    /**
     * Seek to a position in the stream.
     *
     * @param int $offset Stream offset
     * @param int $whence Specifies how the cursor position will be calculated
     *  based on the seek offset. Valid values are identical to the built-in
     *  PHP $whence values for `fseek()`.  SEEK_SET: Set position equal to
     *  offset bytes SEEK_CUR: Set position to current location plus offset
     *  SEEK_END: Set position to end-of-stream plus offset.
     *
     * @throws \RuntimeException On failure.
     * @throws \InvalidArgumentException If $whence is invalid.
     */
    public function seek(int $offset, int $whence = \SEEK_SET): void
    {
        if (!$this->resource) {
            throw new \RuntimeException('Unable to read from stream');
        }

        if (!$this->isSeekable()) {
            throw new \RuntimeException('Stream is not seekable');
        }
        if ($offset < 0) {
            throw new \InvalidArgumentException(
                'Invalid seek offset: must be non-negative'
            );
        }

        $result = \fseek($this->resource, $offset, $whence);

        if ($result === -1) {
            throw new \RuntimeException('Unable to seek stream');
        }
    }

    /**
     * Get stream metadata as an associative array or retrieve a specific key.
     *
     * The keys returned are identical to the keys returned from PHP's
     * stream_get_meta_data() function.
     *
     * @param string $key Specific metadata to retrieve.
     *
     * @return array|mixed|null Returns an associative array if no key is
     *  provided. Returns a specific key value if a key is provided and the
     *  value is found, or null if the key is not found.
     */
    public function getMetadata(?string $key = null): mixed
    {
        if (!$this->resource) {
            return null;
        }

        $meta = \stream_get_meta_data($this->resource);

        if ($key === null) {
            return $meta;
        }

        return $meta[$key] ?? null;
    }

    /**
     * Reads all data from the stream into a string, from the beginning to end.
     *
     * @return string Contents of the stream.
     */
    public function __toString(): string
    {
        if ($this->isSeekable()) {
            $this->rewind();
        }

        return $this->getContents();
    }
}
