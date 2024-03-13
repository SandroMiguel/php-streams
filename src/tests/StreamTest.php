<?php

/**
 * StreamTest
 *
 * @package PhpStreams
 * @license MIT https://github.com/SandroMiguel/php-payload/blob/main/LICENSE
 * @author Sandro Miguel Marques <sandromiguel@sandromiguel.com>
 * @link https://github.com/SandroMiguel/php-payload
 * @version 1.0.0 (2024-03-10)
 */

declare(strict_types=1);

namespace PhpStreams\Tests;

use PhpStreams\Stream;
use PHPUnit\Framework\TestCase;

/**
 * Test for the `Stream` class.
 */
class StreamTest extends TestCase
{
    /**
     * Test that the stream can be constructed with a valid resource.
     */
    public function testConstructorWithValidResource(): void
    {
        $resource = \fopen('php://temp', 'r+');
        $stream = new Stream($resource);
        $this->assertInstanceOf(Stream::class, $stream);
    }

    /**
     * Test that the stream cannot be constructed with an invalid resource.
     */
    public function testConstructorWithInvalidResource(): void
    {
        $this->expectException(
            \PhpStreams\Exceptions\InvalidStreamException::class
        );
        $this->expectExceptionMessage(
            'Invalid or non-stream resource provided. Provided resource type: non-resource'
        );

        new Stream('invalid_resource');
    }

    /**
     * Test that the stream is seekable.
     */
    public function testIsSeekableReturnsTrueForSeekableStream(): void
    {
        $resource = \fopen('php://temp', 'wb+');
        $stream = new Stream($resource);

        $this->assertTrue($stream->isSeekable());
    }

    /**
     * Test that the stream is not seekable.
     */
    public function testIsSeekableReturnsFalseForNonSeekableStream(): void
    {
        $resource = \fopen('php://stdin', 'r');
        $stream = new Stream($resource);
        $result = $stream->isSeekable();
        $this->assertFalse($result);
    }

    /**
     * Test that the stream is writable.
     */
    public function testIsWritableReturnsTrueForWritableStream(): void
    {
        $resource = \fopen('php://temp', 'wb+');
        $stream = new Stream($resource);

        $this->assertTrue($stream->isWritable());
    }

    /**
     * Test that the stream is not writable.
     */
    public function testIsWritableReturnsFalseForNonWritableStream(): void
    {
        $resource = \fopen('php://stdin', 'r');
        $stream = new Stream($resource);

        $this->assertFalse($stream->isWritable());
    }

    /**
     * Test that the stream is readable.
     */
    public function testIsReadableReturnsTrueForReadableStream(): void
    {
        $resource = \fopen('php://temp', 'rb');
        $stream = new Stream($resource);

        $this->assertTrue($stream->isReadable());
    }

    /**
     * Test that the stream is not readable.
     */
    public function testIsReadableReturnsFalseForNonReadableStream(): void
    {
        $resource = \fopen('php://stdout', 'w');
        $stream = new Stream($resource);

        $this->assertFalse($stream->isReadable());
    }

    /**
     * Test that the read method reads data from the stream.
     *
     * @throws \RuntimeException If an error occurs.
     */
    public function testReadReadsDataFromStream(): void
    {
        $data = 'Hello, world!';
        $resource = \fopen('php://memory', 'wb+');
        if (!$resource) {
            throw new \RuntimeException('Unable to open memory stream');
        }

        \fwrite($resource, $data);
        \rewind($resource);
        $stream = new Stream($resource);

        $readData = $stream->read(13);

        $this->assertEquals($data, $readData);
    }

    /**
     * Test that the read method returns an empty string when the stream is at
     *  the end.
     *
     * @throws \RuntimeException If an error occurs.
     */
    public function testReadReturnsEmptyStringWhenStreamAtEnd(): void
    {
        $resource = \fopen('php://memory', 'wb+');
        if (!$resource) {
            throw new \RuntimeException('Unable to open memory stream');
        }

        \fwrite($resource, 'Hello, world!');
        \rewind($resource);
        $stream = new Stream($resource);

        $stream->read(13);
        $readData = $stream->read(1);

        $this->assertEquals('', $readData);
    }

    /**
     * Test that the read method throws an exception when the stream is not
     *  readable.
     */
    public function testReadThrowsExceptionWhenStreamNotReadable(): void
    {
        $resource = \fopen('php://stdout', 'w');
        $stream = new Stream($resource);

        $this->expectException(\PhpStreams\Exceptions\ReadException::class);
        $this->expectExceptionMessage(
            'Stream is not readable: unable to read from stream.'
        );
        $stream->read(1);
    }

    /**
     * Test to ensure that ReadException is thrown when the stream is not
     *  readable.
     */
    public function testReadFromUnreadableStreamThrowsException(): void
    {
        $this->expectException(\PhpStreams\Exceptions\ReadException::class);
        $this->expectExceptionMessage('Stream is not readable');

        $stream = new Stream(\fopen('php://output', 'w'));
        // Attempt to read from a non-readable stream
        $stream->read(1024);
    }

    /**
     * Test that the write method writes data to the stream.
     *
     * @throws \RuntimeException If an error occurs.
     */
    public function testWriteWritesDataToStream(): void
    {
        $data = 'Hello, world!';
        $resource = \fopen('php://memory', 'wb+');
        $stream = new Stream($resource);

        $bytesWritten = $stream->write($data);

        $this->assertEquals(\strlen($data), $bytesWritten);

        if (!$resource) {
            throw new \RuntimeException('Unable to open memory stream');
        }

        \rewind($resource);
        $readData = \fread($resource, \strlen($data));

        $this->assertEquals($data, $readData);
    }

    /**
     * Test that the write method throws an exception when the stream is not
     *  writable.
     */
    public function testWriteThrowsExceptionWhenStreamNotWritable(): void
    {
        // Arrange
        $resource = \fopen('php://stdin', 'r');
        $stream = new Stream($resource);

        // Assert
        $this->expectException(\PhpStreams\Exceptions\WriteException::class);
        $this->expectExceptionMessage('Stream is not writable');

        // Act
        $stream->write('Hello, world!');
    }

    /**
     * Test that the close method closes the stream.
     */
    public function testCloseClosesStream(): void
    {
        $resource = \fopen('php://memory', 'wb+');
        $stream = new Stream($resource);

        $stream->close();

        $this->assertFalse(\is_resource($resource));
    }

    /**
     * Test that the close method does not throw an exception when the stream
     *  is already closed.
     */
    public function testCloseDoesNotThrowException(): void
    {
        $resource = \fopen('php://memory', 'wb+');
        $stream = new Stream($resource);

        $stream->close();
        $stream->close();

        // No exception thrown
        $this->assertTrue(true);
    }

    /**
     * Test that the eof method returns true when the stream is at the end of
     *  the file.
     *
     * @throws \RuntimeException If an error occurs.
     */
    public function testEofReturnsTrueWhenStreamAtEof(): void
    {
        $data = 'Hello, world!';
        $resource = \fopen('php://temp', 'wb+');

        if (!$resource) {
            throw new \RuntimeException('Unable to open memory stream');
        }

        \fwrite($resource, $data);
        \rewind($resource);
        $stream = new Stream($resource);

        // Read data in a loop until the end of the file
        while (!$stream->eof()) {
            $stream->read(1024);
        }

        $this->assertTrue($stream->eof());
    }

    /**
     * Test that the eof method returns false when the stream is not at the
     *  end of the file.
     *
     * @throws \RuntimeException If an error occurs.
     */
    public function testEofReturnsFalseWhenStreamNotAtEof(): void
    {
        $data = 'Hello, world!';
        $resource = \fopen('php://temp', 'wb+');

        if (!$resource) {
            throw new \RuntimeException('Unable to open memory stream');
        }

        \fwrite($resource, $data);
        \rewind($resource);
        $stream = new Stream($resource);

        $stream->read(\strlen($data) - 1);

        $this->assertFalse($stream->eof());
    }

    /**
     * Test that the tell method returns the current position of the stream.
     *
     * @throws \RuntimeException If an error occurs.
     */
    public function testTellReturnsCurrentPosition(): void
    {
        $data = 'Hello, world!';
        $resource = \fopen('php://temp', 'wb+');

        if (!$resource) {
            throw new \RuntimeException('Unable to open memory stream');
        }

        \fwrite($resource, $data);
        \rewind($resource);
        $stream = new Stream($resource);

        // Read 5 bytes
        $stream->read(5);

        $this->assertEquals(5, $stream->tell());
    }

    /**
     * Test that the getSize method returns the size of the stream.
     *
     * @throws \RuntimeException If an error occurs.
     */
    public function testGetSizeReturnsSizeOfTheStream(): void
    {
        $data = 'Hello, world!';
        $resource = \fopen('php://temp', 'wb+');

        if (!$resource) {
            throw new \RuntimeException('Unable to open memory stream');
        }

        \fwrite($resource, $data);
        \rewind($resource);
        $stream = new Stream($resource);

        $expectedSize = \strlen($data);
        $actualSize = $stream->getSize();

        $this->assertEquals($expectedSize, $actualSize);
    }

    /**
     * Test that the getContents method returns the contents of the stream.
     *
     * @throws \RuntimeException If an error occurs.
     */
    public function testGetContentsReturnsContentsOfTheStream(): void
    {
        $data = 'Hello, world!';
        $resource = \fopen('php://temp', 'wb+');

        if (!$resource) {
            throw new \RuntimeException('Unable to open memory stream');
        }

        \fwrite($resource, $data);
        \rewind($resource);
        $stream = new Stream($resource);

        $expectedContents = $data;
        $actualContents = $stream->getContents();

        $this->assertEquals($expectedContents, $actualContents);
    }

    /**
     * Test that the detach method detaches the underlying resource.
     */
    public function testDetachDetachesUnderlyingResource(): void
    {
        $resource = \fopen('php://temp', 'wb+');
        $stream = new Stream($resource);

        // Verify resource is attached initially
        $this->assertIsResource($resource);

        $stream->detach();

        // Expect null after detach
        $this->assertNull($stream->getSize());
    }

    /**
     * Test that seeking to the beginning of the stream works.
     */
    public function testSeekToBeginning(): void
    {
        $resource = \fopen('php://temp', 'wb+');
        $stream = new Stream($resource);

        $stream->seek(0);

        // Verify position after seek
        $this->assertEquals(0, $stream->tell());
    }

    /**
     * Test that getMetadata returns an array on success.
     */
    public function testGetMetadataReturnsArray(): void
    {
        $resource = \fopen('php://temp', 'r');
        $stream = new Stream($resource);

        $metadata = $stream->getMetadata();

        $this->assertIsArray($metadata);
    }

    /**
     * Test that __toString returns a string representing the stream contents.
     *
     * @throws \RuntimeException If an error occurs.
     */
    public function testToStringReturnsStreamContents(): void
    {
        $resource = \fopen('php://temp', 'w+');

        if (!$resource) {
            throw new \RuntimeException('Unable to open memory stream');
        }

        \fwrite($resource, 'This is some text.');
        \rewind($resource);

        $stream = new Stream($resource);

        $this->assertEquals('This is some text.', $stream->__toString());
    }
}
