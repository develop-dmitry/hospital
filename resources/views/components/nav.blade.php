@if(!empty($items))
    <nav class="{{ implode(' ', $tagClasses) }}">
        <ul class="nav__list">
            @foreach ($items as $uri => $name)
                <li class="nav__item">
                    <a href="{{ $uri }}" class="nav__link">
                        {{ $name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
@endif
