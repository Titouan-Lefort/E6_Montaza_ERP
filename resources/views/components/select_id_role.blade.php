@props(['entites', 'selected', 'user', 'id', 'name', 'class', 'onchange', 'placeholder'])
{{-- @var \Illuminate\Database\Eloquent\Collection $entites --}}
{{-- @var string|null $selected --}}
{{-- @var \App\Models\User|null $user --}}
{{-- @var string|null $class --}}
{{-- @var string|null $onchange --}}
{{-- @var string|null $placeholder --}}
<select id="{{ isset($id) ? $id : 'role_id' }}" name="{{ isset($name) ? $name : 'role_id' }}"
    class="block w-full {{ isset($class) ? $class : 'select' }}" required title="Role"
    @isset($onchange) onchange="{{ $onchange }}" @endisset>
    @isset($placeholder)
        <option value="" disabled selected>{{ $placeholder }}</option>
    @endisset
    @foreach ($entites as $entite)
        <optgroup label="{{ $entite->name }}">
            @foreach ($entite->roles as $role)
                <option value="{{ $role->id }}"
                    @if (isset($selected)) {{ $selected == $role->id ? 'selected' : '' }}
                        @elseif (isset($user))
                            {{ old('role_id') == $role->id ? 'selected' : ($user->role_id == $role->id ? 'selected' : '') }}
                        @else
                            {{ old('role_id') == $role->id ? 'selected' : '' }} @endif>
                    {{ $role->name }} {{ $role->trashed() ? ' (désactivé)' : '' }}
                </option>
            @endforeach
        </optgroup>
    @endforeach
</select>
