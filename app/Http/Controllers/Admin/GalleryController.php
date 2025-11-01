<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use App\Services\ImageProcessingService;
use App\Http\Requests\StoreGalleryImageRequest;
use App\Http\Requests\UpdateGalleryImageRequest;
use App\Http\Requests\ReorderGalleryImagesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    protected $imageProcessingService;

    public function __construct(ImageProcessingService $imageProcessingService)
    {
        $this->imageProcessingService = $imageProcessingService;
    }

    /**
     * Display a listing of gallery images for admin management.
     */
    public function index(Request $request)
    {
        $query = GalleryImage::query();

        // Filter by status if specified
        if ($request->has('status')) {
            switch ($request->get('status')) {
                case 'active':
                    $query->active();
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
                // 'all' or any other value shows all images
            }
        }

        // Search by filename or alt text
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('original_name', 'like', "%{$search}%")
                  ->orWhere('alt_text', 'like', "%{$search}%");
            });
        }

        $images = $query->ordered()->paginate(20);
        $totalCount = $images->total();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $images,
                'html' => view('admin.gallery.partials.image-grid', compact('images'))->render()
            ]);
        }

        return view('admin.gallery.index', compact('images', 'totalCount'));
    }

    /**
     * Show the form for creating new gallery images.
     */
    public function create()
    {
        return view('admin.gallery.create');
    }

    /**
     * Store multiple newly uploaded gallery images with enhanced security.
     */
    public function store(StoreGalleryImageRequest $request)
    {
        $validated = $request->validated();

        // Security logging
        \Illuminate\Support\Facades\Log::info('Gallery upload attempt', [
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'file_count' => count($validated['images']),
        ]);

        try {
            $uploadedImages = [];
            
            DB::transaction(function () use ($validated, &$uploadedImages, $request) {
                $images = $this->imageProcessingService->processGalleryImages($validated['images']);
                
                foreach ($images as $index => $imageData) {
                    $altText = isset($validated['alt_texts'][$index]) ? $validated['alt_texts'][$index] : null;
                    
                    $galleryImage = GalleryImage::create([
                        'filename' => $imageData['filename'],
                        'original_name' => $imageData['original_name'],
                        'path' => $imageData['path'],
                        'thumbnail_path' => $imageData['thumbnail_path'],
                        'size' => $imageData['size'],
                        'mime_type' => $imageData['mime_type'],
                        'alt_text' => $altText,
                        'display_order' => GalleryImage::getNextDisplayOrder(),
                        'is_active' => true,
                    ]);
                    
                    $uploadedImages[] = $galleryImage;
                }
            });

            // Log successful upload
            \Illuminate\Support\Facades\Log::info('Gallery upload successful', [
                'user_id' => auth()->id(),
                'uploaded_count' => count($uploadedImages),
                'total_size' => array_sum(array_column($uploadedImages, 'size')),
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Imágenes subidas exitosamente.',
                    'data' => $uploadedImages,
                    'uploaded_count' => count($uploadedImages)
                ]);
            }

            return redirect()->route('admin.gallery.index')
                ->with('success', 'Imágenes subidas exitosamente. Total: ' . count($uploadedImages));

        } catch (\Exception $e) {
            // Log upload failure
            \Illuminate\Support\Facades\Log::error('Gallery upload failed', [
                'user_id' => auth()->id(),
                'ip' => $request->ip(),
                'error' => $e->getMessage(),
                'file_count' => count($validated['images']),
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al subir las imágenes: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Error al subir las imágenes: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified gallery image.
     */
    public function show(GalleryImage $galleryImage)
    {
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $galleryImage,
                'html' => view('admin.gallery.partials.image-details', compact('galleryImage'))->render()
            ]);
        }

        return view('admin.gallery.show', compact('galleryImage'));
    }

    /**
     * Show the form for editing the specified gallery image.
     */
    public function edit(GalleryImage $galleryImage)
    {
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $galleryImage,
                'html' => view('admin.gallery.partials.edit-form', compact('galleryImage'))->render()
            ]);
        }

        return view('admin.gallery.edit', compact('galleryImage'));
    }

    /**
     * Update the specified gallery image metadata.
     */
    public function update(UpdateGalleryImageRequest $request, GalleryImage $galleryImage)
    {
        $validated = $request->validated();

        try {
            $galleryImage->update($validated);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Imagen actualizada exitosamente.',
                    'data' => $galleryImage->fresh()
                ]);
            }

            return redirect()->route('admin.gallery.index')
                ->with('success', 'Imagen actualizada exitosamente.');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar la imagen: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Error al actualizar la imagen: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified gallery image from storage and database.
     */
    public function destroy(GalleryImage $galleryImage)
    {
        try {
            DB::transaction(function () use ($galleryImage) {
                // Delete image files from storage
                $this->imageProcessingService->deleteImageFiles($galleryImage);
                
                // Delete database record
                $galleryImage->delete();
            });

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Imagen eliminada exitosamente.'
                ]);
            }

            return redirect()->route('admin.gallery.index')
                ->with('success', 'Imagen eliminada exitosamente.');

        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar la imagen: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withErrors(['error' => 'Error al eliminar la imagen: ' . $e->getMessage()]);
        }
    }

    /**
     * Reorder gallery images based on new display order.
     */
    public function reorder(ReorderGalleryImagesRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated) {
                foreach ($validated['images'] as $imageData) {
                    GalleryImage::where('id', $imageData['id'])
                        ->update(['display_order' => $imageData['display_order']]);
                }
            });

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Orden de imágenes actualizado exitosamente.'
                ]);
            }

            return redirect()->route('admin.gallery.index')
                ->with('success', 'Orden de imágenes actualizado exitosamente.');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al reordenar las imágenes: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withErrors(['error' => 'Error al reordenar las imágenes: ' . $e->getMessage()]);
        }
    }

    /**
     * Toggle the active status of a gallery image.
     */
    public function toggleActive(GalleryImage $galleryImage)
    {
        try {
            $galleryImage->update(['is_active' => !$galleryImage->is_active]);

            $status = $galleryImage->is_active ? 'activada' : 'desactivada';

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Imagen {$status} exitosamente.",
                    'data' => $galleryImage->fresh()
                ]);
            }

            return redirect()->route('admin.gallery.index')
                ->with('success', "Imagen {$status} exitosamente.");

        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al cambiar el estado de la imagen: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withErrors(['error' => 'Error al cambiar el estado de la imagen: ' . $e->getMessage()]);
        }
    }

    /**
     * Bulk delete multiple gallery images.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'image_ids' => 'required|array|min:1',
            'image_ids.*' => 'integer|exists:gallery_images,id'
        ]);

        try {
            $deletedCount = 0;
            
            DB::transaction(function () use ($request, &$deletedCount) {
                $images = GalleryImage::whereIn('id', $request->image_ids)->get();
                
                foreach ($images as $image) {
                    $this->imageProcessingService->deleteImageFiles($image);
                    $image->delete();
                    $deletedCount++;
                }
            });

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Se eliminaron {$deletedCount} imágenes exitosamente."
                ]);
            }

            return redirect()->route('admin.gallery.index')
                ->with('success', "Se eliminaron {$deletedCount} imágenes exitosamente.");

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar las imágenes: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withErrors(['error' => 'Error al eliminar las imágenes: ' . $e->getMessage()]);
        }
    }
}