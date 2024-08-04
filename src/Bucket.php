<?php

declare(strict_types=1);

namespace Phico\Cdn;

use Aws\S3\S3Client;


class Bucket
{
    private S3Client $client;
    private string $bucket_name;


    public function __construct(S3Client $client, string $bucket_name = '')
    {
        $this->client = $client;
        $this->bucket_name = $bucket_name;
    }
    // provide access to underlying S3 methods
    public function __call($method, $args)
    {
        return $this->client($method, $args);
    }
    public function use(string $bucket_name): Bucket
    {
        $this->bucket_name = $bucket_name;
        return $this;
    }
    // create a bucket
    public function create(string $region = 'auto'): Bucket
    {
        try {

            $this->client->createBucket([
                'Bucket' => $this->bucket_name,
            ]);
            return $this;

        } catch (\Throwable $th) {
            throw new CdnException("Failed creating bucket '$this->bucket_name'");
        }
    }
    // delete a bucket
    public function delete(): Bucket
    {
        try {

            $this->client->deleteBucket([
                'Bucket' => $this->bucket_name,
            ]);
            return $this;

        } catch (\Throwable $th) {
            throw new CdnException("Failed deleting bucket '$this->bucket_name'");
        }
    }
    // list objects in a bucket
    public function list(): array
    {
        try {

            $out = [];
            $results = $this->client->listObjectsV2([
                'Bucket' => $this->bucket_name,
            ]);
            foreach ($results as $obj) {
                $out[] = $obj;
            }
            return $out;

        } catch (\Throwable $th) {
            throw new CdnException("Failed listing bucket '$this->bucket_name'");
        }
    }

}
