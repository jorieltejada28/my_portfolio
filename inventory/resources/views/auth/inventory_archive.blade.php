@extends('layouts.app')

@section('title', 'Inventory Archive')

@section('content')
    <div class="text-center">
        <h1>Inventory Archive</h1>
        <p>This is the dashboard of my application.</p>
    </div>

    <div class="container mt-4">
        <!-- Insert Button -->
        <div class="mb-3 text-end">
            <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#insertModal">Insert</button>
        </div>

        <!-- Inventory Table -->
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Item</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($inventories->filter(fn($inventory) => $inventory->is_archived)->reverse() as $inventory)
                    @php
                        if ($inventory->quantity == 0) {
                            $status = 'Out of Stock';
                        } elseif ($inventory->quantity <= $inventory->max_quantity * 0.2) {
                            $status = 'Running Low';
                        } else {
                            $status = 'In Stock';
                        }

                        $bgColor = match ($status) {
                            'In Stock' => 'bg-success text-white',
                            'Out of Stock' => 'bg-danger text-white',
                            'Running Low' => 'bg-warning text-dark',
                            default => '',
                        };
                    @endphp
                    <tr>
                        <td>{{ $inventory->id }}</td>
                        <td>{{ $inventory->item_name }}</td>
                        <td>{{ $inventory->category }}</td>
                        <td>{{ $inventory->quantity }}</td>
                        <td>{{ $inventory->price }}</td>
                        <td class="{{ $bgColor }}">{{ $status }}</td>
                        <td>
                            <form action="{{ url('/inventory/restore/' . $inventory->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('PUT')
                                <button type="button" class="btn btn-sm btn-primary restore-btn">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </form>
                            <form action="{{ url('/inventory/delete/' . $inventory->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger delete-btn">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No data found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function handleConfirmation(buttonClass, title, text, confirmButtonColor, confirmText) {
                document.querySelectorAll(buttonClass).forEach(function(button) {
                    button.addEventListener('click', function() {
                        const form = this.closest('form');

                        Swal.fire({
                            title: title,
                            text: text,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: confirmButtonColor,
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: confirmText
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
            }

            handleConfirmation('.restore-btn', 'Are you sure?', 'This item will be restored.', '#3085d6',
                'Yes, restore it!');
            handleConfirmation('.delete-btn', 'Are you sure?', 'This item will be permanently deleted.', '#d33',
                'Yes, delete it!');
        });
    </script>

@endsection
