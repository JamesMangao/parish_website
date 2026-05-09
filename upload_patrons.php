<?php

// Quick script to upload patron saint images to the correct Supabase path
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->handleRequest(\Illuminate\Http\Request::capture());
