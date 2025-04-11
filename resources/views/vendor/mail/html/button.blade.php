@props([
    'url',
    'color' => 'primary',
    'align' => 'center',
])
<table class="action" align="{{ $align }}" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="{{ $align }}">
<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="{{ $align }}">
<table border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td>
    <a href="{{ $url }}" class="button" target="_blank"
       style="background-color: #5da57c; border: 8px solid #5da57c; color: #fff; display: inline-block; text-decoration: none; border-radius: 4px;">
        {{ $slot }}
    </a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
