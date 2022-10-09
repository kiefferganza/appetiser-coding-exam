<template>
  <div>
    <div class="grid grid-cols-1 grid-rows-3 lg:grid-rows-none lg:grid-cols-3 gap-4 pb-5">
      <div>
        <form-button
          class="button button__primary flex items-center justify-center md:justify-start mb-5"
          @click="showModal"
        >
          Create Todo
        </form-button>
      </div>
      <div v-if="todos.list.length > 0" class="grid grid-cols-2">
        <form-select
          v-model="selectedSortOption"
          track-by="name"
          label="name"
          placeholder="Sort By"
          :allow-empty="false"
          :options="sortingOptions"
          name="SortBy"
          @input="sortTodos()"
        />
      </div>
      <div v-if="todos.list.length > 0">
        <pagination :length="todos.paginationLength" :limit="10" :page="todos.page" @paginate="changePage" />
      </div>
    </div>
    <div class="grid grid-cols-1 pb-3">
      <div v-if="todos.list.length > 0">
        <search-input v-model="searchKey" placeholder="Search Title" @input="sortTodos" />
      </div>
    </div>
    <div v-if="todos.list.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 z-0">
      <div v-for="(value, index) in formattedTodoList" :key="index"
           class="break-inside overflow-hidden flex flex-col justify-between space-y-3 text-sm rounded-xl p-4 mb-4 bg-white text-black"
      >
        <div class="flex items-center justify-between font-medium">
          <span class="uppercase text-xs font-bold" :class="[{'text-blue-600': value.task_priority === 1},
                                                             {'text-teal-600': value.task_priority === 2},
                                                             {'text-orange-600': value.task_priority === 3},
                                                             {'text-red-600': value.task_priority === 4}]"
          >{{ taskPriority(value.task_priority) }}</span>
          <span class="text-xs text-slate-500">{{ value.date_created }}</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 items-center space-x-3">
          <form-button class="flex flex-none items-center justify-center w-10 h-10 rounded-full text-white hover:bg-red-300 hover:border-red-700"
                       :class="[{'badge__success': value.date_completed},
                                {'badge__warning': !value.date_completed}]"
                       :busy="todoCreate.fetch && hoverIndex === value.id" :disabled="todoCreate.fetch && hoverIndex === value.id" @mouseover="hoverElement(value.id)" @mouseleave="showIcon = false" @click="showModal(value.id, true)"
          >
            <fa-icon v-if="showIcon && hoverIndex === value.id" icon="trash" class="h-10 w-10" />
            <fa-icon v-else :icon="value.date_completed ? 'check' : 'exclamation-triangle'" class="h-10 w-10" />
          </form-button>
          <span class="text-base font-medium">{{ value.title }}</span>
        </div>
        <div> {{ value.description }}</div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 justify-between items-center gap-2">
          <div>
            <form-button class="flex items-center justify-center text-xs font-medium rounded-full px-4 py-1 space-x-1 border-2 border-black bg-white hover:bg-black hover:text-white text-black" @click="showModal(value.id)">
              <span>View</span>
            </form-button>
          </div>
          <div v-if="!value.date_completed">
            <form-button
              :busy="todoCreate.fetch" :disabled="todoCreate.fetch"
              class="flex items-center justify-center text-xs font-medium rounded-full px-4 py-1 space-x-1 border-2 border-black bg-white hover:bg-black hover:text-white text-black" @click="updateTask(value.id, 'complete-task')"
            >
              <span>Complete</span>
            </form-button>
          </div>
          <div>
            <form-button
              :busy="todoCreate.fetch" :disabled="todoCreate.fetch"
              class="flex items-center justify-center text-xs font-medium rounded-full px-4 py-1 space-x-1 border-2 border-black bg-white hover:bg-black hover:text-white text-black" @click="updateTask(value.id, 'archive-task')"
            >
              <span>{{ value.archived_at ? 'Recover' : 'Archive' }}</span>
            </form-button>
          </div>
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
import FormSelect from '~/components/Common/Inputs/FormSelect'
import SearchInput from '~/components/SearchInput'
import { PRIORITY_OPTIONS } from '~/constants/priority-options'
import { SORTING_OPTIONS } from '~/constants/sorting-options'

export default {
  components: {
    FormButton,
    FaIcon,
    Pagination,
    DashboardEmptyState,
    FormSelect,
    SearchInput
  },
  data () {
    return {
      showIcon: false,
      hoverIndex: null,
      selectedSortOption: null,
      searchKey: null
    }
  },
  computed: {
    ...mapState('todos', ['todos', 'todoCreate']),
    ...mapGetters('todos', ['formattedTodoList']),

    sortingOptions () {
      return SORTING_OPTIONS.map((e) => {
        return {
          name: e.name,
          value: e.value,
          column: e.column
        }
      })
    }
  },
  methods: {
    ...mapActions('todos', ['fetchTodos']),
    ...mapMutations('todos', ['setTodoCurrentPage', 'setTodoSortType', 'setTodoSearch']),
    taskPriority (val) {
      return PRIORITY_OPTIONS.find(({ value }) => value === val).name
    },
    changePage (page) {
      this.setTodoCurrentPage(page)
      this.fetchTodos()
    },
    async sortTodos () {
      await this.setTodoSortType(this.selectedSortOption)
      await this.setTodoSearch(this.searchKey)
      await this.fetchTodos()
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
