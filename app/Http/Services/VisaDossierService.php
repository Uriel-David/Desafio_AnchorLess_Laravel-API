<?php

namespace App\Http\Services;

use App\Models\VisaDossierFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;
use Str;

class VisaDossierService
{
    public function __construct() {}

    public function getAllDocuments(): array|string
    {
        try {
            $files = VisaDossierFile::all()
                ->groupBy('tag')
                ->map(function ($group) {
                    return $group->map(function ($file) {
                        return [
                            'id' => $file->id,
                            'name' => $file->name,
                            'url' => $file->url,
                            'type' => $file->type,
                            'tag' => $file->tag,
                            'createdAt' => $file->created_at,
                        ];
                    });
                })->toArray();

            return $files;
        } catch (Exception $e) {
            Log::error('Error in service -> VisaDossierService - function -> getAllDocuments:', [
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'message' => $e->getMessage(),
            ]);

            return 'The saved documents could not be recovered';
        }
    }

    public function insertDocuments(array $data): VisaDossierFile|string
    {
        try {
            /** @var \Illuminate\Http\UploadedFile $file */
            $file = $data['file'];
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filename = Str::slug($originalName) . '-' . time() . '.' . $extension;
            $storedPath = $file->storeAs('visa_documents', $filename, 'public');
            $url = Storage::disk('public')->url($storedPath);

            $visaFile = VisaDossierFile::create([
                'name' => $originalName,
                'ext' => $extension,
                'path' => $storedPath,
                'url' => $url,
                'type' => $data['type'],
                'tag' => $data['tag'],
            ]);

            return $visaFile;
        } catch (Exception $e) {
            Log::error('Error in service -> VisaDossierService - function -> insertDocuments:', [
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'message' => $e->getMessage(),
            ]);

            return 'Error trying to save file';
        }
    }

    public function deleteDocument(int $id): VisaDossierFile|string
    {
        try {
            $file = VisaDossierFile::findOrFail($id);

            if (Storage::disk('public')->exists($file->path)) {
                Storage::disk('public')->delete($file->path);
            }

            $file->delete();

            return $file;
        } catch (Exception $e) {
            Log::error('Error in service -> VisaDossierService - function -> deleteDocument:', [
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'message' => $e->getMessage(),
            ]);

            return 'The document could not be deleted';
        }
    }
}
