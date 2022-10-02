<template>
  <div class="mb-1 mt-1 text-center flex items-center justify-center">
    <img
      src="~/static/ventamo-logo.png"
      width="270"
      height="40"
      class="lg:hidden"
    />
    <h1
      class="hidden lg:block text-4xl font-bold tracking-tight text-ventamo-indigo-2"
    >
      {{ title }}
    </h1>
  </div>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      default: '',
    },
  },
}
</script>
