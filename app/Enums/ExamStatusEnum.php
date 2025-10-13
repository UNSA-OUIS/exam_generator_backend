<?php

namespace App\Enums;

enum ExamStatusEnum: string
{
    case CONFIGURING = 'CONFIGURING';
    case VALIDATED = 'VALIDATED';
    case MASTERED = 'MASTERED';
    case VARIATED = 'VARIATED';
    case APPROVED = 'APPROVED';
}
