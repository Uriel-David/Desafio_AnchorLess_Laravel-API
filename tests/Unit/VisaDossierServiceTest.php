<?php

use Tests\TestCase; // Importa o TestCase do Laravel
use App\Http\Services\VisaDossierService;
use App\Models\VisaDossierFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(TestCase::class)->beforeEach(function () {
    Storage::fake('public');
    $this->service = new VisaDossierService();
});

it('returns documents grouped by tag', function () {
    VisaDossierFile::factory()->count(2)->create([
        'tag' => 'passport',
    ]);

    $result = $this->service->getAllDocuments();

    expect($result)->toBeArray()
        ->and($result)->toHaveKey('passport');
});

it('can insert a document file', function () {
    $file = UploadedFile::fake()->create('file.pdf', 512, 'application/pdf');

    $data = [
        'file' => $file,
        'type' => 'document',
        'tag' => 'passport'
    ];

    $response = $this->service->insertDocuments($data);

    expect($response)->toBeInstanceOf(VisaDossierFile::class);

    // Verifica arquivo pelo caminho retornado na coluna 'path'
    Storage::disk('public')->assertExists($response->path);
});

it('handles insertDocuments exception gracefully', function () {
    $data = ['file' => null, 'type' => 'document', 'tag' => 'fail'];

    $result = $this->service->insertDocuments($data);

    expect($result)->toBeString()
        ->toContain('No file provided');
});

it('can delete a document', function () {
    Storage::put('visa_documents/to_delete.pdf', 'dummy content');

    $file = VisaDossierFile::create([
        'name' => 'to_delete',
        'ext' => 'pdf',
        'path' => 'visa_documents/to_delete.pdf',
        'url' => '/storage/visa_documents/to_delete.pdf',
        'type' => 'document',
        'tag' => 'old',
    ]);

    $deleted = $this->service->deleteDocument($file->id);

    expect($deleted)->toBeInstanceOf(VisaDossierFile::class);
    $this->assertDatabaseMissing('visa_dossier_files', ['id' => $file->id]);
});
