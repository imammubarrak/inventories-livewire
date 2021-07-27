<?php

namespace App\Http\Livewire\Inventory;

use Livewire\Component;
use App\Models\Inventory;
use Livewire\withPagination;


class InventoryIndex extends Component
{
    use withPagination;

    public $search = '';
    public $user_id, $item_id, $source_id, $tag_number, $notes, $assign_date, $return_date, $inventoryId;
    public $editMode = false;
    public $selectedUserId = null;

    protected $rules = [
        'user_id' => 'required',
        'item_id' => 'required',
        'source_id' => 'required',
        'tag_number' => 'required',
        'assign_date' => 'required',
        'notes' => 'min:5',
    ];

    public function storeInventory()
    {
        $this->validate();
        Inventory::create([
            'user_id' => $this->user_id,
            'item_id' => $this->item_id,
            'source_id' => $this->source_id,
            'tag_number' => $this->tag_number,
            'assign_date' => $this->assign_date,
            'return_date' => $this->return_date,
            'notes' => $this->notes,
        ]);
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#inventoryModal', 'actionModal' => 'hide']);
        session()->flash('inventory-message', 'Inventory successfully Created.');
    }

    public function showInventoryModal()
    {
        $this->reset();
        // show the modal
        $this->dispatchBrowserEvent('modal', ['modalId' => '#inventoryModal', 'actionModal' => 'show']);
    }

    public function showEditModal($id)
    {
        $this->reset();
        $this->editMode = true;
        // find the inventory
        $this->inventoryId = $id;
        // load inventory
        $this->loadInventory();
        // show the modal
        $this->dispatchBrowserEvent('modal', ['modalId' => '#inventoryModal', 'actionModal' => 'show']);
    }

    public function loadInventory()
    {
        $inventory = Inventory::find($this->inventoryId);
        $this->user_id = $inventory->user_id;
        $this->item_id = $inventory->item_id;
        $this->source_id = $inventory->source_id;
        $this->tag_number = $inventory->tag_number;
        $this->assign_date = $inventory->assign_date;
        $this->return_date = $inventory->return_date;
        $this->notes = $inventory->notes;
    }

    public function updateInventory()
    {
        $validated = $this->validate([
            'user_id' => 'required',
            'item_id' => 'required',
            'source_id' => 'required',
            'tag_number' => 'required',
            'assign_date' => 'required',
            'return_date' => 'required',
            'notes' => 'min:5',
        ]);
        $inventory = Inventory::find($this->inventoryId);
        $inventory->update($validated);
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#inventoryModal', 'actionModal' => 'hide']);
        session()->flash('inventory-message', 'Inventory successfully Updated.');
    }

    public function deleteInventory($id)
    {
        $inventory = Inventory::find($id);
        $inventory->delete();
        $this->reset();
        session()->flash('inventory-message', 'Inventory successfully Deleted.');
    }

    public function closeModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#inventoryModal', 'actionModal' => 'hide']);
    }

    public function render()
    {
        $inventories = Inventory::paginate(5);
        if (strlen($this->search) > 2) {
            if($this->selectedUserId) {
                $inventories = Inventory::where('notes', 'like', "%{$this->search}%")
                ->where('user_id', $this->selectedUserId)
                ->paginate(5);
            } else {
                $inventories = Inventory::where('notes', 'like', "%{$this->search}%")->paginate(5);
            }   
            } elseif($this->selectedUserId) {
                $inventories = Inventory::where('user_id', $this->selectedUserId)->paginate(5);        }


        return view('livewire.inventory.inventory-index', [
            'inventories' => $inventories
        ])->layout('layouts.main');
    }
}
