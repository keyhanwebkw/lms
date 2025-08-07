<?php

namespace App\Traits;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait ValidatesContentFile
{
    public function validateContentFile($content, &$mime, &$error = null): bool
    {
        if ( !$content) {
            return true;
        }

        $mime = $content->getMimeType();

        $rules = match (true) {
            str_starts_with($mime, 'image/') => Config::get('subsystem.storage.image.validate'),
            str_starts_with($mime, 'video/') => Config::get('subsystem.storage.video.validate'),
            default => null,
        };

        if ( !$rules) {
            $error = st('The uploaded content must be an image or a video');
            return false;
        }

        try {
            Validator::make(['content' => $content], ['content' => $rules])->validate();
        } catch (ValidationException $e) {
            $error = $e->validator->errors()->first('content');
            return false;
        }

        return true;
    }
}
