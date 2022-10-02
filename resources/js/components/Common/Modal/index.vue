<template>
  <!-- Large Modal hidden -->
  <div
    style="background-color: rgba(0, 0, 0, 0.6)"
    :class="{ hidden: !show }"
    class="flex overflow-x-hidden fixed inset-0 z-50 justify-center items-center md:inset-0 h-full"
  >
    <div
      :class="[
        { 'max-w-md': size === 'sm' },
        { 'max-w-lg': size === 'md' },
        { 'max-w-4xl': size === 'lg' },
        { 'max-w-6xl': size === 'xl' },
        { 'max-w-7xl': size === '2xl' },
      ]"
      class="animate__animated animate__slideInDown relative flex md:block items-center justify-center md:px-4 w-full h-full md:h-auto"
    >
      <!-- Modal content -->
      <div
        class="relative bg-white rounded-lg shadow dark:bg-gray-700 w-11/12 md:w-full"
      >
        <!-- Modal header -->
        <div
          class="flex justify-between items-center p-5 rounded-t dark:border-gray-600"
        >
          <h3
            class="text-xl font-medium text-ventamo-navy-blue dark:text-white"
          >
            {{ title }}
          </h3>
          <slot v-if="!title" name="header" />
          <modal-close-button @click="$emit('close')" />
        </div>
        <component
          :is="isForm ? 'form' : 'div'"
          @submit.prevent="$emit('submit')"
        >
          <div class="px-6 md:py-1">
            <slot />
          </div>
          <!-- Modal footer -->
          <div
            v-if="showButtonGroup"
            class="flex items-center justify-end p-6 space-x-2 rounded-b border-gray-200 dark:border-gray-600"
          >
            <form-button
              v-if="showButton"
              type="submit"
              class="button bg-ventamo-yellow-2"
              :busy="busy"
              :disabled="busy"
              @click="$emit('save')"
            >
              {{ saveTitle }}
            </form-button>
            <form-button
              class="button button__secondary"
              @click="$emit('close')"
            >
              Close
            </form-button>
          </div>
        </component>
      </div>
    </div>
  </div>
</template>

<script>
import ModalCloseButton from '~/components/Common/Modal/components/ModalCloseButton'
import FormButton from '~/components/Common/Inputs/FormButton'
export default {
  components: { ModalCloseButton, FormButton },
  props: {
    show: {
      type: Boolean,
      default: false,
    },
    showButtonGroup: {
      type: Boolean,
      default: true,
    },
    showButton: {
      type: Boolean,
      default: true,
    },
    isForm: {
      type: Boolean,
      default: false,
    },
    busy: {
      type: Boolean,
      default: false,
    },
    size: {
      type: String,
      default: 'md',
    },
    title: {
      type: String,
      default: '',
    },
    saveTitle: {
      type: String,
      default: 'Save',
    },
  },
}
</script>
