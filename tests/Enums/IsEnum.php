<?php

namespace Javaabu\StatusEvents\Tests\Enums;

interface IsEnum
{
    public static function labels(): array;

    public function getLabel(): string;
}
