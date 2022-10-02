<template>
  <div class="flex flex-col h-screen my-auto items-center">
    <auth-card title="Login">
      <form class="space-y-6" @submit.prevent="login" @keydown="form.onKeydown($event)">
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
        <div class="flex items-start mb-6">
          <div class="flex items-center h-5">
            <checkbox v-model="remember" name="remember">
              {{ $t('remember_me') }}
            </checkbox>
          </div>
        </div>
        <v-button :loading="form.busy" class="w-full">
          Login to your account
        </v-button>
      </form>
    </auth-card>
  </div>
</template>

<script>
import Form from 'vform'
import Cookies from 'js-cookie'
import AuthCard from '../../components/AuthCard'

export default {
  components: {
    AuthCard
  },
  middleware: 'guest',

  metaInfo () {
    return { title: this.$t('login') }
  },

  data: () => ({
    form: new Form({
      email: '',
      password: ''
    }),
    remember: false
  }),

  methods: {
    async login () {
      // Submit the form.
      const { data } = await this.form.post('/api/login')

      // Save the token.
      this.$store.dispatch('auth/saveToken', {
        token: data.token,
        remember: this.remember
      })

      // Fetch the user.
      await this.$store.dispatch('auth/fetchUser')

      // Redirect home.
      this.redirect()
    },

    redirect () {
      const intendedUrl = Cookies.get('intended_url')

      if (intendedUrl) {
        Cookies.remove('intended_url')
        this.$router.push({ path: intendedUrl })
      } else {
        this.$router.push({ name: 'home' })
      }
    }
  }
}
</script>
