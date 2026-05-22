<section>
    <header>
        <h2 class="h4">
            <i class="fi fi-rr-trash me-2"></i>{{ __('Delete Account') }}
        </h2>

        <p class="text-muted">
            <i class="fi fi-rr-warning me-1"></i>{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    ><i class="fi fi-rr-trash me-1"></i>{{ __('Delete Account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-3">
            @csrf
            @method('delete')

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fi fi-rr-warning me-2"></i>{{ __('Are you sure you want to delete your account?') }}
                </h5>
                <button type="button" class="btn-close" x-on:click="$dispatch('close')"></button>
            </div>

            <div class="modal-body">
                <p class="text-muted">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>

                <div class="mb-3">
                    <x-input-label for="password" value="{{ __('Password') }}" class="visually-hidden" />

                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        placeholder="{{ __('Password') }}"
                    />

                    <x-input-error :messages="$errors->userDeletion->get('password')" />
                </div>
            </div>

            <div class="modal-footer">
                <x-secondary-button x-on:click="$dispatch('close')">
                    <i class="fi fi-rr-cross me-1"></i>{{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button>
                    <i class="fi fi-rr-trash me-1"></i>{{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
