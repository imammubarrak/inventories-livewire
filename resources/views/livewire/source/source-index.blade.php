<div>
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Sources</h1>
    </div>
    <div class="row">
        <div class="card mx-auto">
            <div>
                @if (session()->has('source-message'))
                    <div class="alert alert-success">
                        {{ session('source-message') }}
                    </div>
                @endif
            </div>
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <form>
                            <div class="form-row align-items-center">
                                <div class="col">
                                    <input type="search" wire:model="search" class="form-control mb-2" id="inlineFormInput" placeholder="Jane Doe">
                                </div>
                                <div class="col" wire:loading>
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div>
                        <!-- Button trigger modal -->
                        <button wire:click="showSourceModal" class="btn btn-primary">
                            New Source
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table" wire:loading.remove>
                    <thead>
                      <tr>
                        <th scope="col">#Id</th>
                        <th scope="col">Name</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Notes</th>
                        <th scope="col">Manage</th>
                      </tr>
                    </thead>
                    <tbody>
                        @forelse ($sources as $source)
                        <tr>
                            <th scope="row">{{ $source->id }}</th>
                            <td>{{ $source->name }}</td>
                            <td>{{ $source->phone }}</td>
                            <td>{{ $source->notes }}</td>
                            <td>
                                <button wire:click="showEditModal({{ $source->id }})" class="btn btn-success">Edit</button>
                                <button wire:click="deleteSource({{ $source->id }})" class="btn btn-danger">Delete</button>
                            </td>
                          </tr>
                          @empty
                          <tr>
                              <th>No Results</th>
                          </tr>
                        @endforelse
                    </tbody>
                  </table>
            </div>
            <div>
                {{ $sources->links('pagination::bootstrap-4') }}
            </div>
        </div>
            <!-- Modal -->
            <div class="modal fade" id="sourceModal" tabindex="-1" aria-labelledby="sourceModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    @if ($editMode)
                        <h5 class="modal-title" id="sourceModalLabel">Update Source</h5>
                    @else
                        <h5 class="modal-title" id="sourceModalLabel">Create Source</h5>
                    @endif
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Source Name') }}</label>
    
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" wire:model.defer="name">
    
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone Number') }}</label>
    
                                <div class="col-md-6">
                                    <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" wire:model.defer="phone">
    
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="notes" class="col-md-4 col-form-label text-md-right">{{ __('Notes') }}</label>
    
                                <div class="col-md-6">
                                    <input id="notes" type="text" class="form-control @error('notes') is-invalid @enderror" wire:model.defer="notes">
    
                                    @error('notes')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">Close</button>
                    @if ($editMode)
                        <button type="button" class="btn btn-primary" wire:click="updateSource">Update Source</button>
                    @else
                        <button type="button" class="btn btn-primary" wire:click="storeSource">Store Source</button>  
                    @endif
                    </div>
                </div>
                </div>
            </div>    
    </div>
</div>