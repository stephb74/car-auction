<?php

namespace App\Enum;

enum FeeName: string
{
    case BASIC = 'Basic Buyer Fee';
    case SPECIAL = 'Special Buyer Fee';
    case ASSOCIATION = 'Association Cost';
    case STORAGE = 'Storage Fee';
}
