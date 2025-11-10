<x-app-layout>
    <x-slot name="header">
        {{ __('Notificaciones') }}
    </x-slot>

    <style>
        .highlighted-box {
            background-color: #ffdef0; /* --strawberry-light */
            border: 1px solid #ff4b65; /* --strawberry */
            border-left: 5px solid #9c0000; /* --strawberry-dark */
            padding: 1rem;
            border-radius: 8px;
        }
        .notification-target {
            transition: background-color 0.5s ease;
        }
        .is-highlighted {
            background-color: #ffdef0 !important; /* Corresponde a --strawberry-light */
            border-left: 3px solid #9c0000 !important; /* Corresponde a --strawberry-dark */
            padding-left: 1rem;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Tus Notificaciones</h3>

                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    @forelse ($notifications as $notification)
                        @php
                            $isHighlighted = session('highlighted_id') && session('highlighted_id') == $notification->id;
                        @endphp
                        <div id="notification-{{ $notification->id }}" 
                             class="notification-target border-b border-gray-200 py-4 {{ $notification->read_at ? 'bg-gray-50' : 'bg-white' }} {{ $isHighlighted ? 'is-highlighted' : '' }}">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm {{ $notification->read_at ? 'text-gray-500' : 'font-semibold text-gray-800' }}">
                                        {{ $notification->data['messages'][0] ?? 'Alerta general' }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                @unless ($notification->read_at)
                                    <a href="{{ route('notifications.show', $notification->id) }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                                        Marcar como leída
                                    </a>
                                @else
                                    <span class="text-xs text-gray-500 bg-gray-100 rounded-full px-2 py-1">Leída</span>
                                @endunless
                            </div>
                            @if (count($notification->data['messages']) > 1)
                                <ul class="list-disc list-inside text-xs text-gray-600 mt-2">
                                    @foreach (array_slice($notification->data['messages'], 1) as $message)
                                        <li>{{ $message }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-600">No tienes notificaciones.</p>
                    @endforelse

                    <div class="mt-4">
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
