<template>
  <div class="antialiased bg-gray-100">
    <div class="flex flex-col flex-1">
      <navbar />
      <main class="flex-1">
        <div class="md:py-5 py-4">
          <child />
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import Navbar from '~/components/Navbar'

export default {
  name: 'MainLayout',

  components: {
    Navbar
  }
}
</script>
