@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://api.carlosd-dev.me/images/logo.png" class="logo" alt="Nova Logo">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
