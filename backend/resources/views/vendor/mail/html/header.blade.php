<!-- @props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
    <img src="{{ asset('images/next.png') }}" alt="Next" style="max-height: 50px; display: inline-block;">
</a>
</td>
</tr> -->
 @props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/next.png'))) }}" alt="Next" style="max-height: 125px;">
</a>
</td>
</tr>