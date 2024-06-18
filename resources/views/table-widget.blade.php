@php
    $tableHeading = $this->getTableHeading();
    $headers = $this->getHeaders();
    $data = $this->getData();
    $description = $this->getDescription();
@endphp
<x-filament-widgets::widget class="fi-wi-stats-overview">
    <div
        @if ($pollingInterval = $this->getPollingInterval())
            wire:poll.{{ $pollingInterval }}
        @endif
    >
        <div
            class="fi-ta-ctn divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">
            @if ($tableHeading)
                <div class="fi-ta-header-ctn divide-y divide-gray-200 dark:divide-white/10">
                    <div class="fi-ta-header flex flex-col gap-3 p-4 sm:px-6 sm:flex-row sm:items-center">
                        <div class="grid gap-y-1">
                            <h3 class="fi-ta-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                                {{ $this->getTableHeading() }}
                            </h3>
                        </div>
                    </div>
                </div>
            @endif

            <div class="fi-ta-content relative divide-y divide-gray-200 overflow-x-auto dark:divide-white/10 dark:border-t-white/10">
                <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
                    <thead class="divide-y divide-gray-200 dark:divide-white/5">
                    <tr class="bg-gray-50 dark:bg-white/5">
                        @foreach ($headers as $header)
                            <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6 fi-table-header-cell-title" style="width:{{ $header['width'] }}">
                            <span class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-start">
                                <span class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                    {{ $header['label'] }}
                                </span>
                            </span>
                            </th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
                    @foreach ($data as $row)
                        <tr class="fi-ta-row [@media(hover:hover)]:transition [@media(hover:hover)]:duration-75">
                            @foreach ($headers as $header)
                                <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 fi-table-cell-title">
                                    <div class="fi-ta-col-wrp">
                                        <div class="flex w-full disabled:pointer-events-none justify-start text-start">
                                            <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">
                                                <div class="flex">
                                                    <div class="flex max-w-max" style="">
                                                        <div class="fi-ta-text-item inline-flex items-center gap-1.5">
                                                    <span class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white" style="">
                                                        {{ $row[$header['name']] ?? '' }}
                                                    </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if ($description)
                <div class="flex items-center gap-x-1 px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                    <span class="fi-wi-stats-overview-stat-description text-sm text-gray-500 dark:text-gray-400 fi-color-gray">
                        {{ $description }}
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
