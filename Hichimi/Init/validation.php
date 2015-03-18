<?php

$reg = \Hichimi\App::register();

$reg
    ->validation("number", function($value) {
        return is_numeric($value);
    })
    ->validation("string", function($value) {
        return is_string($value);
    })
    /**
     * 形式のみチェック
     */
    ->validation("uri", function($value) {
        return filter_var($value, FILTER_VALIDATE_URL) && preg_match('@^https?://@i', $value);
    })
    /**
     * 形式のみチェック
     */
    ->validation("email", function($value) {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    })
    /**
     * 形式のみチェック
     */
    ->validation("tel", function($value) {
        return preg_match("/^0([89]0\\d{8}|\\d{1,4}-\\d{1,4}-\\d{4})$/", $value);
    });