<?php

use App\Services\ImageProcessingService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
});

test('service can be instantiated', function () {
    $service = new ImageProcessingService();
    expect($service)->toBeInstanceOf(ImageProcessingService::class);
});

test('generates unique filename', function () {
    $service = new ImageProcessingService();
    $filename1 = $service->generateUniqueFilename('test.jpg');
    $filename2 = $service->generateUniqueFilename('test.jpg');
    
    expect($filename1)->not->toBe($filename2);
    expect($filename1)->toEndWith('.jpg');
    expect($filename2)->toEndWith('.jpg');
});

test('ensures directories exist', function () {
    $service = new ImageProcessingService();
    $service->ensureDirectoriesExist();
    
    expect(Storage::disk('public')->exists('gallery'))->toBeTrue();
    expect(Storage::disk('public')->exists('gallery/thumbnails'))->toBeTrue();
});

test('gets storage stats', function () {
    $service = new ImageProcessingService();
    $stats = $service->getStorageStats();
    
    expect($stats)->toHaveKeys([
        'total_files',
        'total_thumbnails', 
        'total_size_bytes',
        'total_size_mb'
    ]);
    
    expect($stats['total_files'])->toBe(0);
    expect($stats['total_thumbnails'])->toBe(0);
});