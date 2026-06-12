<nav class="navbar navbar-expand navbar-light navbar-top">
    <div class="container-fluid">
        <div class="navbar-left">
            <button class="btn sidebar-toggle d-none d-md-block" id="sidebarToggleMobile">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        
        <div class="navbar-right">
            <ul class="navbar-nav">
                <!-- Notifications -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        @php
                            $notificationsCount = auth()->user()->unreadNotifications()->count();
                        @endphp
                        @if($notificationsCount > 0)
                            <span class="badge bg-danger notification-badge">{{ $notificationsCount }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-end notification-dropdown">
                        <div class="dropdown-header">
                            <h6 class="mb-0">Notifications</h6>
                            @if($notificationsCount > 0)
                                <a href="#" class="mark-all-read">Mark all as read</a>
                            @endif
                        </div>
                        <div class="dropdown-body">
                            @if($notificationsCount > 0)
                                @foreach(auth()->user()->unreadNotifications->take(5) as $notification)
                                    <a href="{{ $notification->data['url'] ?? '#' }}" class="dropdown-item">
                                        <div class="notification-item">
                                            <div class="notification-icon">
                                                <i class="{{ $notification->data['icon'] ?? 'fas fa-bell' }}"></i>
                                            </div>
                                            <div class="notification-content">
                                                <h6>{{ $notification->data['title'] ?? 'Notification' }}</h6>
                                                <p class="mb-0 text-muted">{{ $notification->data['message'] ?? '' }}</p>
                                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-bell-slash fa-2x text-muted mb-2"></i>
                                    <p class="mb-0 text-muted">No new notifications</p>
                                </div>
                            @endif
                        </div>
                        <div class="dropdown-footer">
                        @if(auth()->check() && auth()->user()->unreadNotifications()->count() > 0)
                            <button type="button" class="btn btn-sm btn-outline-primary w-100 mark-all-read">
                                Mark All as Read
                            </button>
                        @else
                            <button type="button" class="btn btn-sm btn-outline-secondary w-100" disabled>
                                No New Notifications
                            </button>
                        @endif
                    </div>
                    </div>
                </li>
                
                <!-- Messages -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-envelope"></i>
                        @php
                            $unreadMessages = \App\Models\ContactMessage::where('status', 'unread')->count();
                        @endphp
                        @if($unreadMessages > 0)
                            <span class="badge bg-danger notification-badge">{{ $unreadMessages }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-end message-dropdown">
                        <div class="dropdown-header">
                            <h6 class="mb-0">Messages</h6>
                            @if($unreadMessages > 0)
                                <span class="badge bg-primary">{{ $unreadMessages }} new</span>
                            @endif
                        </div>
                        <div class="dropdown-body">
                            @if($unreadMessages > 0)
                                @foreach(\App\Models\ContactMessage::where('status', 'unread')->latest()->take(3)->get() as $message)
                                    <a href="{{ route('admin.messages.show', $message->id) }}" class="dropdown-item">
                                        <div class="message-item">
                                            <div class="message-avatar">
                                                <img src="{{ asset('images/default-avatar.png') }}" alt="{{ $message->name }}">
                                            </div>
                                            <div class="message-content">
                                                <h6>{{ $message->name }}</h6>
                                                <p class="mb-0 text-muted text-truncate">{{ $message->message }}</p>
                                                <small class="text-muted">{{ $message->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-envelope-open fa-2x text-muted mb-2"></i>
                                    <p class="mb-0 text-muted">No new messages</p>
                                </div>
                            @endif
                        </div>
                        <div class="dropdown-footer">
                            <button type="button" class="btn btn-sm btn-outline-primary w-100">
                                View All Messages
                            </button>
                        </div>
                    </div>
                </li>
                
                <!-- User Profile -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar-sm">
                            <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('images/default-avatar.png') }}" 
                                 alt="{{ Auth::user()->name }}">
                        </div>
                        <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                        <div class="dropdown-header">
                            <div class="d-flex align-items-center">
                                <div class="user-avatar">
                                    <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('images/default-avatar.png') }}" 
                                         alt="{{ Auth::user()->name }}">
                                </div>
                                <div class="user-info">
                                    <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                                    <small class="text-muted">{{ Auth::user()->email }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <a href="#" class="dropdown-item text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                        </form>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>