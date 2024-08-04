<?php

use Phico\Cdn\Cdn;


beforeEach(function () {
    $config = include path('tests/fixtures/config.php');
    $this->cdn = new Cdn($config['aws']);
});

// it('can upload a file', function () {
//     $key = 'test/file.txt';
//     $filepath = 'tests/fixtures/file.txt';

//     $result = $this->cdn->put($key, $filepath);

//     expect($result)->toBeTrue();
// });

test('can get a file', function () {
    $key = 'test.md';

    $content = $this->cdn->bucket('phico-test')->get($key);

    expect($content)->not()->toBeFalse();
    expect($content)->toBeString();
});
