@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">

            <h4 class="card-title mb-4">Radius Settings</h4>

            <div class="table-responsive">
                <table class="table no-wrap v-middle mb-0 dashboard-table">
                    <thead>
                        <tr>
                            <th>Sr.</th>
                            <th>Key</th>
                            <th>Value</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($radiusSettings as $index => $setting)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $setting->key }} </td>
                                <td>{{ $setting->value }} Km</td>
                                <td>
                                    <button 
                                        class="btn btn-sm btn-primary"
                                        onclick="openEditModal('{{ $setting->id }}', '{{ $setting->value }}')">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    No Radius Settings Found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

{{-- EDIT MODAL --}}
<div class="modal fade" id="editRadiusModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="editRadiusForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Radius</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Radius Value (KM)</label>
                        <input type="number" name="value" id="radiusValue"
                               class="form-control" min="1" max="100" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        Save
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- JS --}}
<script>
function openEditModal(id, value) {
    document.getElementById('editRadiusForm').action =
        "{{ url('/admin/radius') }}/" + id;

    document.getElementById('radiusValue').value = value;
    $('#editRadiusModal').modal('show');
}
</script>

@endsection
