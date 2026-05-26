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

    $imageClass = match($size) {
        'xs' => 'w-4 h-4',
        'sm' => 'w-5 h-5',
        'md' => 'w-6 h-6',
        'lg' => 'w-7 h-7',
        default => 'w-5 h-5',
    };

    $logo = null;
    $bgClass = 'bg-slate-500';
    $label = 'CH';

    if (str_contains($name, 'shopee')) {
        $logo = 'shopee.png';
        $bgClass = 'bg-orange-500';
        $label = 'S';
    } elseif (str_contains($name, 'tokopedia')) {
        $logo = 'tokopedia.png';
        $bgClass = 'bg-emerald-500';
        $label = 'T';
    } elseif (str_contains($name, 'tiktok')) {
        $logo = 'tiktok-shop.png';
        $bgClass = 'bg-slate-900';
        $label = 'TT';
    } elseif (str_contains($name, 'whatsapp') || str_contains($name, 'wa')) {
        $logo = 'whatsapp.png';
        $bgClass = 'bg-green-500';
        $label = 'WA';
    } elseif (str_contains($name, 'instagram') || str_contains($name, 'ig')) {
        $logo = 'instagram.png';
        $bgClass = 'bg-pink-500';
        $label = 'IG';
    } elseif (str_contains($name, 'facebook') || str_contains($name, 'fb')) {
        $logo = 'facebook.png';
        $bgClass = 'bg-blue-600';
        $label = 'FB';
    } elseif (str_contains($name, 'gofood') || str_contains($name, 'gojek')) {
        $logo = 'gofood.png';
        $bgClass = 'bg-emerald-700';
        $label = 'GO';
    } elseif (str_contains($name, 'grab')) {
        $logo = 'grabfood.png';
        $bgClass = 'bg-green-600';
        $label = 'GR';
    } elseif (str_contains($name, 'website') || str_contains($name, 'web')) {
        $logo = 'website.png';
        $bgClass = 'bg-sky-600';
        $label = 'WEB';
    } elseif (str_contains($name, 'offline') || str_contains($name, 'toko')) {
        $logo = 'offline.png';
        $bgClass = 'bg-amber-600';
        $label = 'OFF';
    }

    $logoPath = $logo ? public_path('assets/channel-logos/' . $logo) : null;
    $logoExists = $logoPath && file_exists($logoPath);
@endphp

<div
    title="{{ $channel }}"
    class="{{ $sizeClass }} rounded-full {{ $logoExists ? 'bg-white border border-slate-200' : $bgClass }} text-white font-bold flex items-center justify-center shrink-0 shadow-sm overflow-hidden"
>
    @if($logoExists)
        <img
            src="{{ asset('assets/channel-logos/' . $logo) }}"
            alt="{{ $channel }}"
            class="{{ $imageClass }} object-contain"
        >
    @else
        <span class="leading-none tracking-tight">{{ $label }}</span>
    @endif
</div>