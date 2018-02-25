@component('mail::message')
# New Message

Someone new sent you a message asking you to tutor them!

@component('mail::button', ['url' => config('app.url') ])
View Message
@endcomponent

@component('mail::panel')
This is the panel content.
@endcomponent

@component('mail::table')
|        |          |
| ------------- |-------------|
| **Preferred days**      | Centered      |
| **Preferred meeting time** | Right-Aligned |
| **Preferred meeting location** | Right-Aligned |
| **Hours per lesson** | Right-Aligned |

@endcomponent
![alt text](https://github.com/adam-p/markdown-here/raw/master/src/common/images/icon48.png "Logo Title Text 1")
Great work! you can reply to the person from {{ config('app.name') }}.

@endcomponent