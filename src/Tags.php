<?php

namespace BIPBOP\Client;

final class Tags {

    final public static function decode(string $encodedTags): array
    {
        $stream = fopen('php://memory','r+');
        fwrite($stream, $encodedTags);
        rewind($stream);
        $data = [];
        while (($row = fgetcsv($stream)) !== false) {
            $data = array_merge($data, $row);
        }
        fclose($stream);
        return $data;
    }

    final public static function encode(array $decodedTags): string
    {
        $stream = fopen('php://memory','r+');
        fputcsv($stream, array_values($decodedTags));
        rewind($stream);
        $data = stream_get_contents($stream);
        fclose($stream);
        return rtrim($data, "\n");
    }

}