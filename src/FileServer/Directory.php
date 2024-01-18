<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php 
            $parts = explode("/", $fullPath);
            $directory = end($parts); 
        ?>
        <?= $directory ?>
    </title>
    <style>
        a {
            display: block;
        }
    </style>
</head>
<body>
    <?php
        $directoryItems = new DirectoryIterator($fullPath);
        $itemsArray = [];

        foreach ($directoryItems as $item) {
            $itemsArray[] = $item->getFilename();
        }
        usort($itemsArray, function($a, $b) {
            if ($a == '..') return -1;
            if ($b == '..') return 1;
            return strcmp($a, $b);
        });

        foreach ($itemsArray as $item) {
            if ($item == '.') 
            {
                continue;
            } 
            elseif ($item == '..') 
            {
                $uri = substr($request->uri(), 0, strrpos($request->uri(), '/'));
            } 
            else 
            {
                $uri = $request->uri() . "/" . $item;
            }
    ?>
            <a href="<?= $uri ?>"><?= $item ?></a>
    <?php
        }
    ?>
</body>
</html>
