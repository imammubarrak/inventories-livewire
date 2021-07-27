<div>
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Inventories</h1>
    </div>
    <div class="row">
        <div class="card mx-auto">
            <div>
                @if (session()->has('inventory-message'))
                    <div class="alert alert-success">
                        {{ session('inventory-message') }}
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
                                <div class="col">
                                    <select wire:model="selectedUserId" class="form-control mb-2" aria-label="Default select example">
                                        <option selected>Open this select User</option>
                                        @foreach (App\Models\User::all()->where('role', 'Pengguna') as $user)
                                            <option value="{{ $user->id }}"> {{ $user->firstname }} {{ $user->lastname }}</option>
                                        @endforeach
                                    </select>
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
                        <button wire:click="showInventoryModal" class="btn btn-primary">
                            New Inventory
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table" wire:loading.remove>
                    <thead>
                      <tr>
                        <th scope="col">#Id</th>
                        <th scope="col">User Name</th>
                        <th scope="col">Item Name</th>
                        <th scope="col">Source</th>
                        <th scope="col">Tag Number</th>
                        <th scope="col">Assign Date</th>
                        <th scope="col">Manage</th>
                      </tr>
                    </thead>
                    <tbody>
                        @forelse ($inventories as $inventory)
                        <tr>
                            <th scope="row">{{ $inventory->id }}</th>
                            <td>{{ $inventory->user->firstname }} {{ $inventory->user->lastname }}</td>
                            <td>{{ $inventory->item->name }}</td>
                            <td>{{ $inventory->source->name }}</td>
                            <td>{{ $inventory->tag_number }}</td>
                            <td>{{ $inventory->assign_date }}</td>
                            <td>
                                <button wire:click="showEditModal({{ $inventory->id }})" class="btn btn-success">Edit</button>
                                <button wire:click="deleteInventory({{ $inventory->id }})" class="btn btn-danger">Delete</button>
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
                {{ $inventories->links('pagination::bootstrap-4')}}
            </div>
        </div>
            <!-- Modal -->
            <div class="modal fade" id="inventoryModal" tabindex="-1" aria-labelledby="inventoryModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        @if ($editMode)
                            <h5 class="modal-title" id="inventoryModalLabel">Update Inventory</h5>
                        @else
                            <h5 class="modal-title" id="inventoryModalLabel">Create Inventory</h5>
                        @endif
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        <form>

                            <div class="form-group row">
                                <label for="user_id" class="col-md-4 col-form-label text-md-right">{{ __('User Name') }}</label>
    
                                <div class="col-md-6">
                                    <select wire:model.defer="user_id" class="form-select" aria-label="Default select example">
                                        <option selected>Open this select User</option>
                                        @foreach (App\Models\User::all()->where('role', 'Pengguna') as $user)
                                            <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="item_id" class="col-md-4 col-form-label text-md-right">{{ __('Item Name') }}</label>
    
                                <div class="col-md-6">
                                    <select wire:model.defer="item_id" class="form-select" aria-label="Default select example">
                                        <option selected>Open this select Item</option>
                                        @foreach (App\Models\Item::all() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="source_id" class="col-md-4 col-form-label text-md-right">{{ __('Source Name') }}</label>
    
                                <div class="col-md-6">
                                    <select wire:model.defer="source_id" class="form-select" aria-label="Default select example">
                                        <option selected>Open this select Source</option>
                                        @foreach (App\Models\Source::all() as $source)
                                            <option value="{{ $source->id }}">{{ $source->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="tag_number" class="col-md-4 col-form-label text-md-right">{{ __('Tag Number') }}</label>
    
                                <div class="col-md-6">
                                    <input id="tag_number" type="text" class="form-control @error('tag_number') is-invalid @enderror" wire:model.defer="tag_number">
    
                                    @error('tag_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="assign_date" class="col-md-4 col-form-label text-md-right">{{ __('Assign Date') }}</label>
    
                                <div class="col-md-6">
                                    <input id="assign_date" type="text" class="form-control @error('assign_date') is-invalid @enderror" wire:model.defer="assign_date">
    
                                    @error('assign_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="return_date" class="col-md-4 col-form-label text-md-right">{{ __('Return Date') }}</label>
    
                                <div class="col-md-6">
                                    <input id="return_date" type="text" class="form-control @error('return_date') is-invalid @enderror" wire:model.defer="return_date">
    
                                    @error('return_date')
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
                        <button type="button" class="btn btn-primary" wire:click="updateInventory">Update Inventory</button>
                    @else
                        <button type="button" class="btn btn-primary" wire:click="storeInventory">Store Inventory</button>  
                    @endif
                    </div>
                </div>
                </div>
            </div>    
    </div>
</div>
