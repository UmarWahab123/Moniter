@component('mail::message')
# SSL Expiry Notification

Your SSL certificate for these websites is going to expire this week.<br>
<div class="table-responsive">
    <table class="table  table-stripped text-center">
        <thead>
            <tr>
                <th>Title</th>
                <th>Url</th>
                <th>Expiry Date</th>
            </tr>
        </thead>
        <tbody>
        @foreach($monitors_arr as $sites)
        <tr>
            <td>{{$sites['name']}}</td>
            <td>{{$sites['url']}}</td>
            <td>{{$sites['certificate_expiration_date']}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>



Thanks,<br>
{{ config('app.name') }}<br>
https://monitor.aladdinapps.com
@endcomponent
