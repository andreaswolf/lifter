<?php

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig) {
    $rectorConfig->paths([
        __DIR__ . '/result/'
    ]);
};