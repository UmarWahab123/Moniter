@component('mail::message')
# Website {{ $mailData['status'] }}

Your website <b>({{ $mailData['site'] }})</b> status has been changed to <br>
{{ $mailData['status'] }}


Thanks,<br>
{{ config('app.name') }}<br>
https://monitor.aladdinapps.com
@endcomponent
