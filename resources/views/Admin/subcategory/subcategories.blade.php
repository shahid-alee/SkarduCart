@extends('admin.layout')
@section('admin-dashboard-subcategory')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body" style="width: max-content;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title">Categories Table</h4>
                            <a href="{{route('subcategory.create')}}"
                                class="btn btn-primary btn-rounded btn-fw">
                                Add New Sub Category
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Sub Category </th>
                                        <th>Category </th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($subcategories as $subcategory)
                                    <tr>
                                        <td>{{ $subcategory->id }}</td>
                                        
                                        <td>{{ $subcategory->sub_category_name }}</td>
                                        <td>{{ $subcategory->category->category_name ?? 'N/A' }}</td>
                                        
                                        <td>{{ $subcategory->description }}</td>
                                        <td>
                                            <a href="{{ route('subcategory.edit', $subcategory->id) }}">
                                                <button type="button" class="btn btn-info btn-rounded btn-fw">EDIT</button>
                                            </a>

                                            <form action="{{ route('subcategory.destroy', $subcategory->id) }}"
                                                method="POST"
                                                style="display:inline;"
                                                onsubmit="return confirm('Are you sure you want to delete this subcategory?')">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-danger btn-rounded btn-fw">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>


                        <div class="mt-4 d-flex justify-content-end">
                            {{ $subcategories->links('pagination::bootstrap-4') }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection