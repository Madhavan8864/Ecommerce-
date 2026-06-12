@extends('user.layouts.app')

@section('title', 'Support Center')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-question-circle fa-3x text-primary mb-3"></i>
                    <h5>FAQ</h5>
                    <p class="text-muted">Find answers to common questions</p>
                    <a href="{{ route('faq') }}" class="btn btn-outline-primary btn-sm">View FAQs</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-phone-alt fa-3x text-success mb-3"></i>
                    <h5>Call Us</h5>
                    <p class="text-muted">Mon-Fri, 9am-6pm</p>
                    <h6 class="text-primary">+91 98765 43210</h6>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-envelope fa-3x text-info mb-3"></i>
                    <h5>Email Us</h5>
                    <p class="text-muted">24/7 Support</p>
                    <h6 class="text-primary">support@ecart.com</h6>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Send a Message</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('user.support.send') }}">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label">Subject</label>
                    <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" required>
                    @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Message</label>
                    <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror" required></textarea>
                    @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
        </div>
    </div>
</div>
@endsection