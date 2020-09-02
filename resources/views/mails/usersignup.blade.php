@component('mail::message')
# Your account created successfully.
Hi! {{$mailData['name']}} <br>
Your password for <b> {{$mailData['email']}} </b> is <br>
<b>12345678</b>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
