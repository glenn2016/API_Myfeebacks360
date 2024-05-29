@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'MyFeeback360')
<img src="{{ url('logo.png') }}" class="logo" alt="MyFeedback360">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
