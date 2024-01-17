TODOS:
INTERFACES for Request, Response, and other objects that are passed around frequently

FILESERVER
lookup in a directory for the requested path
router->fileserver(PATH, DIRECTORY);
e.g. router->fileserver("/static", __DIR__."/stat");
root of /stat is uri/static
so /static/file1 file1 will be in the /stat directory
When no file is specified we will show the directory structure, through which a user can browse and click
