<template>
  <nav class="bg-white border-gray-200 px-2 sm:px-4 py-2.5 border">
    <div class="container flex flex-wrap justify-between items-center mx-auto">
      <a href="https://flowbite.com/" class="flex items-center">
        <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">
          <router-link :to="{ name: user ? 'home' : 'welcome' }" class="navbar-brand">
            {{ appName }}
          </router-link>
        </span>
      </a>
      <div v-if="user" class="flex items-center md:order-2">
        <div class="relative inline-block text-left">
          <div>
            <button
              id="user-menu-button"
              type="button"
              class="flex mr-3 text-sm bg-gray-800 rounded-full md:mr-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
              aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom" @click="showDropdown = !showDropdown"
            >
              <span class="sr-only">Open user menu</span>
              <img class="w-8 h-8 rounded-full" :src="user.photo_url" alt="user photo">
            </button>
          </div>

          <div
            v-if="showDropdown"
            class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
            role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1"
          >
            <div class="py-1" role="none">
              <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
              <a id="menu-item-0" href="#" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">
                <router-link :to="{ name: 'settings.profile' }" class="nav-link">
                  Account Settings
                </router-link>
              </a>
              <a href="#" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" @click.prevent="logout">
                Sign Out
              </a>
            </div>
          </div>
        </div>
      </div>
      <template v-else>
        <div id="navbar-default" class="hidden w-full md:block md:w-auto">
          <ul class="flex flex-col p-4 mt-4 bg-gray-50 rounded-lg border border-gray-100 md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium md:border-0 md:bg-white">
            <li>
              <a href="#" class="block py-2 pr-4 pl-3 text-gray-700 bg-blue-700 rounded md:bg-transparent md:p-0" :class="{'text-blue-700': routePath === 'login'}">
                <router-link :to="{ name: 'login' }" class="nav-link" active-class="active">
                  Log In
                </router-link>
              </a>
            </li>
            <li>
              <a href="#" class="block py-2 pr-4 pl-3 text-gray-700 bg-blue-700 rounded md:bg-transparent md:p-0" :class="{'text-blue-700': routePath === 'register'}">
                <router-link :to="{ name: 'register' }" class="nav-link" active-class="active">
                  Register
                </router-link>
              </a>
            </li>
          </ul>
        </div>
      </template>
    </div>
  </nav>
</template>
<script>
import { mapGetters } from 'vuex'

export default {
  data () {
    return {
      appName: window.config.appName,
      showDropdown: false
    }
  },

  computed: {
    ...mapGetters('auth', ['user']),
    routePath() {
      return this.$route.name
    }
  },

  methods: {
    async logout () {
      // Log out the user.
      await this.$store.dispatch('auth/logout')

      this.showDropdown = false
      // Redirect to login.
      this.$router.push({ name: 'login' })
    }
  }
}
</script>
