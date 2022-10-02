<template>
  <common-wrapper>
    <template #content>
      <Todo />
    </template>
  </common-wrapper>
</template>

<script>
import { mapActions } from 'vuex'
import CommonWrapper from '../components/Common/Wrapper'
import Todo from '~/components/Todo'
export default {
  components: {
    CommonWrapper,
    Todo
  },
  middleware: 'auth',
  mounted () {
    this.fetchTodos()
  },
  methods: {
    ...mapActions('todos', ['fetchTodos']),

    showModal () {
      this.$emit('show-user-modal')
    }
  },
  metaInfo () {
    return { title: this.$t('home') }
  }
}
</script>
