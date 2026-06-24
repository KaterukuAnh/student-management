@extends('layouts.app')

@section('title', __('Danh sách học sinh'))

@php
    $reopenCreate = $errors->any() && old('_modal') === 'create';
    $reopenEdit = $errors->any() && old('_modal') === 'edit';
@endphp

@section('content')
    <div
        x-data="{ showCreate: {{ $reopenCreate ? 'true' : 'false' }}, editing: null }"
        x-init="@if ($reopenEdit) editing = @js(['id' => old('id'), 'name' => old('name'), 'birth_date' => old('birth_date'), 'gender' => old('gender'), 'email' => old('email'), 'classroom_id' => old('classroom_id')]) @endif"
    >
        <x-page-head crumb="{{ __('Hồ sơ').' · '.__('Học sinh') }}" title="{{ __('Danh sách học sinh') }}">
            <x-slot:actions>
                <button type="button" class="btn btn-primary" @click="showCreate = true"><span class="plus">+</span> {{ __('Thêm học sinh') }}</button>
            </x-slot:actions>
        </x-page-head>

        <div class="panel">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>{{ __('Họ tên') }}</th>
                        <th>{{ __('Ngày sinh') }}</th>
                        <th>{{ __('Giới tính') }}</th>
                        <th>Email</th>
                        <th>{{ __('Lớp') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        <tr>
                            <td class="id">{{ $student->id }}</td>
                            <td class="cell-name">
                                <x-avatar :name="$student->name" :size="34" />
                                <span class="nm">{{ $student->name }}</span>
                            </td>
                            <td>{{ $student->birth_date }}</td>
                            <td class="sex">{{ $student->genderLabel() }}</td>
                            <td>{{ $student->email }}</td>
                            <td><span class="badge badge-cls">{{ $student->classroom->name }}</span></td>
                            <td class="rowact">
                                <button type="button" class="iconbtn" @click="editing = @js(['id' => $student->id, 'name' => $student->name, 'birth_date' => $student->birth_date, 'gender' => $student->gender, 'email' => $student->email, 'classroom_id' => $student->classroom_id])">{{ __('Sửa') }}</button>
                                <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('{{ __('Xóa học sinh này?') }}')" class="iconbtn danger">{{ __('Xóa') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><div class="empty"><div class="big">{{ __('Chưa có học sinh nào') }}</div></div></td></tr>
                    @endforelse
                </tbody>
            </table>

            <x-pagination :paginator="$students" />
        </div>

        <div class="overlay" x-show="showCreate" x-cloak @keydown.escape.window="showCreate = false" style="display:none">
            <div class="modal" @click.away="showCreate = false">
                <div class="modal-h">
                    <span class="ti">{{ __('Thêm học sinh mới') }}</span>
                    <button type="button" class="x" @click="showCreate = false">✕</button>
                </div>

                <form method="POST" action="{{ route('students.store') }}">
                    @csrf
                    <input type="hidden" name="_modal" value="create">

                    <div class="modal-b">
                        @if ($reopenCreate)
                            <div class="login-error">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <div class="form-grid">
                            <div class="field full">
                                <label>{{ __('Họ tên') }}</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="inp">
                            </div>

                            <div class="field">
                                <label>{{ __('Ngày sinh') }}</label>
                                <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="inp">
                            </div>

                            <div class="field">
                                <label>{{ __('Giới tính') }}</label>
                                <select name="gender">
                                    <option value="">-- {{ __('Chọn') }} --</option>
                                    @foreach (\App\Models\Student::GENDERS as $code => $label)
                                        <option value="{{ $code }}" {{ old('gender') == $code ? 'selected' : '' }}>{{ __($label) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="field full">
                                <label>Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="inp">
                            </div>

                            <div class="field full">
                                <label>{{ __('Lớp') }}</label>
                                <select name="classroom_id">
                                    <option value="">-- {{ __('Chọn lớp') }} --</option>
                                    @foreach ($classrooms as $classroom)
                                        <option value="{{ $classroom->id }}" {{ old('classroom_id') == $classroom->id ? 'selected' : '' }}>
                                            {{ $classroom->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-f">
                        <button type="button" class="btn btn-ghost" @click="showCreate = false">{{ __('Hủy') }}</button>
                        <button type="submit" class="btn btn-accent">{{ __('Lưu') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="overlay" x-show="editing" x-cloak @keydown.escape.window="editing = null" style="display:none">
            <div class="modal" @click.away="editing = null" x-show="editing">
                <div class="modal-h">
                    <span class="ti">{{ __('Sửa thông tin học sinh') }}</span>
                    <button type="button" class="x" @click="editing = null">✕</button>
                </div>

                <template x-if="editing">
                    <form method="POST" :action="'{{ route('students.index') }}/' + editing.id">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="_modal" value="edit">
                        <input type="hidden" name="id" :value="editing.id">

                        <div class="modal-b">
                            @if ($reopenEdit)
                                <div class="login-error">
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="form-grid">
                                <div class="field full">
                                    <label>{{ __('Họ tên') }}</label>
                                    <input type="text" name="name" x-model="editing.name" class="inp">
                                </div>

                                <div class="field">
                                    <label>{{ __('Ngày sinh') }}</label>
                                    <input type="date" name="birth_date" x-model="editing.birth_date" class="inp">
                                </div>

                                <div class="field">
                                    <label>{{ __('Giới tính') }}</label>
                                    <select name="gender" x-model="editing.gender">
                                        @foreach (\App\Models\Student::GENDERS as $code => $label)
                                            <option value="{{ $code }}">{{ __($label) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="field full">
                                    <label>Email</label>
                                    <input type="email" name="email" x-model="editing.email" class="inp">
                                </div>

                                <div class="field full">
                                    <label>{{ __('Lớp') }}</label>
                                    <select name="classroom_id" x-model="editing.classroom_id">
                                        @foreach ($classrooms as $classroom)
                                            <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="modal-f">
                            <button type="button" class="btn btn-ghost" @click="editing = null">{{ __('Hủy') }}</button>
                            <button type="submit" class="btn btn-accent">{{ __('Cập nhật') }}</button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>
@endsection
