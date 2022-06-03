<?php

namespace App\Constants;

enum UserRoles: string
{
    case LANDLORD = 'landlord';
    case RENTING = 'renting';
}
