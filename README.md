# Cdn

Lightweight CDN support for [Phico](https://github.com/phico-php/phico)

## Installation

Using composer

```sh
composer require phico/cdn
```

## Usage

CDN provides quick and simple access to AWS S3 and Cloudflare R2 private CDNs.

### Objects

```php
// put a local file on the cdn
$cdn->put('path/to/local/file.txt', '/path/on/cdn/file.txt');

// retrieve the file content
$content = $this->cdn->get('/path/on/cdn/file.txt');

// delete the file
$cdn->delete('/path/on/cdn/file.txt');
```

#### Switching buckets

If you have connected with the necessary permissions you can switch buckets.

```php
$cdn->bucket('other-bucket')->put('local/file.txt', 'uploads/file.txt');
$cdn->bucket('backup-bucket')->put('local/file.txt', 'uploads/file.txt');
```

### Buckets

If you have connected with the necessary permissions you can manage buckets.

```php
// list all buckets
$array = $cdn->buckets();

// create a bucket on the cdn
$cdn->bucket('tmp')->create();

// list objects in the bucket
$array = $cdn->bucket('tmp')->list();

// switching a bucket will be remembered so the above two lines could be shortened to
$array = $cdn->bucket('tmp')->create()->list();

// delete the bucket from the cdn
$cdn->bucket('tmp')->delete();
```

## Issues

If you discover any bugs or issues with behaviour or performance please create an issue, and if you are able a pull request with a fix.

Please make sure to update tests as appropriate.

For major changes, please open an issue first to discuss what you would like to change.

## License

[BSD-3-Clause](https://choosealicense.com/licenses/bsd-3-clause/)
