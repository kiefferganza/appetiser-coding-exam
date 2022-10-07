<template>
  <div>
    <div class="grid grid-cols-6 gap-12 pb-5">
      <div>
        <form-button
          class="button button__primary flex items-center justify-center md:justify-start mb-5"
          @click="showModal"
        >
          Create Todo
        </form-button>
      </div>
      <div class="col-start-5">
        <pagination :length="todos.paginationLength" :limit="10" :page="todos.page" @paginate="changePage" />
      </div>
    </div>
    <div v-if="todos.list.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
      <div v-for="(value, index) in formattedTodoList" :key="index"
           class="break-inside relative overflow-hidden flex flex-col justify-between space-y-3 text-sm rounded-xl max-w-[23rem] p-4 mb-4 bg-white text-black"
      >
        <div class="flex items-center justify-between font-medium">
          <span class="uppercase text-xs font-bold" :class="[{'text-blue-600': value.task_priority === 1},
                                                             {'text-teal-600': value.task_priority === 2},
                                                             {'text-orange-600': value.task_priority === 3},
                                                             {'text-red-600': value.task_priority === 4}]"
          >{{ taskPriority(value.task_priority) }}</span>
          <span class="text-xs text-slate-500">{{ value.date_created }}</span>
        </div>
        <div class="flex flex-row items-center space-x-3">
          <form-button class="flex flex-none items-center justify-center w-10 h-10 rounded-full text-white hover:bg-red-300" :class="[{'badge__success': value.date_completed},
                                                                                                                                      {'badge__warning': !value.date_completed}]"
                       :busy="todoCreate.fetch && hoverIndex === value.id" :disabled="todoCreate.fetch && hoverIndex === value.id" @mouseover="hoverElement(value.id)" @mouseleave="showIcon = false" @click="showModal(value.id, true)"
          >
            <fa-icon v-if="showIcon && hoverIndex === value.id" icon="trash" class="h-10 w-10" />
            <fa-icon v-else :icon="value.date_completed ? 'check' : 'exclamation-triangle'" class="h-10 w-10" />
          </form-button>
          <span class="text-base font-medium">{{ value.title }}</span>
        </div>
        <div> {{ value.description }}</div>
        <div class="flex justify-between items-center">
          <form-button class="flex items-center justify-center text-xs font-medium rounded-full px-4 py-1 space-x-1 border-2 border-black bg-white hover:bg-black hover:text-white text-black" @click="showModal(value.id)">
            <span>View</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M5 12h13M12 5l7 7-7 7" />
            </svg>
          </form-button>
          <form-button v-if="!value.date_completed"
                       :busy="todoCreate.fetch" :disabled="todoCreate.fetch"
                       class="ml-3 flex items-center justify-center text-xs font-medium rounded-full px-4 py-1 space-x-1 border-2 border-black bg-white hover:bg-black hover:text-white text-black" @click="updateTask(value.id, 'complete-task')"
          >
            <span>Complete Task</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <fa-icon icon="check" />
            </svg>
          </form-button>
        </div>
      </div>
    </div>
    <dashboard-empty-state v-else />
  </div>
</template>
<script>
import { mapActions, mapGetters, mapMutations, mapState } from 'vuex'
import FormButton from '~/components/Common/Inputs/FormButton'
import FaIcon from '~/components/Common/FaIcon'
import Pagination from '~/components/Common/Pagination'
import DashboardEmptyState from '~/components/DashboardEmptyState'
import { PRIORITY_OPTIONS } from '~/constants/priority-options'

export default {
  components: {
    FormButton,
    FaIcon,
    Pagination,
    DashboardEmptyState
  },
  data () {
    return {
      showIcon: false,
      hoverIndex: null
    }
  },
  computed: {
    ...mapState('todos', ['todos', 'todoCreate']),
    ...mapGetters('todos', ['formattedTodoList'])
  },
  methods: {
    ...mapActions('todos', ['fetchTodos']),
    ...mapMutations('todos', ['setTodoCurrentPage']),
    taskPriority (val) {
      return PRIORITY_OPTIONS.find(({ value }) => value === val).name
    },
    changePage (page) {
      this.setTodoCurrentPage(page)
      this.fetchTodos()
    },
    showModal (id = null, isReject = false) {
      this.$emit('show-todos-modal', { id: id, isReject: isReject })
    },
    updateTask (id = null, type) {
      this.$emit('update-task', {
        id: id,
        type: type
      })
    },
    hoverElement (id) {
      this.hoverIndex = id
      this.showIcon = !this.showIcon
    }
  }
}
</script>
