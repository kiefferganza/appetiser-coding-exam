<template>
  <div class="flex flex-col h-screen my-auto items-center">
    <auth-card title="Register">
      <form class="space-y-6" @submit.prevent="register" @keydown="form.onKeydown($event)">
        <div class="mb-6">
          <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Name</label>
          <input id="name"
                 v-model="form.name" type="text"
                 :class="{ 'border-red-500': form.errors.has('name') }"
                 class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="John Doe" required
          >
          <has-error :form="form" field="email" />
        </div>
        <div class="mb-6">
          <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Email</label>
          <input id="email"
                 v-model="form.email" type="email"
                 :class="{ 'border-red-500': form.errors.has('email') }"
                 class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="name@flowbite.com" required
          >
          <has-error :form="form" field="email" />
        </div>
        <div class="mb-6">
          <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Password</label>
          <input id="password"
                 v-model="form.password"
                 type="password"
                 :class="[{ 'border-red-500': form.errors.has('password') },
                          { 'bg-red-50': form.errors.has('password') }
                 ]"
                 class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required
          >
          <has-error :form="form" field="password" />
        </div>
        <div class="mb-6">
          <label for="password-confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Password</label>
          <input id="password-confirmation"
                 v-model="form.password_confirmation"
                 type="password"
                 :class="[{ 'border-red-500': form.errors.has('password_confirmation') },
                          { 'bg-red-50': form.errors.has('password_confirmation') }
                 ]"
                 class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required
          >
          <has-error :form="form" field="password" />
        </div>
        <v-button :loading="form.busy" class="w-full">
          Register
        </v-button>
      </form>
    </auth-card>
  </div>
</template>

<script>
import Form from 'vform'
import AuthCard from '../../components/AuthCard'

export default {
  components: {
    AuthCard
  },

  middleware: 'guest',

  metaInfo () {
    return { title: this.$t('register') }
  },

  data: () => ({
    form: new Form({
      name: '',
      email: '',
      password: '',
      password_confirmation: ''
    }),
    mustVerifyEmail: false
  }),

  methods: {
    async register () {
      const { data } = await this.form.post('/api/register')

      // Log in the user.
      const { data: { token } } = await this.form.post('/api/login')

      // Save the token.
      this.$store.dispatch('auth/saveToken', { token })

      // Update the user.
      await this.$store.dispatch('auth/updateUser', { user: data })

      // Redirect home.
      this.$router.push({ name: 'home' })
    }
  }
}
</script>
