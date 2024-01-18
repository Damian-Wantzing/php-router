<?php

namespace Router\FileServer;

use Directory;
use DirectoryIterator;
use Router\Request\Request;
use Router\Response\Response;

class FileServer
{
    private Directory $root;

    public function __construct(Directory $root)
    {
        $this->root = $root;
    }

    public function handleRequest(Request $request, Response $response)
    {
        $pathLength = strlen($request->route()->path());
        $trimmedUri = substr($request->uri(), $pathLength);
        $fullPath = realpath($this->root->path . $trimmedUri);

        if (strpos($fullPath, $this->root->path) !== 0) {
            // The requested path is outside of the root directory
            echo "404"; // TODO use different error handling mechanism
            return;
        }

        if (is_dir($fullPath))
        {
            require_once(__DIR__."/Directory.php");            
        } 
        else if (is_file($fullPath))
        {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $fullPath);
            finfo_close($finfo);

            header("Content-Type: $mimeType");
            readfile($fullPath);
        }
        else
        {
            echo "404"; // TODO use different error handling mechanism
        }
    }
}
