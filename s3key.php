<?php
use Aws\S3\S3Client;
use Aws\Exception\AwsException;



//Create a S3Client
$s3Client = new S3Client([
    'region' => 'us-west-2',
    'version' => '2006-03-01',
    'credentials' => array(
            'key' => "XXXXXXXXXXXXXXXXXXXX",
            'secret'  => "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"
        )
]);

?>