{{-- resources/views/trangchu/button.blade.php --}}
@if(isset($type) && $type == 'submit')
    <button type="submit" class="{{ $class }}">
        {!! $content !!}
    </button>
@else
    <a href="{{ $href ?? '#' }}" class="{{ $class }}">
        {!! $content !!}
    </a>
@endif