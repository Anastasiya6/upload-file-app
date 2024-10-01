<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        Log::info($request->all());
        $fileName = $request->input('file');

        $offset = intval($request->input('offset'));
        if(!file_exists(public_path() . '/storage/uploads')) {
            mkdir(public_path() . '/storage/uploads');
        }
        if(!file_exists(public_path() . '/storage/temp')) {
            mkdir(public_path() . '/storage/temp');
        }
        // Определение пути для временного сохранения
        $filePath = public_path('\temp' . $fileName);

        // Открываем файл для записи в нужную позицию
        $file = fopen($filePath, $offset === 0 ? 'w' : 'a');
        fwrite($file, $request->file('file')->get());
        fclose($file);

        // Проверка завершена ли загрузка файла
        if (filesize($filePath) >= $request->file('file')->getSize()) {
            // Перемещаем файл в конечную директорию
            Storage::move('uploads/' . $fileName, 'completed/' . $fileName);
            return response()->json(['success' => true, 'message' => 'Upload complete']);
        }

        return response()->json(['success' => true, 'message' => 'Chunk uploaded']);
    }
}
