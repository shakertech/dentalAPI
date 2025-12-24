<?php

if (!function_exists('tenant')) {
    function tenant()
    {
        return auth()->user()?->tenant;
    }
}
