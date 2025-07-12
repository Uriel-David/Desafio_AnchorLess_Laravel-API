<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\VisaDossierFile;

beforeEach(function () {
    Storage::fake('public');
    VisaDossierFile::query()->delete();
});

it('can list uploaded documents', function () {
    VisaDossierFile::factory()->create([
        'type' => 'document',
        'tag' => 'passport',
    ]);

    $response = $this->getJson('/api/documents/list');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [],
            'message'
        ]);
});

it('can upload a document file', function () {
    $file = UploadedFile::fake()->create('visa.pdf', 1024, 'application/pdf');

    $response = $this->postJson('/api/documents/upload', [
        'file' => $file,
        'type' => 'document',
        'tag' => 'passport',
    ]);

    $response->assertOk()
        ->assertJsonPath('success', true);

    $this->assertDatabaseCount('visa_dossier_files', 1);

    $visaFile = VisaDossierFile::first();
    Storage::disk('public')->assertExists($visaFile->path);
});

it('rejects invalid file upload', function () {
    $file = UploadedFile::fake()->create('file.exe', 100);

    $response = $this->postJson('/api/documents/upload', [
        'file' => $file,
        'type' => 'image',
        'tag' => 'dangerous',
    ]);

    $response->assertStatus(422);
});

it('can delete a document', function () {
    Storage::put('visa_documents/test.pdf', 'dummy content');
    $file = VisaDossierFile::create([
        'name' => 'test',
        'ext' => 'pdf',
        'path' => 'visa_documents/test.pdf',
        'url' => '/storage/visa_documents/test.pdf',
        'type' => 'document',
        'tag' => 'passport',
    ]);

    $response = $this->deleteJson('/api/documents/delete', ['id' => $file->id]);

    $response->assertOk()
        ->assertJsonPath('message', 'File deleted');

    $this->assertDatabaseMissing('visa_dossier_files', ['id' => $file->id]);
});
