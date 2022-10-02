<template>
  <div class="flex gap-2 sm:gap-3 items-center justify-center">
    <form-input
      v-for="index in 6"
      :key="index"
      v-mask="'#'"
      class="p-3 text-center font-semibold text-xl pin-input"
      type="text"
      maxlength="1"
      style="max-width: 3em; caret-color: transparent"
      @keyup="setToNextInput"
      @focus="setFocus(index)"
    />
  </div>
</template>

<script>
import FormInput from './FormInput'
export default {
  components: { FormInput },
  data() {
    return {
      currentFocus: 0,
      currentKeyCode: null,
    }
  },
  methods: {
    setFocus(index) {
      this.currentFocus = index
    },
    setToNextInput(e) {
      const inputs = [...document.getElementsByClassName('pin-input')]
      let emittedValue = ''
      if (e.keyCode === 8) {
        if (this.currentFocus > 1) {
          inputs.at(this.currentFocus - 1).value = ''
          inputs.at(this.currentFocus - 2).focus()
        } else {
          inputs.at(this.currentFocus - 1).value = ''
        }
      } else if (e.keyCode >= 48 && e.keyCode <= 57) {
        if (this.currentFocus < inputs.length) {
          inputs.at(this.currentFocus - 1).value = e.key
          inputs.at(this.currentFocus).focus()
        } else {
          inputs.at(this.currentFocus - 1).value = e.key
        }
      } else {
        e.preventDefault()
      }
      inputs.forEach((e) => {
        emittedValue += e.value ?? ''
      })
      this.$emit('input', emittedValue)
    },
  },
}
</script>
