import Vue from 'vue'

Vue.directive('click-outside', {
  bind (el, binding, vnode) {
    const vm = vnode.context
    const callback = binding.value

    el.clickOutsideEvent = (event) => {
      if (!(el === event.target || el.contains(event.target))) {
        return callback.call(vm, event)
      }
    }
    document.body.addEventListener('click', el.clickOutsideEvent)
  },
  unbind (el) {
    document.body.removeEventListener('click', el.clickOutsideEvent)
  }
})
