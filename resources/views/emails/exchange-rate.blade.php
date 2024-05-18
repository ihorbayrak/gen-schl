@component('mail::message')
    Current exchange rate: {{$rate}}

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
