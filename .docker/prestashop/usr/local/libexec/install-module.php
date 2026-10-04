<?php

require_once '/var/www/html/config/config.inc.php';

$module = Module::getInstanceByName('modulefixture');

if (!$module || Module::isInstalled('modulefixture')) {
    exit(0);
}

Module::updateTranslationsAfterInstall(false);

$context = Context::getContext();

if (!isset($context->employee) || !Validate::isLoadedObject($context->employee)) {
    $context->employee = new Employee(1);
}

if (!$module->install()) {
    fwrite(STDERR, "Unable to install modulefixture module.\n");
    exit(1);
}
