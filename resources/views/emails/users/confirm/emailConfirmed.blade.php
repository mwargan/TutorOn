@component('mail::message')
# Email confirmed!

Great job! You've successfully confirmed your email address.

@component('mail::button', ['url' => ''])
Go to {{ config('app.name') }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
