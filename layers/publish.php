<?php

use Aws\Lambda\LambdaClient;
use Symfony\Component\Process\Process;

require_once __DIR__ . '/../vendor/autoload.php';

$layers = [
    'laravel-serverless-console' => 'Laravel Bootstrap for Bref running in Console',
    'laravel-serverless-fpm' => 'Laravel Bootstrap for Bref running in FPM',
];

$regions = [
    /*"ca-central-1",
    "eu-central-1",
    "eu-north-1",
    "eu-west-1",
    "eu-west-2",
    "eu-west-3",
    "sa-east-1",
    "us-east-1",
    "us-east-2",
    "us-west-1",
    "us-west-2",
    "ap-south-1",
    "ap-northeast-1",
    "ap-northeast-2",
    "ap-southeast-1",*/
    "ap-southeast-2"
];

foreach ($layers as $layer => $layerDescription) {
    $file = __DIR__ . "/export/$layer.zip";
    if (! file_exists($file)) {
        echo "File $file does not exist: generate the archives first\n";
        exit(1);
    }
}

foreach ($regions as $region) {
    $lambda = new LambdaClient([
        'region' => $region,
        'version' => 'latest',
    ]);

    $layersToPublish = isset($argv[1]) ? [$argv[1] => $layers[$argv[1]]] : $layers;

    foreach ($layersToPublish as $layer => $description) {
        $file = __DIR__ . "/export/$layer.zip";

        $publishResponse = $lambda->publishLayerVersion([
            'LayerName' => $layer,
            'Description' => $description,
            'LicenseInfo' => 'MIT',
            'Content' => [
                'ZipFile' => file_get_contents($file),
            ],
        ]);

        $lambda->addLayerVersionPermission([
            'Action' => 'lambda:GetLayerVersion',
            'LayerName' => $layer,
            'Principal' => '*',
            'StatementId' => (string) time(),
            'VersionNumber' => (string) $publishResponse['Version'],
        ]);

        echo '['.$region.']: '.$publishResponse['LayerVersionArn'].PHP_EOL;
    }
}
