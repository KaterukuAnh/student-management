<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Xóa tài khoản') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Sau khi tài khoản bị xóa, toàn bộ dữ liệu liên quan sẽ bị xóa vĩnh viễn. Trước khi xóa, hãy tải xuống mọi dữ liệu hoặc thông tin bạn muốn giữ lại.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Xóa tài khoản') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Bạn có chắc muốn xóa tài khoản này?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Sau khi tài khoản bị xóa, toàn bộ dữ liệu liên quan sẽ bị xóa vĩnh viễn. Vui lòng nhập mật khẩu để xác nhận bạn muốn xóa vĩnh viễn tài khoản này.') }}
            </p>

            <div class="field mt-6">
                <x-input-label for="password" value="{{ __('Mật khẩu') }}" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="{{ __('Mật khẩu') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Hủy') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Xóa tài khoản') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
