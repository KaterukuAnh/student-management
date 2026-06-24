@extends('layouts.app')

@section('title', __('Danh sách môn học'))

@php
    $reopenCreate = $errors->any() && old('_modal') === 'create';
    $reopenEdit = $errors->any() && old('_modal') === 'edit';
@endphp

@section('content')
    <div
        x-data="{ showCreate: {{ $reopenCreate ? 'true' : 'false' }}, editing: null }"
        x-init="@if ($reopenEdit) editing = @js(['id' => old('id'), 'name' => old('name'), 'credits' => old('credits')]) @endif"
    >
        <x-page-head crumb="{{ __('Tổ chức').' · '.__('Môn học') }}" title="{{ __('Danh sách môn học') }}">
            <x-slot:actions>
                <button type="button" class="btn btn-primary" @click="showCreate = true"><span class="plus">+</span> {{ __('Thêm môn học') }}</button>
            </x-slot:actions>
        </x-page-head>

        <div class="panel">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>{{ __('Tên môn') }}</th>
                        <th>{{ __('Số tiết') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($subjects as $subject)
                        <tr>
                            <td class="id">{{ $subject->id }}</td>
                            <td class="cell-name"><span class="nm">{{ $subject->name }}</span></td>
                            <td>{{ $subject->credits }}</td>
                            <td class="rowact">
                                <button type="button" class="iconbtn" @click="editing = @js(['id' => $subject->id, 'name' => $subject->name, 'credits' => $subject->credits])">{{ __('Sửa') }}</button>
                                <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('{{ __('Xóa môn này?') }}')" class="iconbtn danger">{{ __('Xóa') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4"><div class="empty"><div class="big">{{ __('Chưa có môn học nào') }}</div></div></td></tr>
                    @endforelse
                </tbody>
            </table>

            <x-pagination :paginator="$subjects" />
        </div>

        <div class="overlay" x-show="showCreate" x-cloak @keydown.escape.window="showCreate = false" style="display:none">
            <div class="modal" @click.away="showCreate = false">
                <div class="modal-h">
                    <span class="ti">{{ __('Thêm môn học mới') }}</span>
                    <button type="button" class="x" @click="showCreate = false">✕</button>
                </div>

                <form method="POST" action="{{ route('subjects.store') }}">
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
                                <label>{{ __('Tên môn') }}</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="inp">
                            </div>

                            <div class="field full">
                                <label>{{ __('Số tiết') }}</label>
                                <input type="number" name="credits" value="{{ old('credits') }}" min="1" class="inp">
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
                    <span class="ti">{{ __('Sửa môn học') }}</span>
                    <button type="button" class="x" @click="editing = null">✕</button>
                </div>

                <template x-if="editing">
                    <form method="POST" :action="'{{ route('subjects.index') }}/' + editing.id">
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
                                    <label>{{ __('Tên môn') }}</label>
                                    <input type="text" name="name" x-model="editing.name" class="inp">
                                </div>

                                <div class="field full">
                                    <label>{{ __('Số tiết') }}</label>
                                    <input type="number" name="credits" x-model="editing.credits" min="1" class="inp">
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
