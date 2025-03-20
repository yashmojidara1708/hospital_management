<?php

if (!function_exists('get_setting')) {
    function get_setting($name)
    {
        $setting = \App\Models\Setting::where('key', $name)->first();

        if (!empty($setting)) {
            return $setting['value'];
        }
        return '';
    }
}

