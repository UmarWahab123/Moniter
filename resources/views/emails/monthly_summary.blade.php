@component('mail::message')
# Websites Summary

Websites Summary of {{$lastmonthName}}.<br>
<div class="table-responsive">
    <table class="table w-100   table-dark"  width="100%">
        <thead>
            <tr>
                <th>Title</th>
                <th>Url</th>
                <th>SSL Expiry Date</th>
                <th>Last Check</th>
                <th>Last Down</th>
                <th>Last up</th>
                <th>Down Times</th>
                <th>Up Times</th>
            </tr>
        </thead>
        <tbody>
        @foreach($urlData as $sites)
        <tr>
            <td>{{$sites['title']}}</td>
            <td>{{$sites['url']}}</td>
            <td>{{$sites['ssl_expiry_date']}}</td>
            <td>{{$sites['last_check']}}</td>
            <td>{{$sites['last_down']}}</td>
            <td>{{$sites['last_up']}}</td>
            <td>{{$sites['down_count']}}</td>
            <td>{{$sites['up_count']}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
