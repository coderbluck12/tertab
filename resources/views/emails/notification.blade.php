@component('mail::message')
    # {{ $title }}

    {!! nl2br(e($message)) !!} {{-- This will ensure line breaks are respected --}}

    @if(isset($button_text) && isset($button_url))
        @component('mail::button', ['url' => $button_url])
            {{ $button_text }}
        @endcomponent
    @endif

    Thanks,
    **{{ config('app.name') }} Team**
@endcomponent
