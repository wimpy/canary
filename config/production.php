<?php

// configure your app for the production environment
ini_set('display_errors', 0);
$app['aws.config'] = [
    'version' => 'latest',
    'region' => getenv('AWS_REGION') ?: 'eu-west-1',
];
$app['bucket_name'] = getenv('S3_BUCKET');
$app['bucket_key'] = 'canary-test';
$app['kms_master_key'] = getenv('KMS_MASTER_KEY');
