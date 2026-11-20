@props(['status'])
@php
    $normalized = strtolower((string) $status);
    $labels = [
        'open' => 'Em aberto',
        'closed' => 'Fechado',
        'pending' => 'Pendente',
        'paid' => 'Pago',
        'sent' => 'Enviado',
        'eligible' => 'Elegível',
        'active' => 'Ativo',
        'inactive' => 'Inativo',
        'regular' => 'Regular',
        'delinquent' => 'Inadimplente',
    ];
@endphp
<span {{ $attributes->class(['badge', 'badge-'.$normalized]) }}>{{ $labels[$normalized] ?? ucfirst($normalized) }}</span>
