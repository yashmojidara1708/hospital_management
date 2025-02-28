<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#role" data-whatever="@mdo">Add Role</button>

<div class="modal fade" id="role" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Role</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" name="roleform" id="roleform">
            @csrf
          <div class="form-group">
            <label for="name" class="col-form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name">
          </div>
          <div class="form-group">
            <label for="name" class="col-form-label">Status</label>
           
                <select class="form-control" name="status" id="status">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
        </div>
        </form>
      </div>
      <div class="modal-footer">
        
        <button type="button" class="btn btn-primary" name="saverole" id="saverole">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>