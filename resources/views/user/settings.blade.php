@extends('user.layouts.app')

@section('title', 'Account Settings')
@section('page-title', 'Account Settings')

@section('content')
<div class="row">
    <div class="col-lg-3 mb-4">
        <!-- Settings Menu -->
        <div class="card">
            <div class="card-body p-0">
                <div class="list-group list-group-flush" id="settingsTabs" role="tablist">
                    <a class="list-group-item list-group-item-action active" 
                       id="notifications-tab" 
                       data-bs-toggle="tab" 
                       href="#notifications" 
                       role="tab">
                        <i class="fas fa-bell me-2"></i> Notifications
                    </a>
                    <a class="list-group-item list-group-item-action" 
                       id="privacy-tab" 
                       data-bs-toggle="tab" 
                       href="#privacy" 
                       role="tab">
                        <i class="fas fa-shield-alt me-2"></i> Privacy & Security
                    </a>
                    <a class="list-group-item list-group-item-action" 
                       id="preferences-tab" 
                       data-bs-toggle="tab" 
                       href="#preferences" 
                       role="tab">
                        <i class="fas fa-sliders-h me-2"></i> Preferences
                    </a>
                    <a class="list-group-item list-group-item-action" 
                       id="sessions-tab" 
                       data-bs-toggle="tab" 
                       href="#sessions" 
                       role="tab">
                        <i class="fas fa-desktop me-2"></i> Active Sessions
                    </a>
                    <a class="list-group-item list-group-item-action text-danger" 
                       id="danger-tab" 
                       data-bs-toggle="tab" 
                       href="#danger" 
                       role="tab">
                        <i class="fas fa-exclamation-triangle me-2"></i> Danger Zone
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-9">
        <div class="tab-content" id="settingsTabContent">
            <!-- Notifications Settings -->
            <div class="tab-pane fade show active" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Notification Settings</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user.settings.updatePreferences') }}" method="POST" id="notificationForm">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-4">
                                <h6 class="mb-3">Email Notifications</h6>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="email_notifications" 
                                           name="email_notifications" 
                                           value="1"
                                           {{ old('email_notifications', session('user_settings.email_notifications', true)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="email_notifications">
                                        Enable all email notifications
                                    </label>
                                </div>
                                
                                <div class="ms-4">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="order_updates" 
                                               name="order_updates" 
                                               value="1"
                                               {{ old('order_updates', session('user_settings.order_updates', true)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="order_updates">
                                            Order updates and shipping notifications
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="product_updates" 
                                               name="product_updates" 
                                               value="1"
                                               {{ old('product_updates', session('user_settings.product_updates', true)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="product_updates">
                                            Product updates and restock alerts
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="promotional_emails" 
                                               name="promotional_emails" 
                                               value="1"
                                               {{ old('promotional_emails', session('user_settings.promotional_emails', true)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="promotional_emails">
                                            Promotional emails and offers
                                        </label>
                                    </div>
                                    
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="newsletter_subscription" 
                                               name="newsletter_subscription" 
                                               value="1"
                                               {{ old('newsletter_subscription', session('user_settings.newsletter_subscription', true)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="newsletter_subscription">
                                            Newsletter subscription
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <h6 class="mb-3">SMS Notifications</h6>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="sms_notifications" 
                                           name="sms_notifications" 
                                           value="1"
                                           {{ old('sms_notifications', session('user_settings.sms_notifications', false)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sms_notifications">
                                        Enable SMS notifications
                                    </label>
                                    <small class="d-block text-muted">
                                        Receive order updates via SMS (standard rates may apply)
                                    </small>
                                </div>
                            </div>
                            
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Save Notification Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Privacy & Security -->
            <div class="tab-pane fade" id="privacy" role="tabpanel" aria-labelledby="privacy-tab">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Privacy & Security</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user.settings.updatePrivacy') }}" method="POST" id="privacyForm">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-4">
                                <h6 class="mb-3">Profile Visibility</h6>
                                <div class="mb-3">
                                    <select class="form-select" id="profile_visibility" name="profile_visibility">
                                        <option value="public" {{ old('profile_visibility', session('user_settings.profile_visibility', 'public')) == 'public' ? 'selected' : '' }}>
                                            Public - Anyone can see my profile
                                        </option>
                                        <option value="friends" {{ old('profile_visibility', session('user_settings.profile_visibility', 'public')) == 'friends' ? 'selected' : '' }}>
                                            Friends Only - Only my friends can see my profile
                                        </option>
                                        <option value="private" {{ old('profile_visibility', session('user_settings.profile_visibility', 'public')) == 'private' ? 'selected' : '' }}>
                                            Private - Only I can see my profile
                                        </option>
                                    </select>
                                </div>
                                
                                <div class="form-check mb-2">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="show_email" 
                                           name="show_email" 
                                           value="1"
                                           {{ old('show_email', session('user_settings.show_email', false)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="show_email">
                                        Show email address on profile
                                    </label>
                                </div>
                                
                                <div class="form-check mb-2">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="show_phone" 
                                           name="show_phone" 
                                           value="1"
                                           {{ old('show_phone', session('user_settings.show_phone', false)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="show_phone">
                                        Show phone number on profile
                                    </label>
                                </div>
                                
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="search_engine_indexing" 
                                           name="search_engine_indexing" 
                                           value="1"
                                           {{ old('search_engine_indexing', session('user_settings.search_engine_indexing', true)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="search_engine_indexing">
                                        Allow search engines to index my profile
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <h6 class="mb-3">Data Sharing</h6>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="data_sharing" 
                                           name="data_sharing" 
                                           value="1"
                                           {{ old('data_sharing', session('user_settings.data_sharing', false)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="data_sharing">
                                        Allow sharing of anonymous usage data to improve our services
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <h6 class="mb-3">Cookie Preferences</h6>
                                <div class="mb-3">
                                    <select class="form-select" id="cookie_preferences" name="cookie_preferences">
                                        <option value="essential" {{ old('cookie_preferences', session('user_settings.cookie_preferences', 'essential')) == 'essential' ? 'selected' : '' }}>
                                            Essential Only - Required for site functionality
                                        </option>
                                        <option value="all" {{ old('cookie_preferences', session('user_settings.cookie_preferences', 'essential')) == 'all' ? 'selected' : '' }}>
                                            All Cookies - Includes analytics and advertising
                                        </option>
                                        <option value="custom" {{ old('cookie_preferences', session('user_settings.cookie_preferences', 'essential')) == 'custom' ? 'selected' : '' }}>
                                            Custom - Choose which cookies to allow
                                        </option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Save Privacy Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Preferences -->
            <div class="tab-pane fade" id="preferences" role="tabpanel" aria-labelledby="preferences-tab">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Preferences</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user.settings.updatePreferences') }}" method="POST" id="preferencesForm">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="language" class="form-label">Language</label>
                                    <select class="form-select" id="language" name="language">
                                        <option value="en" {{ old('language', session('user_settings.language', 'en')) == 'en' ? 'selected' : '' }}>English</option>
                                        <option value="es" {{ old('language', session('user_settings.language', 'en')) == 'es' ? 'selected' : '' }}>Spanish</option>
                                        <option value="fr" {{ old('language', session('user_settings.language', 'en')) == 'fr' ? 'selected' : '' }}>French</option>
                                        <option value="de" {{ old('language', session('user_settings.language', 'en')) == 'de' ? 'selected' : '' }}>German</option>
                                        <option value="it" {{ old('language', session('user_settings.language', 'en')) == 'it' ? 'selected' : '' }}>Italian</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="currency" class="form-label">Currency</label>
                                    <select class="form-select" id="currency" name="currency">
                                        <option value="USD" {{ old('currency', session('user_settings.currency', 'USD')) == 'USD' ? 'selected' : '' }}>US Dollar ($)</option>
                                        <option value="EUR" {{ old('currency', session('user_settings.currency', 'USD')) == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                                        <option value="GBP" {{ old('currency', session('user_settings.currency', 'USD')) == 'GBP' ? 'selected' : '' }}>British Pound (£)</option>
                                        <option value="INR" {{ old('currency', session('user_settings.currency', 'USD')) == 'INR' ? 'selected' : '' }}>Indian Rupee (₹)</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="timezone" class="form-label">Timezone</label>
                                <select class="form-select" id="timezone" name="timezone">
                                    @foreach(timezone_identifiers_list() as $timezone)
                                        <option value="{{ $timezone }}" 
                                                {{ old('timezone', session('user_settings.timezone', 'UTC')) == $timezone ? 'selected' : '' }}>
                                            {{ $timezone }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Save Preferences
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Active Sessions -->
            <div class="tab-pane fade" id="sessions" role="tabpanel" aria-labelledby="sessions-tab">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Active Sessions</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            This shows all devices where you're currently logged in.
                        </div>
                        
                        @if(isset($sessions) && $sessions && $sessions->count() > 0)
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Device & Browser</th>
                                            <th>IP Address</th>
                                            <th>Last Activity</th>
                                            <th>Current</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sessions as $session)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            @php
                                                                $userAgent = $session->user_agent ?? '';
                                                                $isMobile = stripos($userAgent, 'mobile') !== false || 
                                                                            stripos($userAgent, 'android') !== false || 
                                                                            stripos($userAgent, 'iphone') !== false;
                                                                $isTablet = stripos($userAgent, 'tablet') !== false || 
                                                                            stripos($userAgent, 'ipad') !== false;
                                                            @endphp
                                                            
                                                            @if($isTablet)
                                                                <i class="fas fa-tablet-alt fa-2x text-success"></i>
                                                            @elseif($isMobile)
                                                                <i class="fas fa-mobile-alt fa-2x text-info"></i>
                                                            @else
                                                                <i class="fas fa-desktop fa-2x text-primary"></i>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <strong>{{ $session->browser ?? 'Unknown Browser' }}</strong>
                                                            <div class="text-muted small">
                                                                {{ $session->platform ?? 'Unknown Platform' }}
                                                                @if($isTablet)
                                                                    • Tablet
                                                                @elseif($isMobile)
                                                                    • Mobile
                                                                @else
                                                                    • Desktop
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $session->ip_address ?? 'Unknown' }}</td>
                                                <td>
                                                    @if(isset($session->last_activity))
                                                        {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}
                                                    @else
                                                        Unknown
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(isset($session->is_current_device) && $session->is_current_device)
                                                        <span class="badge bg-success">Current</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(!isset($session->is_current_device) || !$session->is_current_device)
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger logout-session"
                                                                data-session-id="{{ $session->id ?? '' }}">
                                                            Logout
                                                        </button>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="text-end mt-3">
                                <button type="button" 
                                        class="btn btn-danger"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#logoutAllModal">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout From All Devices
                                </button>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                <h5>No active sessions found</h5>
                                <p class="text-muted">You're not logged in on any other devices.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Danger Zone -->
            <div class="tab-pane fade" id="danger" role="tabpanel" aria-labelledby="danger-tab">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">Danger Zone</h5>
                    </div>
                    <div class="card-body">
                        <!-- Export Data -->
                        <div class="mb-4">
                            <h6 class="mb-3">Export Your Data</h6>
                            <p class="text-muted mb-3">
                                Download all your personal data in JSON format. This includes your profile information, order history, reviews, and more.
                            </p>
                            <a href="{{ route('user.settings.exportData') }}" class="btn btn-outline-primary">
                                <i class="fas fa-download me-2"></i> Export My Data
                            </a>
                        </div>
                        
                        <!-- Delete Account -->
                        <div>
                            <h6 class="mb-3 text-danger">Delete Account</h6>
                            <p class="text-muted mb-3">
                                Once you delete your account, there is no going back. Please be certain.
                            </p>
                            <button type="button" 
                                    class="btn btn-danger"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteAccountModal">
                                <i class="fas fa-trash me-2"></i> Delete My Account
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Logout All Modal -->
<div class="modal fade" id="logoutAllModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Logout From All Devices</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to logout from all devices except this one?</p>
                <p class="text-muted">You'll need to login again on all other devices.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('user.settings.logoutOtherSessions') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Yes, Logout All</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Delete Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone. All your data will be permanently deleted.
                </div>
                
                <form id="deleteAccountForm" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="delete_password" class="form-label">Enter your password to confirm:</label>
                        <input type="password" 
                               class="form-control" 
                               id="delete_password" 
                               name="password"
                               required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="delete_reason" class="form-label">Reason for leaving (optional):</label>
                        <textarea class="form-control" 
                                  id="delete_reason" 
                                  name="reason" 
                                  rows="3"
                                  placeholder="Help us improve by telling us why you're leaving..."></textarea>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" 
                               type="checkbox" 
                               id="delete_confirm" 
                               name="confirm"
                               required>
                        <label class="form-check-label" for="delete_confirm">
                            I understand that this action cannot be undone and all my data will be permanently deleted.
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteAccount">Delete My Account</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Initialize Bootstrap tabs
        var triggerTabList = [].slice.call(document.querySelectorAll('#settingsTabs a'));
        triggerTabList.forEach(function (triggerEl) {
            var tabTrigger = new bootstrap.Tab(triggerEl);
            triggerEl.addEventListener('click', function (event) {
                event.preventDefault();
                tabTrigger.show();
            });
        });
        
        // Show success/error messages
        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif
        
        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif
        
        // Form submissions
        $('#notificationForm, #privacyForm, #preferencesForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    toastr.success(response.message || 'Settings updated successfully!');
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('Something went wrong! Please try again.');
                    }
                }
            });
        });
        
        // Logout specific session
        $('.logout-session').on('click', function() {
            const sessionId = $(this).data('session-id');
            const button = $(this);
            
            Swal.fire({
                title: 'Logout from this device?',
                text: "You'll need to login again on this device.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, logout'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/user/settings/logout-device/' + sessionId,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            toastr.success(response.message || 'Device logged out successfully!');
                            button.closest('tr').fadeOut();
                        },
                        error: function(xhr) {
                            toastr.error(xhr.responseJSON?.message || 'Something went wrong!');
                        }
                    });
                }
            });
        });
        
        // Delete account
        $('#confirmDeleteAccount').on('click', function() {
            const formData = $('#deleteAccountForm').serialize();
            
            Swal.fire({
                title: 'Are you absolutely sure?',
                text: "This action cannot be undone. All your data will be permanently deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete my account',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return $.ajax({
                        url: '{{ route("user.settings.deleteAccount") }}',
                        type: 'POST',
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(response => {
                        return response;
                    }).catch(error => {
                        Swal.showValidationMessage(
                            error.responseJSON?.message || 'Something went wrong!'
                        );
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Account Deleted!',
                        'Your account has been successfully deleted.',
                        'success'
                    ).then(() => {
                        window.location.href = '{{ route("home") }}';
                    });
                }
            });
        });
    });
</script>
@endpush