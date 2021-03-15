<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompressingRequest;
use App\Services\Compressor;

class CompressingController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function compress(CompressingRequest $request)
    {
        $data = $request->validated();

        $compressor = new Compressor(
            $data['compression_level'],
            $data['sort_mode'],
            $data['compress_colors'],
            $data['compress_font_weight'],
            $data['remove_backslashes'],
            $data['remove_semicolons']
        );

        return response()->json([
            'result' => $compressor->process($data['css_source']),
        ]);
    }
}
