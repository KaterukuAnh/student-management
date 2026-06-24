@extends('layouts.app')

@section('title', __('Danh sách lớp học'))

@php
    $reopenCreate = $errors->any() && old('_modal') === 'create';
    $reopenEdit = $errors->any() && old('_modal') === 'edit';
@endphp

@section('content')
    <div
        x-data="{ showCreate: {{ $reopenCreate ? 'true' : 'false' }}, editing: null }"
        x-init="@if ($reopenEdit) editing = @js(['id' => old('id'), 'name' => old('name'), 'grade' => old('grade')]) @endif"
    >
        <x-page-head crumb="{{ __('Tổ chức').' · '.__('Lớp học') }}" title="{{ __('Danh sách lớp học') }}">
            <x-slot:actions>
                <button type="button" class="btn btn-primary" @click="showCreate = true"><span class="plus">+</span> {{ __('Thêm lớp mới') }}</button>
            </x-slot:actions>
        </x-page-head>

        <div class="panel">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>{{ __('Tên lớp') }}</th>
                        <th>{{ __('Khối') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($classrooms as $classroom)
                        <tr>
                            <td class="id">{{ $classroom->id }}</td>
                            <td class="cell-name"><span class="nm">{{ $classroom->name }}</span></td>
                            <td><span class="badge badge-soft">{{ __('Khối') }} {{ $classroom->grade }}</span></td>
                            <td class="rowact">
                                <button type="button" class="iconbtn" @click="editing = @js(['id' => $classroom->id, 'name' => $classroom->name, 'grade' => $classroom->grade])">{{ __('Sửa') }}</button>
                                <form action="{{ route('classrooms.destroy', $classroom->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('{{ __('Xóa lớp này?') }}')" class="iconbtn danger">{{ __('Xóa') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4"><div class="empty"><div class="big">{{ __('Chưa có lớp học nào') }}</div></div></td></tr>
                    @endforelse
                </tbody>
            </table>

            <x-pagination :paginator="$classrooms" />
        </div>

        <div class="overlay" x-show="showCreate" x-cloak @keydown.escape.window="showCreate = false" style="display:none">
            <div class="modal" @click.away="showCreate = false">
                <div class="modal-h">
                    <span class="ti">{{ __('Thêm lớp mới') }}</span>
                    <button type="button" class="x" @click="showCreate = false">✕</button>
                </div>

                <form method="POST" action="{{ route('classrooms.store') }}">
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
                                <label>{{ __('Tên lớp') }}</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="inp">
                            </div>

                            <div class="field full">
                                <label>{{ __('Khối') }}</label>
                                <select name="grade">
                                    <option value="">-- {{ __('Chọn khối') }} --</option>
                                    <option value="10" {{ old('grade') == '10' ? 'selected' : '' }}>{{ __('Khối') }} 10</option>
                                    <option value="11" {{ old('grade') == '11' ? 'selected' : '' }}>{{ __('Khối') }} 11</option>
                                    <option value="12" {{ old('grade') == '12' ? 'selected' : '' }}>{{ __('Khối') }} 12</option>
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
                    <span class="ti">{{ __('Sửa lớp học') }}</span>
                    <button type="button" class="x" @click="editing = null">✕</button>
                </div>

                <template x-if="editing">
                    <form method="POST" :action="'{{ route('classrooms.index') }}/' + editing.id">
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
                                    <label>{{ __('Tên lớp') }}</label>
                                    <input type="text" name="name" x-model="editing.name" class="inp">
                                </div>

                                <div class="field full">
                                    <label>{{ __('Khối') }}</label>
                                    <select name="grade" x-model="editing.grade">
                                        <option value="10">{{ __('Khối') }} 10</option>
                                        <option value="11">{{ __('Khối') }} 11</option>
                                        <option value="12">{{ __('Khối') }} 12</option>
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
