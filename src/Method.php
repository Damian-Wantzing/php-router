<?php

namespace Router;

enum Method: string
{
    case Get = "GET";
    case Post = "POST";
    case Put = "PUT";
    case Delete = "DELETE";
}
