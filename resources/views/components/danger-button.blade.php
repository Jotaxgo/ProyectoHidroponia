<button {{ $attributes->merge([
    'type' => 'submit', 
    'class' => 'inline-flex items-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm text-white transition',
    'style' => 'background-color: var(--strawberry); box-shadow: var(--shadow-sm);',
    'onmouseover' => "this.style.backgroundColor='var(--strawberry-dark)'",
    'onmouseout' => "this.style.backgroundColor='var(--strawberry)'"
    ]) }}>
    {{ $slot }}
</button>
