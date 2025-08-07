<?php

if (!function_exists('getFileCategory')) {
    /**
     * Normalize country code
     *
     * @param string $mimeType
     * @return string|null
     */
    function getFileCategory(string $mimeType): ?string
    {
        $categories = [
            'video' => ['video/mp4', 'video/x-msvideo', 'video/mpeg', 'video/quicktime'],
            'audio' => ['audio/mpeg', 'audio/wav', 'audio/mp4'],
            'image' => ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'],
            'excel' => [
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'text/csv'
            ],
            'pdf' => ['application/pdf'],
        ];

        foreach ($categories as $category => $mimes) {
            if (in_array($mimeType, $mimes)) {
                return $category;
            }
        }

        return null;
    }
}
