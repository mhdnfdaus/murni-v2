<?php

if (!function_exists('_id')) {
    function _id() {
        return \Illuminate\Support\Str::orderedUuid();
    }
}