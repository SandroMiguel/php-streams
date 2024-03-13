# PhpStreams

PhpStreams is a PHP library that provides stream handling following the PSR-7 standards.

## Features

-   **Stream Manipulation**: Read from and write to streams with ease.
-   **PSR-7 Compatibility**: Conforms to the [PSR-7](https://www.php-fig.org/psr/psr-7/) standards for interoperability.
-   **Flexible**: Offers a comprehensive set of features for stream manipulation.

## Installation

You can install PhpStreams via Composer:

```bash
composer require sandromiguel/php-streams
```

## Usage

#### Try it on replit.com

Try out the interactive examples on the replit.com platform:

[Run on replit.com](https://replit.com/@SandroMiguel/PhpStreams)

### Text Stream in Memory

```php
require 'vendor/autoload.php';

use PhpStreams\Stream;

// Create a text stream in memory
$stream = new Stream(fopen('php://temp', 'r+'));

// Write data to the stream
$stream->write("Hello, world!\n");

// Move the pointer to the beginning of the stream
$stream->rewind();

// Read data from the stream
$data = $stream->getContents();

echo $data;
```

Output example

```
Hello, world!
```

### Reading from a File

```php
require 'vendor/autoload.php';

use PhpStreams\Stream;

// Open a file for reading
$fileHandle = fopen('example.txt', 'r');

// Create a stream from the file handle
$fileStream = new Stream($fileHandle);

// Check if the stream is readable
$fileContents = $fileStream->isReadable() ? $fileStream->getContents() : null;

if ($fileContents) {
  echo $fileContents;
} else {
  echo "The file is not readable.";
}

// Close the file handle
fclose($fileHandle);
```

Output example (if example.txt contains "Hello, my name is example.txt"):

```
Hello, my name is example.txt
```

### Writing to a File

```php
require 'vendor/autoload.php';

use PhpStreams\Stream;

// Open a file for writing
$fileHandle = fopen('write.txt', 'w');

// Create a stream from the file handle
$fileStream = new Stream($fileHandle);

// Check if the stream is writable
$bytesWritten = $fileStream->isWritable() ? $fileStream->write('New text') : null;

if ($bytesWritten) {
  echo "Bytes written: $bytesWritten";
} else {
  echo "The file is not writable.";
}

// Close the file handle
fclose($fileHandle);
```

Output example (if writing is successful):

```
Bytes written: 8
```

### Reading a Specific Number of Bytes from a File

```php
require 'vendor/autoload.php';

use PhpStreams\Stream;

// Open a file for reading
$fileHandle = fopen('example.txt', 'r');

// Create a stream from the file handle
$fileStream = new Stream($fileHandle);

// Check if the stream is readable
if ($fileStream->isReadable()) {
    // Define the exact number of bytes to read
    $numBytesToRead = 6;

    // Read 10 bytes from the file
    $data = $fileStream->read($numBytesToRead);

    // Output the read data
    echo "Read $numBytesToRead bytes of data: $data\n";

    // Read the remaining content of the file
    $remainingData = $fileStream->getContents();

    // Output the remaining data
    echo "Remaining data: $remainingData";
} else {
    echo "The file is not readable.";
}

// Close the file handle
fclose($fileHandle);
```

Output example

```
Read 6 bytes of data: Hello,
Remaining data:  my name is example.txt
```

### Getting Stream Metadata

```php
require 'vendor/autoload.php';

use PhpStreams\Stream;

// Open a file for reading
$fileHandle = fopen('example.txt', 'r');

// Create a stream from the file handle
$fileStream = new Stream($fileHandle);

// Get metadata of the stream
$metadata = $fileStream->getMetadata();

// Output the metadata
echo "Stream metadata:\n";
print_r($metadata);

// Close the file handle
fclose($fileHandle);
```

Output example

```
Stream metadata:
Array
(
    [timed_out] =>
    [blocked] => 1
    [eof] =>
    [wrapper_type] => plainfile
    [stream_type] => STDIO
    [mode] => r
    [unread_bytes] => 0
    [seekable] => 1
    [uri] => example.txt
)
```

## Contributing

Want to contribute? All contributions are welcome. Read the [contributing guide](CONTRIBUTING.md).

## Questions

If you have questions tweet me at [@sandro_m_m](https://twitter.com/sandro_m_m) or [open an issue](../../issues/new).

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details

**~ sharing is caring ~**
