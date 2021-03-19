@isset($text)
    <p>Text content:</p>
    {{ $text }}
@endisset
<br>
@isset($html)
    <p>Html content:</p>
    {!! $html !!}
@endisset