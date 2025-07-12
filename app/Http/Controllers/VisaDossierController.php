<?php

namespace App\Http\Controllers;

use App\Http\Requests\VisaDossierDeleteRequest;
use App\Http\Requests\VisaDossierUploadRequest;
use App\Http\Services\VisaDossierService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class VisaDossierController extends Controller
{
    private VisaDossierService $visaDossierService;

    public function __construct()
    {
        $this->visaDossierService = new VisaDossierService();
    }

    public function listDocuments(): JsonResponse
    {
        $files = $this->visaDossierService->getAllDocuments();

        if (is_string($files)) {
            return response()->json(['success' => false, 'data' => [], 'message' => $files], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['success' => true, 'data' => $files, 'message' => 'success'], Response::HTTP_OK);
    }

    public function uploadDocuments(VisaDossierUploadRequest $request): JsonResponse
    {
        $data = $request->validated();
        $response = $this->visaDossierService->insertDocuments($data);

        if (is_string($response)) {
            return response()->json(['success' => false, 'data' => [], 'message' => $response], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['success' => true, 'data' => $response, 'message' => 'success'], Response::HTTP_OK);
    }

    public function deleteDocument(VisaDossierDeleteRequest $request): JsonResponse
    {
        $data = $request->validated();
        $response = $this->visaDossierService->deleteDocument($data['id']);

        if (is_string($response)) {
            return response()->json(['success' => false, 'data' => [], 'message' => $response], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['success' => true, 'message' => 'File deleted'], Response::HTTP_OK);
    }
}
