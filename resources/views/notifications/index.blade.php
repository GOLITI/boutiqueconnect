@extends('layouts.app') {{-- Utilise votre layout principal --}}

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Notifications</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li><a class="active" href="{{ route('notifications.index') }}">Notifications</a></li>
            </ul>
        </div>
        <form action="{{ route('notifications.mark_all_as_read') }}" method="POST" style="display: inline-block;">
            @csrf
            <button type="submit" class="btn-download" style="background-color: var(--green); border: none; cursor: pointer;">
                <i class='bx bxs-check-circle' ></i>
                <span class="text">Marquer tout comme lu</span>
            </button>
        </form>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Mes Notifications</h3>
            </div>

            @if (session('success'))
                <div style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            <ul style="list-style: none; padding: 0;">
                @forelse ($notifications as $notification)
                    <li style="background-color: var(--grey); padding: 15px; border-radius: 10px; margin-bottom: 10px; display: flex; align-items: center; justify-content: space-between;
                               {{ $notification->is_read ? 'opacity: 0.7;' : 'border-left: 5px solid var(--blue);' }}">
                        <div style="display: flex; align-items: center;">
                            <i class='bx {{ $notification->is_read ? 'bxs-bell-ring' : 'bxs-bell' }}' style="font-size: 1.8em; color: {{ $notification->is_read ? 'var(--dark-grey)' : 'var(--blue)' }}; margin-right: 15px;"></i>
                            <div>
                                <p style="font-weight: {{ $notification->is_read ? 'normal' : 'bold' }}; color: var(--dark); margin-bottom: 5px;">
                                    {{ $notification->message }}
                                </p>
                                <span style="font-size: 0.85em; color: var(--dark-grey);">
                                    Reçu le {{ $notification->created_at->format('d/m/Y à H:i') }}
                                </span>
                            </div>
                        </div>
                        @if (!$notification->is_read)
                            <form action="{{ route('notifications.mark_as_read', $notification->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                <button type="submit" style="background-color: var(--blue); color: white; padding: 8px 15px; border-radius: 8px; border: none; font-size: 14px; cursor: pointer; transition: background-color 0.3s ease;">
                                    Marquer comme lu
                                </button>
                            </form>
                        @else
                            <span style="color: var(--dark-grey); font-size: 0.9em;">Lue</span>
                        @endif
                    </li>
                @empty
                    <li style="background-color: var(--grey); padding: 20px; border-radius: 10px; text-align: center; color: var(--dark-grey);">
                        Aucune notification pour l'instant.
                    </li>
                @endforelse
            </ul>

            <div style="margin-top: 20px;">
                {{ $notifications->links() }} {{-- Affiche les liens de pagination --}}
            </div>
        </div>
    </div>
@endsection
