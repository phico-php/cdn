<?php

use Phico\Cdn\Cdn;


beforeEach(function () {
    $config = include path('tests/fixtures/config.php');
    $this->cdn = new Cdn($config['r2']);
});

test('can upload an object to Tigris', function () {
    $name = 'test/file.txt';
    $filepath = path('tests/fixtures/file.txt');

    $this->cdn->put($filepath, $name);

    $content = $this->cdn->get($name);

    expect($content)->not()->toBeFalse();
    expect($content)->toBeString();

});

test('can get an object from Tigris', function () {
    $key = 'test/file.txt';

    $content = $this->cdn->get($key);

    expect($content)->not()->toBeFalse();
    expect($content)->toBeString();
    expect($content)->toBe('This is the file content
');
});

test('can remove an object from Tigris', function () {
    $key = 'test/file.txt';

    $cdn = $this->cdn->delete($key);

    expect($cdn)->toEqual($this->cdn);
});

// test('can create a bucket', function () {
//     $name = 'test-bucket-delete-me';

//     $this->cdn->bucket($name)->create();
// });
// test('can delete a bucket', function () {
//     $name = 'test-bucket-delete-me';

//     $this->cdn->bucket($name)->delete();
// });

