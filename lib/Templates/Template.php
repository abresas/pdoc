<?php
namespace PDoc\Templates;

interface Template
{
    public function render(array $values);
}
