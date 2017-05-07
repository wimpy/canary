<?php

// configure your app for the production environment
ini_set('display_errors', 0);
$app['aws.config'] = array(
    'version' => 'latest',
    'region' => getenv('AWS_REGION') ?: 'eu-west-1',
);
$app['bucket_name'] = getenv('S3_BUCKET') . "/" . getenv('ENVIRONMENT') . "/" . getenv('APPLICATION_NAME');
$app['bucket_name_wrong_env'] = getenv('S3_BUCKET') . "/wrong/" . getenv('APPLICATION_NAME');
$app['bucket_name_wrong_app'] = getenv('S3_BUCKET') . "/" . getenv('ENVIRONMENT') . "/wrong";
$app['bucket_key'] = 'canary-test';
$app['bucket_content'] = 'This is a test';
$app['kms_master_key'] = getenv('KMS_MASTER_KEY');
$app['string_to_decrypt'] = "gibberish";
