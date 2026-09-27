<?php

use App\Helpers\GalleryUploadHelper;
use App\Models\GalleryImage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin']);

    $this->admin = User::factory()->create([
        'name' => 'Test Admin',
        'email' => 'gallery-admin@test.com',
        'password' => Hash::make('password123'),
    ]);
    $this->admin->assignRole('admin');
});

it('caps the effective image limit at the configured value when php allows more', function () {
    config(['gallery.security.max_file_size' => 1024 * 1024]);

    $phpLimitKb = GalleryUploadHelper::phpUploadLimitKb();

    expect(GalleryUploadHelper::maxFileSizeKb())
        ->toBe($phpLimitKb !== null && $phpLimitKb < 1024 ? $phpLimitKb : 1024);
});

it('never advertises more than php accepts for a single upload', function () {
    config(['gallery.security.max_file_size' => 50 * 1024 * 1024]);

    expect(GalleryUploadHelper::maxFileSizeKb())
        ->toBeLessThanOrEqual(GalleryUploadHelper::phpUploadLimitKb() ?? PHP_INT_MAX);
});

it('reads the per-upload file count from configuration', function () {
    config(['gallery.security.max_files_per_upload' => 3]);

    expect(GalleryUploadHelper::maxFilesPerUpload())->toBe(3);
});

it('formats the limit for display', function () {
    expect(GalleryUploadHelper::formatKb(5120))->toBe('5 MB')
        ->and(GalleryUploadHelper::formatKb(2048))->toBe('2 MB')
        ->and(GalleryUploadHelper::formatKb(800))->toBe('800 KB')
        ->and(GalleryUploadHelper::formatKb(0))->toBe('—');
});

it('rejects an image larger than the configured limit', function () {
    config(['gallery.security.max_file_size' => 64]);

    $response = $this->actingAs($this->admin)
        ->post(route('admin.gallery.store'), [
            'images' => [UploadedFile::fake()->image('grande.jpg', 400, 400)],
        ]);

    $response->assertSessionHasErrors('images.0');
    expect(GalleryImage::count())->toBe(0);
});

it('rejects more images than the configured per-upload maximum', function () {
    config(['gallery.security.max_files_per_upload' => 1]);

    $response = $this->actingAs($this->admin)
        ->post(route('admin.gallery.store'), [
            'images' => [
                UploadedFile::fake()->image('una.jpg', 200, 200),
                UploadedFile::fake()->image('dos.jpg', 200, 200),
            ],
        ]);

    $response->assertSessionHasErrors('images');
});

it('does not let guests upload gallery images', function () {
    config(['gallery.security.max_file_size' => 5 * 1024 * 1024]);

    $this->post(route('admin.gallery.store'), [
        'images' => [UploadedFile::fake()->image('invitado.jpg', 200, 200)],
    ])->assertRedirect();
});
