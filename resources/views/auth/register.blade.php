<x-layout :title="'Registro'">
    <x-page-heading>Registro administrador</x-page-heading>

    <div class=" flex items-center justify-center ">
        <form method="POST" action="/register" enctype="multipart/form-data" class="w-full max-w-md bg-white p-8 rounded-lg shadow-md mt-14">
            @csrf
            <div class="space-y-2 row" >

                <x-forms.field class="col-12">
                    <x-forms.label for="name">Name</x-forms.label>
                        <x-forms.input name="name" id="name" :value="old('name')" required />
                        <x-forms.error name="name" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <x-forms.label for="email">Email</x-forms.label>
                        <x-forms.input name="email" id="email" type="email" :value="old('email')" required />
                        <x-forms.error name="email" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <x-forms.label for="password">Password</x-forms.label>
                        <x-forms.input name="password" id="password" type="password" required />
                        <x-forms.error name="password" />
                </x-forms.field>

                <x-forms.field class="col-12">
                    <x-forms.label for="password_confirmation">Password Confirmation</x-forms.label>
                        <x-forms.input name="password_confirmation" id="password_confirmation" type="password" required />
                        <x-forms.error name="password_confirmation" />
                </x-forms.field>


            </div>
            <x-forms.button>Create Account</x-forms.button>
        </form>
    </div>
</x-layout>
