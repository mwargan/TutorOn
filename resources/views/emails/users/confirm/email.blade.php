@component('mail::message')
# You're almost in!

Just click the button below to verify your email address and get full access to {{ config('app.name') }}!

@component('mail::button', ['url' => config('app.name').'/admin/me/confirm-email/'.$key])
Confirm Email
@endcomponent

Didn't add an email address? Contact us directly at support.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
