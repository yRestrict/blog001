<?php

namespace App;

enum UserRole: string
{
    case Owner   = 'owner';
    case Author  = 'author';
    case Visitor = 'visitor';
}
