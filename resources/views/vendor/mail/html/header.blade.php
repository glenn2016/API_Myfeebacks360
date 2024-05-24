@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
<img src="{{ asset('logo.png') }}" alt="{{ config('app.name') }}" style="height: 50px;">

@else
{{ $slot }}
@endif
</a>
</td>
</tr>
