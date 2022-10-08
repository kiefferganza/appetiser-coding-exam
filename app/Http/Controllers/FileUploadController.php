<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{

    public function __construct(private FileUpload $fileUpload){}
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request): \Illuminate\Http\JsonResponse
    {
        $fileList = [];
        $files = $request->file('file');

        foreach($files as $file) {
            $fileName = Str::uuid().'_'.$file->getClientOriginalName();

            $filePath = $file->move(public_path('upload'), $fileName);

            $this->fileUpload->create([
                'todo_id' => $request['todoID'],
                'file_path' => $filePath,
            ]);
            $fileList[] = $filePath;
        }


        return response()->json(['success'=>'You have successfully upload file.', 'data' => $fileList]);
    }
}

