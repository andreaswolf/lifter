<?php

namespace a9f\Lifter;

use Symfony\Component\Console\Application;

final class LifterApplication extends Application
{
    public function __construct()
    {
        parent::__construct('Lifter Upgrade Automation Tool');
    }
}
