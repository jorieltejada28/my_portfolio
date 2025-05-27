@extends('layouts.app')

@section('title', 'Inventory')

@section('content')
    <div class="text-center">
        <h1>Inventory</h1>
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
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($inventories->filter(fn($inventory) => !$inventory->is_archived)->reverse() as $inventory)
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
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#viewModal"
                                data-item="{{ json_encode($inventory) }}">
                                <i class="bi bi-eye"></i></button>
                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#editModal"
                                data-item="{{ json_encode($inventory) }}">
                                <i class="bi bi-pencil"></i></button>
                            <form action="{{ url('/inventory/archive/' . $inventory->id) }}" method="POST"
                                style="display:inline;" class="archive-form">
                                @csrf
                                @method('PUT')
                                <button type="button" class="btn btn-sm btn-danger archive-btn">
                                    <i class="bi bi-trash"></i>
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

    <!-- Insert Modal -->
    <div class="modal fade" id="insertModal" tabindex="-1" aria-labelledby="insertModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"> <!-- vertical center -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Insert Inventory Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Center form horizontally, max width -->
                    <form class="mx-auto" action="{{ route('inventory_post') }}" method="POST" style="max-width: 600px;">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="itemName" class="form-label">Item Name</label>
                                <input type="text" class="form-control" name="itemName" id="itemName">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="brandName" class="form-label">Brand Name</label>
                                <input type="text" class="form-control" name="brandName" id="brandName">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="itemCategory" class="form-label">Category</label>
                                <input type="text" class="form-control" name="itemCategory" id="itemCategory">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="itemQuantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" name="itemQuantity" id="itemQuantity">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="itemPrice" class="form-label">Price</label>
                                <input type="text" class="form-control" name="itemPrice" id="itemPrice">
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- View Modal with paragraphs -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Inventory Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body mx-auto" style="max-width: 600px;">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Item Name</label>
                            <p id="viewItemName" class="form-control-plaintext"></p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Brand Name</label>
                            <p id="viewBrandName" class="form-control-plaintext"></p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Category</label>
                            <p id="viewItemCategory" class="form-control-plaintext"></p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Quantity</label>
                            <p id="viewItemQuantity" class="form-control-plaintext"></p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Price</label>
                            <p id="viewItemPrice" class="form-control-plaintext"></p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <p id="viewStatus" class="form-control-plaintext"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"> <!-- vertical center -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Inventory Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="mx-auto" method="POST" style="max-width: 600px;" id="editForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <input type="hidden" name="id" id="editItemId">
                            <div class="col-md-6 mb-3">
                                <label for="editItemName" class="form-label">Item Name</label>
                                <input type="text" class="form-control" id="editItemName" name="editItemName">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="editBrandName" class="form-label">Brand Name</label>
                                <input type="text" class="form-control" id="editBrandName" name="editBrandName">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="editItemCategory" class="form-label">Category</label>
                                <input type="text" class="form-control" id="editItemCategory"
                                    name="editItemCategory">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="editItemQuantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="editItemQuantity"
                                    name="editItemQuantity">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="editItemPrice" class="form-label">Price must ex. "100"</label>
                                <input type="text" class="form-control" id="editItemPrice" name="editItemPrice">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="saveEditBtn">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        var viewModal = document.getElementById('viewModal');

        viewModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var itemData = button.getAttribute('data-item');

            if (itemData) {
                var item = JSON.parse(itemData);

                document.getElementById('viewItemName').textContent = item.item_name || '';
                document.getElementById('viewBrandName').textContent = item.brand_name || '';
                document.getElementById('viewItemCategory').textContent = item.category || '';
                document.getElementById('viewItemQuantity').textContent = item.quantity || '';
                document.getElementById('viewItemPrice').textContent = item.price || '';
                document.getElementById('viewStatus').textContent = item.status || '';
            }
        });

        var editModal = document.getElementById('editModal');

        editModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var itemData = button.getAttribute('data-item');

            if (itemData) {
                var item = JSON.parse(itemData);

                document.getElementById('editItemId').value = item.id || '';
                document.getElementById('editItemName').value = item.item_name || '';
                document.getElementById('editBrandName').value = item.brand_name || '';
                document.getElementById('editItemCategory').value = item.category || '';
                document.getElementById('editItemQuantity').value = item.quantity || '';
                document.getElementById('editItemPrice').value = item.price || '';

                document.getElementById('editForm').action = '/inventory/update/' + item.id;
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.archive-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This item will be archived.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, archive it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>

@endsection
