@component('mail::message')
    @if ($email_template)
        {!! $body !!}
    @else
        # Website {{ $mailData['status'] }}

        Your website <b>({{ $mailData['site'] }})</b> status has been changed to <br>
        {{ $mailData['status'] }}


        Thanks,<br>
    @endif
    {{ config('app.name') }}<br>
    https://monitor.aladdinapps.com
@endcomponent
