<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\withPagination;

class UserIndex extends Component
{
    use withPagination;

    public $search = '';
    public $username, $firstname, $lastname, $email, $password, $userId, $role;
    public $editMode = false;

    protected $rules = [
        'username' => 'required',
        'lastname' => 'required',
        'firstname' => 'required',
        'role' => 'required',
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function storeUser()
    {
        $this->validate();
        User::create([
            'username' => $this->username,
            'lastname' => $this->lastname,
            'firstname' => $this->firstname,
            'role' => $this->role,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#userModal', 'actionModal' => 'hide']);
        session()->flash('user-message', 'User successfully Created.');
    }

    public function showUserModal()
    {
        $this->reset();
        // show the modal
        $this->dispatchBrowserEvent('modal', ['modalId' => '#userModal', 'actionModal' => 'show']);
    }

    public function showEditModal($id)
    {
        $this->reset();
        $this->editMode = true;
        // find the user
        $this->userId = $id;
        // load user
        $this->loadUser();
        // show the modal
        $this->dispatchBrowserEvent('modal', ['modalId' => '#userModal', 'actionModal' => 'show']);
    }

    public function loadUser()
    {
        $user = User::find($this->userId);
        $this->username = $user->username;
        $this->lastname = $user->lastname;
        $this->role = $user->role;
        $this->firstname = $user->firstname;
        $this->email = $user->email;
    }

    public function updateUser()
    {
        $validated = $this->validate([
            'username' => 'required',
            'lastname' => 'required',
            'firstname' => 'required',
            'role' => 'required',
            'email' => 'required|email',
        ]);
        $user = User::find($this->userId);
        $user->update($validated);
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#userModal', 'actionModal' => 'hide']);
        session()->flash('user-message', 'User successfully Updated.');
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if (auth()->user()->id == $user->id) {
            $this->reset();
            $this->dispatchBrowserEvent('modal', ['modalId' => '#userModal', 'actionModal' => 'hide']);
            session()->flash('user-message', 'You are deleting yourself.');
        }
        else
        $user->delete();
        $this->reset();
        session()->flash('user-message', 'User successfully Deleted.');
    }

    public function closeModal()
    {
        $this->reset();
        $this->dispatchBrowserEvent('modal', ['modalId' => '#userModal', 'actionModal' => 'hide']);
    }

    public function render()
    {
        $users = User::paginate(5);
        if (strlen($this->search) > 2) {
            $users = User::where('username', 'like', "%{$this->search}%")->paginate(5);
        }

        return view('livewire.user.user-index',[
            'users' => $users
        ])->layout('layouts.main');
    }
}
