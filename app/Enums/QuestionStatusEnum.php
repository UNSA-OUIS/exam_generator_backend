<?php

namespace App\Enums;

enum QuestionStatusEnum: string
{
    case AVAILABLE = 'AVAILABLE';
    case UNAVAILABLE = 'UNAVAILABLE';
    case USED = 'USED';
    case RETIRED = 'RETIRED';
}
