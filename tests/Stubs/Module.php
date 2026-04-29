<?php

if (!class_exists('Module')) {
    abstract class Module
    {
        public function registerHook($hook_name, $shop_list = null)
        {
            return true;
        }
    }
}
