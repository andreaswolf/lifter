<?php

use a9f\Lifter\Configuration\LifterConfig;

return static function (LifterConfig $config) {
    $GLOBALS['called'] = true;
};
