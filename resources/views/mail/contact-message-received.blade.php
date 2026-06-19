<x-mail::message>
# Nieuw contactbericht

Er is een nieuw bericht binnengekomen via het contactformulier.

**Van:** {{ $contactMessage->name }} ({{ $contactMessage->email }})
**Onderwerp:** {{ $contactMessage->subject }}
@if ($contactMessage->user_id)
**Lid:** ja (gebruiker #{{ $contactMessage->user_id }})
@endif

---

{{ $contactMessage->message }}

<x-mail::button :url="route('admin.contact.index')">
Bekijk in beheer
</x-mail::button>

Met liefde,<br>
{{ config('app.name') }}
</x-mail::message>
