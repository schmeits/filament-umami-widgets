<?php

declare(strict_types=1);

/**
 * src/Traits/HasDescription.php
 *
 * Builds the translated description shown under a widget heading.
 */

namespace Schmeits\FilamentUmami\Traits;

use Illuminate\Support\Facades\Lang;

trait HasDescription
{
    public function getDescription(): ?string
    {
        if (Lang::get("filament-umami-widgets::translations.widget.{$this->id}.description")) {
            return (Lang::has("filament-umami-widgets::translations.widget.{$this->id}.description_prefix") ? trans("filament-umami-widgets::translations.widget.{$this->id}.description_prefix") : trans('filament-umami-widgets::translations.widget.global.description_prefix')) .
                trans("filament-umami-widgets::translations.widget.{$this->id}.description") .
                (Lang::has("filament-umami-widgets::translations.widget.{$this->id}.description_postfix") ? trans("filament-umami-widgets::translations.widget.{$this->id}.description_postfix") : trans('filament-umami-widgets::translations.widget.global.description_postfix'));
        }

        return null;
    }
}
