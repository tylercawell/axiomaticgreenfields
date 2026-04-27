<x-mail::message>
# Good Day {{ $employee->first_name }},

A commission note has been allocated to you for the amount of R{{ number_format($amount, 2) }}.


Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
