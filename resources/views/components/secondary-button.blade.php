<button {{ $attributes->merge([
    'type' => 'button', 
    'class' => 'inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-lg font-semibold text-sm text-gray-800 transition hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-strawberry focus:ring-offset-2'
    ]) }}>
    {{ $slot }}
</button>
