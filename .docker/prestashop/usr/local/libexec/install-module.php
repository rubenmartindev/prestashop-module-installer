<?php

require_once '/var/www/html/config/config.inc.php';

$module = Module::getInstanceByName('installerdemo');

if (!$module || Module::isInstalled('installerdemo')) {
    exit(0);
}

Module::updateTranslationsAfterInstall(false);

$context = Context::getContext();

if (!isset($context->employee) || !Validate::isLoadedObject($context->employee)) {
    $context->employee = new Employee(1);
}

if (!$module->install()) {
    fwrite(STDERR, "Unable to install installerdemo module.\n");
    exit(1);
}
