# Changelog

## [1.2.0](https://github.com/SandroMiguel/php-streams/compare/v1.1.0...v1.2.0) (2024-03-14)


### Features

* **Stream.php:** implemented caching of stream metadata to improve performance ([f68badf](https://github.com/SandroMiguel/php-streams/commit/f68badf0aa722841c7b8fae757a006462bb113db))
* **Stream.php:** refactor resource availability check to avoid code duplication ([d7c888d](https://github.com/SandroMiguel/php-streams/commit/d7c888d32263f176d6b49db2341c52f5accc2da4))

## [1.1.0](https://github.com/SandroMiguel/php-streams/compare/v1.0.0...v1.1.0) (2024-03-13)


### Features

* **ReadException.php:** new class for handling read errors ([48783a8](https://github.com/SandroMiguel/php-streams/commit/48783a82efb93a31ccccd7d6559b5df4ac95567c))
* **SeekException.php:** updated rewind method with specific exceptions, introducing SeekException ([14a6039](https://github.com/SandroMiguel/php-streams/commit/14a603948fb44afb18814c36c30c45c97e59058a))
* **Stream.php:** improve error handling in getContents method ([97ffe10](https://github.com/SandroMiguel/php-streams/commit/97ffe10c2c87370d1b0dcffb3eacb1279629afa9))
* **Stream.php:** improve Stream constructor error handling ([40e975f](https://github.com/SandroMiguel/php-streams/commit/40e975f25454d12345b961182288045d8ded4f2d))
* **Stream.php:** refactored exception handling in the seek() to use specific exception classes ([8021d80](https://github.com/SandroMiguel/php-streams/commit/8021d80d634ca23f2aa16a806e5848c08f4abcf6))
* **Stream.php:** refactored getContents method to include custom exception handling ([7d951e0](https://github.com/SandroMiguel/php-streams/commit/7d951e07e92852596fb4d7dc366f4a564dd9b5a1))
* **WriteException.php:** implemented WriteException for improved write error handling ([e367395](https://github.com/SandroMiguel/php-streams/commit/e367395d059403464e843bac75bc1c40cd148ec3))

## 1.0.0 (2024-03-12)


### Features

* initial commit ([8254d3e](https://github.com/SandroMiguel/php-streams/commit/8254d3ed9519edd23e24431e67900a5cf947c994))

## [2.0.3](https://github.com/SandroMiguel/php-sceleto/compare/v2.0.2...v2.0.3) (2024-03-05)


### Bug Fixes

* **deps:** update dependecies ([31ba324](https://github.com/SandroMiguel/php-sceleto/commit/31ba32425a15af0fb0957f364596f849ad18d561))

## [2.0.2](https://github.com/SandroMiguel/php-sceleto/compare/v2.0.1...v2.0.2) (2023-10-27)


### Bug Fixes

* **composer.json:** fix phpmetrics report path ([2984432](https://github.com/SandroMiguel/php-sceleto/commit/298443256460686142d4d9f765c0ad5111e5a137))

## [2.0.1](https://github.com/SandroMiguel/php-sceleto/compare/v2.0.0...v2.0.1) (2023-10-17)


### Bug Fixes

* **phpunit.xml.dist:** fix phpunit configuration ([fd07d01](https://github.com/SandroMiguel/php-sceleto/commit/fd07d0118fccac09d94f88c414af056d505faada))

## [2.0.0](https://github.com/SandroMiguel/php-sceleto/compare/v1.0.0...v2.0.0) (2023-10-10)


### ⚠ BREAKING CHANGES

* upgrade to stable version

### Bug Fixes

* **composer.json:** fix src path ([c832a0f](https://github.com/SandroMiguel/php-sceleto/commit/c832a0f6dd48d1e095dd2d40895f010cbf41b112))
* **package.json:** add dev dependencies ([a451d2c](https://github.com/SandroMiguel/php-sceleto/commit/a451d2ccc444c3eedfa1a619ffd0608e1bfada19))
* **phpunit.xml.dist:** update directory ([878864e](https://github.com/SandroMiguel/php-sceleto/commit/878864e63557e6515e8201dcd9f3fccec83fc97d))
* update about ([f2e1e25](https://github.com/SandroMiguel/php-sceleto/commit/f2e1e2584f0b3942dd5651049935a9940fc2e6b5))

## [1.0.0](https://github.com/SandroMiguel/php-sceleto/compare/v0.5.1...v1.0.0) (2023-10-09)


### ⚠ BREAKING CHANGES

* upgrade to stable version

### Bug Fixes

* **package.json:** add dev dependencies ([a451d2c](https://github.com/SandroMiguel/php-sceleto/commit/a451d2ccc444c3eedfa1a619ffd0608e1bfada19))
* update about ([f2e1e25](https://github.com/SandroMiguel/php-sceleto/commit/f2e1e2584f0b3942dd5651049935a9940fc2e6b5))

## [0.5.1](https://github.com/SandroMiguel/php-sceleto/compare/v0.5.0...v0.5.1) (2023-10-07)


### Bug Fixes

* **phpunit.xml.dist:** update directory ([878864e](https://github.com/SandroMiguel/php-sceleto/commit/878864e63557e6515e8201dcd9f3fccec83fc97d))

## [0.5.0](https://github.com/SandroMiguel/php-sceleto/compare/0.4.2...v0.5.0) (2023-10-07)


### Features

* remove config/ ([9121421](https://github.com/SandroMiguel/php-sceleto/commit/912142155b731f80d29c82d6c6aec179a98125f2))
* update autoload psr-4 ([6ea9d07](https://github.com/SandroMiguel/php-sceleto/commit/6ea9d078e979eaf5103e1f16c3dda473dafbd159))
