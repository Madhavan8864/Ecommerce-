@extends('user.layouts.app')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="row">
    <div class="col-lg-4 mb-4">
        <!-- Profile Card -->
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <div class="profile-avatar mx-auto">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" 
                                 alt="{{ Auth::user()->name }}" 
                                 class="rounded-circle">
                        @else
                            <div class="avatar-placeholder rounded-circle">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        @endif
                        <a href="#" class="change-avatar" data-bs-toggle="modal" data-bs-target="#avatarModal">
                            <i class="fas fa-camera"></i>
                        </a>
                    </div>
                </div>
                
                <h4 class="mb-1">{{ Auth::user()->name }}</h4>
                <p class="text-muted mb-3">{{ Auth::user()->email }}</p>
                
                <div class="d-flex justify-content-center mb-3">
                    <div class="text-center mx-3">
                        <h5 class="mb-0">{{ Auth::user()->orderCount() }}</h5>
                        <small class="text-muted">Orders</small>
                    </div>
                    <div class="text-center mx-3">
                        <h5 class="mb-0">₹{{ number_format(Auth::user()->totalSpent(), 2) }}</h5>
                        <small class="text-muted">Total Spent</small>
                    </div>
                    <div class="text-center mx-3">
                        <h5 class="mb-0">{{ Auth::user()->reviews->count() }}</h5>
                        <small class="text-muted">Reviews</small>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('user.orders.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-shopping-bag me-2"></i> My Orders
                    </a>
                    <a href="{{ route('user.wishlist.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-heart me-2"></i> My Wishlist
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Account Status -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Account Status</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2 d-flex justify-content-between">
                        <span>Email Verification</span>
                        @if(Auth::user()->hasVerifiedEmail())
                            <span class="badge bg-success">Verified</span>
                        @else
                            <span class="badge bg-warning">Pending</span>
                        @endif
                    </li>
                    <li class="mb-2 d-flex justify-content-between">
                        <span>Phone Verification</span>
                        @if(Auth::user()->phone_verified_at)
                            <span class="badge bg-success">Verified</span>
                        @else
                            <span class="badge bg-warning">Pending</span>
                        @endif
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Member Since</span>
                        <span>{{ Auth::user()->created_at->format('d M, Y') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <!-- Profile Edit Form -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Profile</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', Auth::user()->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', Auth::user()->email) }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', Auth::user()->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if(!Auth::user()->phone_verified_at && Auth::user()->phone)
                                <a href="{{ route('verify.phone') }}" class="btn btn-sm btn-warning mt-1">
                                    Verify Phone
                                </a>
                            @endif
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" 
                                   class="form-control @error('date_of_birth') is-invalid @enderror" 
                                   id="date_of_birth" 
                                   name="date_of_birth" 
                                   value="{{ old('date_of_birth', Auth::user()->date_of_birth ? Auth::user()->date_of_birth->format('Y-m-d') : '') }}">
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-control @error('gender') is-invalid @enderror" 
                                id="gender" 
                                name="gender">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', Auth::user()->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', Auth::user()->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', Auth::user()->gender) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" 
                                  name="address" 
                                  rows="2">{{ old('address', Auth::user()->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" 
                                   class="form-control @error('city') is-invalid @enderror" 
                                   id="city" 
                                   name="city" 
                                   value="{{ old('city', Auth::user()->city) }}">
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="state" class="form-label">State</label>
                            <input type="text" 
                                   class="form-control @error('state') is-invalid @enderror" 
                                   id="state" 
                                   name="state" 
                                   value="{{ old('state', Auth::user()->state) }}">
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="zip_code" class="form-label">ZIP Code</label>
                            <input type="text" 
                                   class="form-control @error('zip_code') is-invalid @enderror" 
                                   id="zip_code" 
                                   name="zip_code" 
                                   value="{{ old('zip_code', Auth::user()->zip_code) }}">
                            @error('zip_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" 
                               class="form-control @error('country') is-invalid @enderror" 
                               id="country" 
                               name="country" 
                               value="{{ old('country', Auth::user()->country) }}">
                        @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Change Password -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Change Password</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('user.profile.updatePassword') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password *</label>
                        <input type="password" 
                               class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password" 
                               name="current_password" 
                               required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="new_password" class="form-label">New Password *</label>
                            <input type="password" 
                                   class="form-control @error('new_password') is-invalid @enderror" 
                                   id="new_password" 
                                   name="new_password" 
                                   required>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="new_password_confirmation" class="form-label">Confirm New Password *</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="new_password_confirmation" 
                                   name="new_password_confirmation" 
                                   required>
                        </div>
                    </div>
                    
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-key me-2"></i> Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Avatar Modal -->
<div class="modal fade" id="avatarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Profile Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('user.profile.updateAvatar') }}" method="POST" enctype="multipart/form-data" id="avatarForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="avatar" class="form-label">Select Image</label>
                        <input type="file" 
                               class="form-control" 
                               id="avatar" 
                               name="avatar"
                               accept="image/*">
                        <small class="text-muted">Max file size: 2MB. Recommended: 300x300px</small>
                    </div>
                    
                    <div class="text-center mb-3">
                        <div id="avatarPreview"></div>
                    </div>
                    
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .profile-avatar {
        position: relative;
        width: 150px;
        height: 150px;
        margin: 0 auto;
    }
    
    .profile-avatar img,
    .avatar-placeholder {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border: 5px solid #f8f9fa;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    
    .avatar-placeholder {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        font-weight: bold;
    }
    
    .change-avatar {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: #0d6efd;
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    .change-avatar:hover {
        background: #0b5ed7;
        color: white;
    }
    
    #avatarPreview {
        width: 200px;
        height: 200px;
        margin: 0 auto;
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    
    #avatarPreview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Avatar preview
        $('#avatar').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#avatarPreview').html(`<img src="${e.target.result}" alt="Preview">`);
                }
                reader.readAsDataURL(file);
            } else {
                $('#avatarPreview').html('<span class="text-muted">Preview will appear here</span>');
            }
        });
        
        // Avatar form submission
        $('#avatarForm').submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#avatarModal').modal('hide');
                        location.reload();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON.errors;
                    if (errors && errors.avatar) {
                        toastr.error(errors.avatar[0]);
                    } else {
                        toastr.error('Something went wrong!');
                    }
                }
            });
        });
    });
</script>
@endpush