@component('mail::message')
# Webasite Monitoring

Your Website <b>{{ $mailData['title'] }} ({{$mailData['url'] }})</b>
is registered and being monitored now.

SSl Certificate Check = {{ $mailData['ssl'] }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
