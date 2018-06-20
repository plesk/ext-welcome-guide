<?php
// Copyright 1999-2018. Plesk International GmbH. All rights reserved.

use PleskExt\Welcome\Installer;

try {
    Installer::createDefaultConfig();
    Installer::createExtensionsFile();
} catch (pm_Exception $e) {
    echo $e->getMessage() . "\n";
    exit(1);
}

exit(0);
