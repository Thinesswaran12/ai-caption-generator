<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AiCaptionController;

Route::post('/ai-caption-generator/generate', [AiCaptionController::class, 'generate']);