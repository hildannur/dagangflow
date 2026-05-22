@props([
    'channel' => '',
    'size' => 'sm',
])

@php
    $name = strtolower(trim((string) $channel));

    $sizeClass = match($size) {
        'xs' => 'w-7 h-7 text-[10px]',
        'sm' => 'w-8 h-8 text-[11px]',
        'md' => 'w-10 h-10 text-xs',
        'lg' => 'w-12 h-12 text-sm',
        default => 'w-8 h-8 text-[11px]',
    };

    $bgClass = 'bg-slate-500';
    $label = 'CH';

    if (str_contains($name, 'shopee')) {
        $bgClass = 'bg-orange-500';
        $label = 'S';
    } elseif (str_contains($name, 'tokopedia')) {
        $bgClass = 'bg-emerald-500';
        $label = 'T';
    } elseif (str_contains($name, 'tiktok')) {
        $bgClass = 'bg-slate-900';
        $label = 'TT';
    } elseif (str_contains($name, 'lazada')) {
        $bgClass = 'bg-purple-600';
        $label = 'L';
    } elseif (str_contains($name, 'instagram')) {
        $bgClass = 'bg-pink-500';
        $label = 'IG';
    } elseif (str_contains($name, 'whatsapp')) {
        $bgClass = 'bg-green-500';
        $label = 'WA';
    } elseif (str_contains($name, 'facebook')) {
        $bgClass = 'bg-blue-600';
        $label = 'FB';
    } elseif (str_contains($name, 'website') || str_contains($name, 'web')) {
        $bgClass = 'bg-sky-600';
        $label = 'WEB';
    } elseif (str_contains($name, 'offline') || str_contains($name, 'toko')) {
        $bgClass = 'bg-amber-600';
        $label = 'OFF';
    } elseif (str_contains($name, 'grab')) {
        $bgClass = 'bg-green-600';
        $label = 'GR';
    } elseif (str_contains($name, 'gofood') || str_contains($name, 'gojek')) {
        $bgClass = 'bg-emerald-700';
        $label = 'GO';
    }
@endphp

<div
    title="{{ $channel }}"
    class="{{ $sizeClass }} {{ $bgClass }} rounded-full text-white font-bold flex items-center justify-center shrink-0 shadow-sm"
>
    <span class="leading-none tracking-tight">{{ $label }}</span>
</div>