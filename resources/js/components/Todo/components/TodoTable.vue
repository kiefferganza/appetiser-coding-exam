<template>
  <common-table
    class="animate__animated animate__fadeIn"
    searchable
    search-placeholder="Search Task.."
    :data="tableData"
    empty-state-title="No Tasks Available"
    empty-state-description="There are no tasks available as of the moment."
    :fetching="false"
    pagination
    :pagination-length="todos.PaginationLength"
    :pagination-limit="10"
    :pagination-page="todos.page"
    @table-paginate="changePage"
  >
    <template #widget>
      <form-button
        class="button button__primary flex items-center justify-center md:justify-start"
        @click="showModal"
      >
        Create Todo
      </form-button>
    </template>
    <template #action-buttons-2="{ title }">
      <span>{{ title }}</span>
    </template>
    <template #table-data-1="{ data }">
      <common-badge
        :title="data"
        :variant="data === 'Pending' ? 'warning' : 'success'"
      />
    </template>
  </common-table>
</template>
<script>
import CommonTable from '~/components/Common/Table'
import FormButton from '~/components/Common/Inputs/FormButton'
import CommonBadge from '~/components/Common/Badge'
import { mapActions, mapGetters, mapMutations, mapState } from 'vuex'

export default {
  components: {
    CommonTable,
    CommonBadge,
    FormButton
  },
  computed: {
    ...mapState('todos', ['todos']),
    ...mapGetters('todos', ['formattedTodoList']),
    tableData () {
      return {
        columns: ['Title', 'Status', 'Created at'],
        rows: this.formattedTodoList
          ? this.formattedTodoList.map((e) => {
            return {
              id: e.id,
              rowValues: [
                e.title,
                e.date_completed ? 'Completed' : 'Pending',
                e.date_created
              ]
            }
          })
          : [],
        callbacks: [
          {
            callback: (id) => this.$emit('show-todos-modal', id),
            title: 'Update Task',
            icon: 'edit',
            visible: true
          }
        ]
      }
    }
  },
  methods: {
    ...mapActions('todos', ['fetchTodos']),
    ...mapMutations('todos', ['setTodoCurrentPage']),
    changePage (page) {
      this.setTodoCurrentPage(page)
      this.fetchTodos()
    },
    showModal () {
      this.$emit('show-todos-modal')
    }
  }
}
</script>
