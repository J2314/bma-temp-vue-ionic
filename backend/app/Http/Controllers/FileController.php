<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormFiles;

class FileController extends Controller
{
    // public function upload(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|file|mimes:pdf,doc,docx|max:10240', 
    //     ]);

    //     $uploadedFile = $request->file('file');
    //     $fileName = $uploadedFile->getClientOriginalName(); 
    //     $uploadedFile->storeAs('uploads', $fileName); 

    //     $submittedFile = new SubmittedFile();
    //     $submittedFile->name = $fileName;
    //     $submittedFile->uploaded_by = auth()->user()->name; 
    //     $submittedFile->save();

    //     return response()->json(['message' => 'File uploaded successfully']);
    // }

    public function submittedFiles()
    {
        $files = SubmittedFile::all();

        return response()->json($files);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:txt,doc,docx,pdf|max:10240',
            'form_id' => 'required|string|max:255',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            if ($file->getSize() === 0) {
                return response()->json(['error' => 'The uploaded file is empty.'], 422);
            }

            $filename = $file->getClientOriginalName();
            $path = 'uploads/' . $filename;

            $existingRecord = FormFiles::where('form_id', $request->input('form_id'))
                ->where('file_path', $path)
                ->first();

            Storage::disk('public')->put($path, fopen($request->file('file'), 'r+'));
            $path = URL::to('/') .'/storage/'. $path;

            $fileRecord = new FormFiles();
            $fileRecord->form_id = $request->input('form_id');
            $fileRecord->file_path = $path;
            $fileRecord->is_active = true;
            $fileRecord->save();

            return response()->json(['message' => 'File uploaded successfully', 'file_id' => $fileRecord->id]);
        }

        return response()->json(['error' => 'File upload failed'], 422);
    }

    public function retrieveUploads($formId)
    {
        $uploads = FormFiles::where('form_id', $formId)->select('id','form_id', 'file_path', 'created_at')->get();
        return response()->json($uploads);
    }
}