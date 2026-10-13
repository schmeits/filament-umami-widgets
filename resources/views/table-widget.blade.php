@php
    $tableHeading = $this->getTableHeading();
    $headers = $this->getHeaders();
    $data = array_slice($this->getData(), 0, $this->getLimit());
    $description = $this->getDescription();
    $options = $this->getOptions();
    $hasOptions = count($options) > 0;
    $hasHeader = $tableHeading || $description || $hasOptions || $this->hasLimitedResults();
@endphp
{{--
    Only Filament's own semantic classes (fi-ta-*) and Blade components are used
    here. Since Filament 4 the panel CSS no longer contains arbitrary Tailwind
    utilities, so utility classes in a plugin view would render unstyled.
--}}
<x-filament-widgets::widget>
    <div
        @if ($pollingInterval = $this->getPollingInterval())
            wire:poll.{{ $pollingInterval }}
        @endif
        @class([
            'fi-ta-ctn',
            'fi-ta-ctn-with-header' => $hasHeader,
        ])
    >
        <div class="fi-ta-main">
            @if ($hasHeader)
                <div class="fi-ta-header fi-ta-header-adaptive-actions-position">
                    <div>
                        @if ($tableHeading)
                            <h3 class="fi-ta-header-heading">
                                {{ $tableHeading }}
                            </h3>
                        @endif

                        @if ($description)
                            <p class="fi-ta-header-description">
                                {{ $description }}
                            </p>
                        @endif
                    </div>

                    @if ($this->hasLimitedResults() || $hasOptions)
                        <div class="fi-ta-actions">
                            @if ($this->hasLimitedResults())
                                <label for="{{ $this->getId() }}-limit" class="fi-sr-only">
                                    {{ trans('filament-umami-widgets::translations.widget.global.limit_label') }}
                                </label>
                                <x-filament::input.wrapper>
                                    <x-filament::input.select id="{{ $this->getId() }}-limit" wire:model.live="limit">
                                        @foreach ([5, 10, 20, 50] as $count)
                                            <option value="{{ $count }}">{{ trans('filament-umami-widgets::translations.widget.global.limit', ['count' => $count]) }}</option>
                                        @endforeach
                                        <option value="500">{{ trans('filament-umami-widgets::translations.widget.global.limit_show_all') }}</option>
                                    </x-filament::input.select>
                                </x-filament::input.wrapper>
                            @endif

                            @if ($hasOptions)
                                <label for="{{ $this->getId() }}-option" class="fi-sr-only">
                                    {{ trans('filament-umami-widgets::translations.widget.global.option_label') }}
                                </label>
                                <x-filament::input.wrapper>
                                    <x-filament::input.select id="{{ $this->getId() }}-option" wire:model.live="option">
                                        @foreach ($options as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </x-filament::input.select>
                                </x-filament::input.wrapper>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            <div class="fi-ta-content-ctn">
                <table class="fi-ta-table">
                    <thead>
                        <tr>
                            @foreach ($headers as $header)
                                <th class="fi-ta-header-cell" style="width: {{ $header['width'] }}">
                                    {{ $header['label'] }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $row)
                            <tr class="fi-ta-row">
                                @foreach ($headers as $header)
                                    @php
                                        $value = $row[$header['name']] ?? null;
                                    @endphp
                                    <td class="fi-ta-cell">
                                        <div class="fi-ta-col">
                                            <div class="fi-ta-text">
                                                @if (is_array($value))
                                                    @foreach ($value as $key => $count)
                                                        <span class="fi-ta-text-item">
                                                            <x-filament::badge>{{ $key }}</x-filament::badge>
                                                            {{ $count }}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span class="fi-ta-text-item">{{ $value ?? '' }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
