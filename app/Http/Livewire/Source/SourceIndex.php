<?php

namespace App\Http\Livewire\Source;

use Livewire\Component;
use App\Models\Source;
use Livewire\withPagination;

class SourceIndex extends Component
{
    use withPagination;

    public $search = '';
    public $name, $notes, $phone, $sourceId;
    public $editMode = false;

    protected $rules = [
        'name' => 'required',
        'phone' => 'required',
        'notes' => 'min:5',
    ];

    public function storeSource()
    {
        $this->validate();
        Source::create([
            'name' => $this->name,
            'phone' => $this->phone,
            'notes' => $this->notes,
        ]);
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#sourceModal', 'actionModal' => 'hide']);
        session()->flash('source-message', 'Source successfully Created.');
    }

    public function showSourceModal()
    {
        $this->reset();
        // show the modal
        $this->dispatchBrowserEvent('modal', ['modalId' => '#sourceModal', 'actionModal' => 'show']);
    }

    public function showEditModal($id)
    {
        $this->reset();
        $this->editMode = true;
        // find the item
        $this->sourceId = $id;
        // load item
        $this->loadSource();
        // show the modal
        $this->dispatchBrowserEvent('modal', ['modalId' => '#sourceModal', 'actionModal' => 'show']);
    }

    public function loadSource()
    {
        $source = Source::find($this->sourceId);
        $this->name = $source->name;
        $this->phone = $source->phone;
        $this->notes = $source->notes;
    }

    public function updateSource()
    {
        $validated = $this->validate([
            'name' => 'required',
            'phone' => 'required',
            'notes' => 'min:5',
        ]);
        $source = Source::find($this->sourceId);
        $source->update($validated);
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#sourceModal', 'actionModal' => 'hide']);
        session()->flash('source-message', 'Source successfully Updated.');
    }

    public function deleteSource($id)
    {
        $source = Source::find($id);
        $source->delete();
        $this->reset();
        session()->flash('source-message', 'Source successfully Deleted.');
    }

    public function closeModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#sourceModal', 'actionModal' => 'hide']);
    }

    public function render()
    {
        $sources = Source::paginate(5);
        if (strlen($this->search) > 2) {
            $sources = Source::where('name', 'like', "%{$this->search}%")->paginate(5);
        }

        return view('livewire.source.source-index', [
            'sources' => $sources
        ])->layout('layouts.main');
    }
}
