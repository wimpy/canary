<?php

use Aws\S3\Exception\S3Exception;

$app->get('/healthz', function () use ($app) {

    function canWriteOnOwnS3Bucket($app)
    {
        $s3 = $app['aws']->createS3();
        $s3->putObject([
            'Bucket' => $app['bucket_name'],
            'Key' => $app['bucket_key'],
            'Body' => "Something"
        ]);
    }

    function canReadWhatsWrittenOnS3($app)
    {
        /** @var \Aws\S3\S3Client $s3 */
        $s3 = $app['aws']->createS3();
        $s3->getObject([
            'Bucket' => $app['bucket_name'],
            'Key' => $app['bucket_key'],
        ]);
    }

    function canDecryptUsingKMS($app)
    {
        /** @var \Aws\Kms\KmsClient $kms */
        $kms = $app['aws']->createKMS();
        $string = "Hello World";
        $encrypted_string = $kms->encrypt([
            'KeyId' => $app['kms_master_key'],
            'Plaintext' => $string,
        ]);
        $decrypted_string = $kms->decrypt([
            'CiphertextBlob' => $encrypted_string->get('CiphertextBlob'),
        ]);

        if ($decrypted_string->get('Plaintext') !== $string) {
            throw new RuntimeException("Initial string and its encrypted/decrypted version don't match");
        }
    }

    canWriteOnOwnS3Bucket($app);
    canReadWhatsWrittenOnS3($app);
    canDecryptUsingKMS($app);

    return json_encode("OK");
})
;
