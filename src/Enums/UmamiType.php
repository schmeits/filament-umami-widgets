<?php

namespace Schmeits\FilamentUmami\Enums;

enum UmamiType: string
{
    case TYPE_SELF_HOSTED = 'self-hosted';
    case TYPE_CLOUD = 'cloud';
}
