<?php

namespace App\Http\Controllers\Api;

use App\Models\Document;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['fileUpload']);
    }

    public function fileUpload(Request $request)
    {

    }

    public function count(Request $request)
    {
        try
        {
            $document_count = Document::all()->count();
        } catch (Exception $e)
        {
            $document_count = $e->getMessage();
        }
        return response()->json([
            'document_count' => $document_count,
        ]);
    }

    public function index(Request $request)
    {
        try
        {
            $documents = Document::all();
        } catch (Exception $e)
        {
            $documents = $e->getMessage();
        }
        return response()->json([
            'documents' => $documents,
        ]);
    }

    public function create(Request $request)
    {
        try
        {
            $filename = null;
            if(!empty($request->data))
            {
                $filename = Carbon::now()->format('Y-m-d-H-i-s');
                Storage::disk('local')->put('documents/' . $filename . '.pdf', base64_decode($request->data));
            }

            $data = [
                'code' => $request->code,
                'document_name' => $request->document_name,
                'document_number' => $request->document_number,
                'issue_date' => $request->issue_date,
                'expiry_date' => $request->expiry_date,
                'filename' => $filename,
                'crew_id' => $request->crew_id,
                'user_id' => $request->user_id,
            ];
            $document = Document::create($data);
        } catch (Exception $e)
        {
            $document = $e->getMessage();
        }
        return response()->json([
            'crew_document' => $document,
        ]);
    }

    public function edit(Request $request)
    {
        try
        {
            $document = Document::with(['crew', 'user'])->where('id', $request->rid)->first();
        } catch (Exception $e)
        {
            $document = $e->getMessage();
        }
        return response()->json([
            'crew_document' => $document,
        ]);
    }

    public function update(Request $request)
    {
        try
        {
            $data = [
                'code' => $request->code,
                'document_name' => $request->document_name,
                'document_number' => $request->document_number,
                'issue_date' => $request->issue_date,
                'expiry_date' => $request->expiry_date,
                'crew_id' => $request->crew_id,
            ];
            $document = Document::where('id', $request->id)->update($data);
        } catch (Exception $e)
        {
            $document = $e->getMessage();
        }
        return response()->json([
            'crew_document' => $document,
        ]);
    }

    public function destroy(Request $request)
    {
        try
        {
            $doc = Document::where('id', $request->id)->first();
            Storage::disk('local')->delete('documents/' . $doc->filename . '.pdf');
            $document = Document::where('id', $request->id)->delete();
        } catch (Exception $e)
        {
            $document = $e->getMessage();
        }
        return response()->json([
            'crew_document' => $document,
        ]);
    }
}
