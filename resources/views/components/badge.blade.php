@props(['type' => 'default'])

@php
    $classes = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize ';
    
    switch ($type) {
        case 'success':
        case 'active':
        case 'completed':
        case 'received':
            $classes .= 'bg-green-100 text-green-800';
            break;
        case 'warning':
        case 'pending':
        case 'in_progress':
        case 'partial':
            $classes .= 'bg-yellow-100 text-yellow-800';
            break;
        case 'danger':
        case 'error':
        case 'cancelled':
        case 'inactive':
        case 'rejected':
            $classes .= 'bg-red-100 text-red-800';
            break;
        case 'info':
        case 'new':
            $classes .= 'bg-blue-100 text-blue-800';
            break;
        default:
            $classes .= 'bg-slate-100 text-slate-800';
    }
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
