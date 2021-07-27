<?php

namespace App\Http\Livewire\Item;

use Livewire\Component;
use App\Models\Item;
use Livewire\withPagination;

class ItemIndex extends Component
{
    use withPagination;

    public $search = '';
    public $name, $notes, $itemId;
    public $editMode = false;

    protected $rules = [
        'name' => 'required',
        'notes' => 'min:10',
    ];

    public function storeItem()
    {
        $this->validate();
        Item::create([
            'name' => $this->name,
            'notes' => $this->notes,
        ]);
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#itemModal', 'actionModal' => 'hide']);
        session()->flash('item-message', 'Item successfully Created.');
    }

    public function showItemModal()
    {
        $this->reset();
        // show the modal
        $this->dispatchBrowserEvent('modal', ['modalId' => '#itemModal', 'actionModal' => 'show']);
    }

    public function showEditModal($id)
    {
        $this->reset();
        $this->editMode = true;
        // find the item
        $this->itemId = $id;
        // load item
        $this->loadItem();
        // show the modal
        $this->dispatchBrowserEvent('modal', ['modalId' => '#itemModal', 'actionModal' => 'show']);
    }

    public function loadItem()
    {
        $item = Item::find($this->itemId);
        $this->name = $item->name;
        $this->notes = $item->notes;
    }

    public function updateItem()
    {
        $validated = $this->validate([
            'name' => 'required',
            'notes' => 'min:10',
        ]);
        $item = Item::find($this->itemId);
        $item->update($validated);
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#itemModal', 'actionModal' => 'hide']);
        session()->flash('item-message', 'Item successfully Updated.');
    }

    public function deleteItem($id)
    {
        $item = Item::find($id);
        $item->delete();
        $this->reset();
        session()->flash('item-message', 'Item successfully Deleted.');
    }

    public function closeModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#itemModal', 'actionModal' => 'hide']);
    }

    public function render()
    {
        $items = Item::paginate(5);
        if (strlen($this->search) > 2) {
            $items = Item::where('name', 'like', "%{$this->search}%")->paginate(5);
        }

        return view('livewire.item.item-index', [
            'items' => $items
        ])->layout('layouts.main');
    }
}
