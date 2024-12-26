<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Room Management</h2>
        </div>
        <div class="col-md-6 text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                Add New Room
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Room Number</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="roomsTable">
                        <?php foreach ($rooms as $room): ?>
                            <tr id="room-<?= $room['id'] ?>">
                                <td><?= esc($room['room_number']) ?></td>
                                <td><?= esc($room['room_type']) ?></td>
                                <td><?= number_format($room['price'], 2) ?></td>
                                <td>
                                    <span class="badge bg-<?= $room['status'] === 'available' ? 'success' : ($room['status'] === 'occupied' ? 'warning' : 'danger') ?>">
                                        <?= ucfirst($room['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-room" data-id="<?= $room['id'] ?>">Edit</button>
                                    <button class="btn btn-sm btn-danger delete-room" data-id="<?= $room['id'] ?>">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Room Modal -->
<div class="modal fade" id="addRoomModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addRoomForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="room_number" class="form-label">Room Number</label>
                        <input type="text" class="form-control" id="room_number" name="room_number" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="room_type" class="form-label">Room Type</label>
                        <input type="text" class="form-control" id="room_type" name="room_type" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="available">Available</option>
                            <option value="occupied">Occupied</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Room</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Room Modal -->
<div class="modal fade" id="editRoomModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editRoomForm">
                <input type="hidden" id="edit_room_id" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_room_number" class="form-label">Room Number</label>
                        <input type="text" class="form-control" id="edit_room_number" name="room_number" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_room_type" class="form-label">Room Type</label>
                        <input type="text" class="form-control" id="edit_room_type" name="room_type" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="edit_price" name="price" step="0.01" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status" name="status" required>
                            <option value="available">Available</option>
                            <option value="occupied">Occupied</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Room</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Add Room Form Submit
    $('#addRoomForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '<?= base_url('rooms/create') ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    location.reload();
                } else {
                    if (response.errors) {
                        $.each(response.errors, function(field, error) {
                            $('#' + field).addClass('is-invalid')
                                .siblings('.invalid-feedback')
                                .text(error);
                        });
                    }
                }
            }
        });
    });

    // Edit Room Button Click
    $('.edit-room').on('click', function() {
        const id = $(this).data('id');
        const row = $(`#room-${id}`);
        
        $('#edit_room_id').val(id);
        $('#edit_room_number').val(row.find('td:eq(0)').text());
        $('#edit_room_type').val(row.find('td:eq(1)').text());
        $('#edit_price').val(parseFloat(row.find('td:eq(2)').text().replace(',', '')));
        $('#edit_status').val(row.find('td:eq(3)').text().toLowerCase().trim());
        
        $('#editRoomModal').modal('show');
    });

    // Edit Room Form Submit
    $('#editRoomForm').on('submit', function(e) {
        e.preventDefault();
        const id = $('#edit_room_id').val();
        
        $.ajax({
            url: `<?= base_url('rooms') ?>/${id}`,
            type: 'PUT',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    location.reload();
                } else {
                    if (response.errors) {
                        $.each(response.errors, function(field, error) {
                            $(`#edit_${field}`).addClass('is-invalid')
                                .siblings('.invalid-feedback')
                                .text(error);
                        });
                    }
                }
            }
        });
    });

    // Delete Room Button Click
    $('.delete-room').on('click', function() {
        if (confirm('Are you sure you want to delete this room?')) {
            const id = $(this).data('id');
            
            $.ajax({
                url: `<?= base_url('rooms') ?>/${id}`,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        $(`#room-${id}`).remove();
                    } else {
                        alert('Failed to delete room');
                    }
                }
            });
        }
    });

    // Clear validation errors when modal is hidden
    $('.modal').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
        $(this).find('.is-invalid').removeClass('is-invalid');
        $(this).find('.invalid-feedback').text('');
    });
});
</script>
<?= $this->endSection() ?>
