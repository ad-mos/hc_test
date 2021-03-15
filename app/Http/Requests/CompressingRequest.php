<?php

namespace App\Http\Requests;

use App\Services\Compressor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompressingRequest extends FormRequest
{
    public function rules()
    {
        return [
            'compression_level' => [
                'required',
                'integer',
                Rule::in(array_keys(Compressor::getCompressionLevels())),
            ],
            'sort_mode' => [
                'required',
                'integer',
                Rule::in(array_keys(Compressor::getSortModes())),
            ],
            'compress_colors' => [
                'nullable',
            ],
            'compress_font_weight' => [
                'nullable',
            ],
            'remove_backslashes' => [
                'nullable',
            ],
            'remove_semicolons' => [
                'nullable',
            ],
            'css_source' => [
                'required',
                'string',
            ]
        ];
    }

    public function validated()
    {
        $data = parent::validated();

        $data['compress_colors'] = array_key_exists('compress_colors', $data);
        $data['compress_font_weight'] = array_key_exists('compress_font_weight', $data);
        $data['remove_backslashes'] = array_key_exists('remove_backslashes', $data);
        $data['remove_semicolons'] = array_key_exists('remove_semicolons', $data);

        return $data;
    }
}
