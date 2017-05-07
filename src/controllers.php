<?php

use Aws\S3\Exception\S3Exception;

$app->get('/health', function () use ($app) {

    function canWriteOnOwnS3Bucket($app)
    {
        $s3 = $app['aws']->createS3();
        $s3->putObject([
            'Bucket' => $app['bucket_name'],
            'Key' => $app['bucket_key'],
            'Body' => $app['bucket_content']
        ]);
    }

    function canReadWhatsWrittenOnS3($app, $bucket_content)
    {
        /** @var \Aws\S3\S3Client $s3 */
        $s3 = $app['aws']->createS3();
        $s3->getObject([
            'Bucket' => $app['bucket_name'],
            'Key' => $app['bucket_key'],
        ]);
    }

    function mustFailWhenWritingToDifferentEnvironment($app)
    {
        try {
            /** @var \Aws\S3\S3Client $s3 */
            $s3 = $app['aws']->createS3();
            $s3->putObject([
                'Bucket' => $app['bucket_name_wrong_env'],
                'Key' => $app['bucket_key'],
                'Body' => $app['bucket_content']
            ]);
            throw new RuntimeException('Application must not be able to write outside the environment where is deployed');
        } catch (S3Exception $exception) {
        }

    }

    function mustFailWhenWritingToDifferentApp($app)
    {
        try {
            /** @var \Aws\S3\S3Client $s3 */
            $s3 = $app['aws']->createS3();
            $s3->putObject([
                'Bucket' => $app['bucket_name_wrong_app'],
                'Key' => $app['bucket_key'],
                'Body' => $app['bucket_content']
            ]);
            throw new RuntimeException('Application must not be able to write outside the application bucket');
        } catch (S3Exception $exception) {
        }
    }

    /** @var \Aws\S3\S3Client $s3 */
//    function canDecryptUsingKMS($app)
//    {
//        /** @var \Aws\Kms\KmsClient $kms */
//        $kms = $app['aws']->createKMS();
//        $kms->decrypt([
//            'CiphertextBlob' => $app['string_to_decrypt'],
//            'EncryptionContext' => ['EncryptionContextKey' => 'string']
//        ]);
//    }
    canWriteOnOwnS3Bucket($app);
    canReadWhatsWrittenOnS3($app, $app['bucket_content']);
    mustFailWhenWritingToDifferentEnvironment($app);
    mustFailWhenWritingToDifferentApp($app);
//    canDecryptUsingKMS($app);

    return json_encode("OK");
})
;
