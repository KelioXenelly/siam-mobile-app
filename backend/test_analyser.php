<?php
require 'vendor/autoload.php';
$factories = [
    new \OpenApi\Analysers\DocBlockAnnotationFactory(),
    new \OpenApi\Analysers\AttributeAnnotationFactory()
];
$analyser = new \OpenApi\Analysers\ReflectionAnalyser($factories);
echo "OK\n";
