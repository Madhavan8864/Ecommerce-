@extends('admin.layouts.app')

@section('title', 'Brands')
@section('page-title', 'Brands')

@section('breadcrumbs')
<li class="breadcrumb-item active">Brands</li>
@endsection

@section('page-actions')
<a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-2"></i> Add Brand
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Logo</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Products</th>
                                <th>Est. Year</th>
                                <th>Country</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($brands as $brand)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <img src="{{ $brand->logo_url }}" 
                                         alt="{{ $brand->name }}" 
                                         class="img-thumbnail" 
                                         width="50" 
                                         height="50">
                                </td>
                                <td>{{ $brand->name }}</td>
                                <td>{{ $brand->slug }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $brand->products_count ?? 0 }}</span>
                                </td>
                                <td>{{ $brand->established_year ?? '-' }}</td>
                                <td>{{ $brand->country_of_origin ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $brand->status == 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($brand->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.brands.edit', $brand->id) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger delete-brand" 
                                                data-id="{{ $brand->id }}"
                                                data-name="{{ $brand->name }}"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Delete brand
        $('.delete-brand').click(function() {
            var brandId = $(this).data('id');
            var brandName = $(this).data('name');
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to delete brand: " + brandName,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url("admin/brands") }}/' + brandId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'Something went wrong!',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>
@endpush