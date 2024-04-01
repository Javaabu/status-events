<?php

namespace Javaabu\StatusEvents\Interfaces;

interface Trackable
{
    public function getStatusColors(): array;
    public function getStatusLabels(): array;
}
