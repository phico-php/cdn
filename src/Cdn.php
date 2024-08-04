<?php

declare(strict_types=1);

namespace Phico\Cdn;

use Aws\S3\S3Client;

class Cdn
{
    private S3Client $client;
    private Bucket $bucket;
    private array $options = [
        'account_id' => '',
        'access_key' => '',
        'secret_key' => '',
        'bucket_name' => '',
        'region' => 'auto',
        'endpoint' => '',
    ];


    public function __construct(array $options = [])
    {
        // apply default options overriding with passed options
        foreach ($this->options as $k => $v) {
            $this->options[$k] = (isset($options[$k])) ? $options[$k] : $v;
        }

        $this->client = new S3Client([
            'credentials' => [
                'key' => $this->options['access_key'],
                'secret' => $this->options['secret_key'],
            ],
            'region' => $this->options['region'],
            'endpoint' => $this->options['endpoint'],
            'version' => 'latest',
        ]);

        $this->bucket = new Bucket($this->client, $this->options['bucket_name']);
    }
    // provide access to underlying S3 methods
    public function __call($method, $args)
    {
        return $this->bucket->$method(...$args);
    }
    public function bucket(string $bucket_name = null): Bucket
    {
        if (!is_null($bucket_name)) {
            $this->options['bucket_name'] = $bucket_name;
        }

        return $this->bucket->use($this->options['bucket_name']);
    }
    // list all buckets
    public function buckets(): array
    {
        try {

            $out = [];
            $results = $this->client->listBuckets();
            foreach ($results as $bucket) {
                $out[] = $bucket;
            }
            return $out;

        } catch (\Throwable $th) {
            throw new CdnException("Failed listing available buckets", $th);
        }
    }
    // fetch an object from a bucket
    public function get(string $name): mixed
    {
        try {

            $file = $this->client->getObject([
                'Bucket' => $this->options['bucket_name'],
                'Key' => $name,
            ]);
            $body = $file->get('Body');
            $body->rewind();

            return (string) $body;

        } catch (\Throwable $th) {
            throw new CdnException("Failed to fetch file '$name' from cdn");
        }
    }
    // put an object in a bucket
    public function put(string $filepath, string $name): self
    {
        try {

            $this->client->putObject([
                'Bucket' => $this->options['bucket_name'],
                'Key' => $name,
                'SourceFile' => $filepath
            ]);

            return $this;

        } catch (\Throwable $th) {
            throw new CdnException("Failed to upload '$filepath' to '{$this->options['bucket_name']}/$name'");
        }

    }
    // remove an object from a bucket
    public function delete(string $name): self
    {
        try {

            $this->client->deleteObject([
                'Bucket' => $this->options['bucket_name'],
                'Key' => $name,
            ]);

            return $this;

        } catch (\Throwable $th) {
            throw new CdnException("Failed to delete '$name' from '{$this->options['bucket_name']}'");
        }

    }

}
