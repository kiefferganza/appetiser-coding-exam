<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFileRequest;
use App\Models\FileUpload;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{

    public function __construct(private FileUpload $fileUpload, private Todo $todo){}
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(StoreFileRequest $request): \Illuminate\Http\JsonResponse
    {
        $fileList = [];
        $files = $request->file('file');
        if($this->todo->where('id', '!=', $request['todoID'])) return response()->json('Todo Doesnt Exist',404);

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

