@component('mail::message')
<style>
.button {
  background-color: #4CAF50;
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
}
</style>
# Your account created successfully.
Hi! {{$mailData['name']}} <br>
Your password for <b> {{$mailData['email']}} </b> is <br>
<b>12345678</b>

<a class="button" href="https://monitor.aladdinapps.com/login">Login Here</a><br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
