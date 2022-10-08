<template>
  <common-modal
    :show="show"
    :size="isReject ? 'md' : 'lg'"
    is-form
    :title="!isUpdate ? 'Create Task' : isReject ? '' : 'Update Task'"
    :save-title="isReject ? 'Delete Request' : 'Save'"
    :busy="todoCreate.fetch"
    @save="submit"
    @close="closeModal"
  >
    <div
      v-if="!isReject"
      class="sm:px-4"
      style="max-height: 30em"
    >
      <div class="pb-5">
        <div class="flex flex-col md:flex-row items-center gap-4 pb-1">
          <form-group
            class="w-full"
            label="title"
            label-for="title"
            required
          >
            <form-input
              v-model="title"
              required
              type="text"
              name="title"
              block
            />
            <p v-if="hasError('title')" class="mt-2 text-sm text-red-600 dark:text-red-500">
              <span class="font-medium">{{ todoCreate.error.title[0] }}</span>
            </p>
          </form-group>
        </div>
      </div>
      <div class="pb-5">
        <div class="flex flex-col md:flex-row items-center gap-4 pb-1">
          <form-group
            class="w-full"
            label="Description"
            label-for="description"
            required
          >
            <form-text-area v-model="description" name="description" />
            <p v-if="hasError('description')" class="mt-2 text-sm text-red-600 dark:text-red-500">
              <span class="font-medium">{{ todoCreate.error.description[0] }}</span>
            </p>
          </form-group>
        </div>
      </div>
      <div class="py-3 pb-5">
        <div class="flex flex-col md:flex-row items-center gap-4 pb-1">
          <form-group
            class="w-full"
            label="Priority"
            label-for="priority"
            required
          >
            <form-select
              v-model="selectedTaskPriority"
              track-by="name"
              label="name"
              :allow-empty="false"
              :options="taskPriorityOptions"
              name="priority"
            />
            <p v-if="hasError('task_priority')" class="mt-2 text-sm text-red-600 dark:text-red-500">
              <span class="font-medium">{{ todoCreate.error.task_priority[0] }}</span>
            </p>
          </form-group>
          <form-group
            class="w-full"
            label="Due Date"
            label-for="priority"
          >
            <form-date-picker v-model="dueDate" />
            <p v-if="hasError('due_at')" class="mt-2 text-sm text-red-600 dark:text-red-500">
              <span class="font-medium">{{ todoCreate.error.due_at[0] }}</span>
            </p>
          </form-group>
        </div>
      </div>
    </div>
    <div v-else class="flex flex-col items-center justify-center">
      <svg aria-hidden="true" class="mx-auto mb-4 w-14 h-14 text-gray-400 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
      <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
        Are you sure you want to delete this task?
      </h3>
    </div>
  </common-modal>
</template>

<script>
import { mapState } from 'vuex'
import isEmpty from 'lodash/isEmpty'
import format from 'date-fns/format'
import has from 'lodash/has'

import CommonModal from '~/components/Common/Modal'
import FormGroup from '~/components/Common/FormGroup'
import FormInput from '~/components/Common/Inputs/FormInput'
import FormSelect from '~/components/Common/Inputs/FormSelect'
import FormTextArea from '~/components/Common/Inputs/FormTextArea'
import FormDatePicker from '~/components/Common/Inputs/FormDatePicker'
import { PRIORITY_OPTIONS } from '~/constants/priority-options'

export default {
  components: {
    FormInput,
    FormSelect,
    FormGroup,
    CommonModal,
    FormTextArea,
    FormDatePicker
  },
  props: {
    show: {
      type: Boolean,
      default: false
    },
    updateData: {
      type: Object,
      default: () => {}
    },
    isReject: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      title: null,
      description: null,
      dueDate: null,
      selectedTaskPriority: null,
      selectedStatus: null
    }
  },
  computed: {
    ...mapState('todos', ['todoCreate']),
    isUpdate () {
      return !this.checkEmptyObject(this.updateData)
    },
    formattedDueDate () {
      return this.dueDate ? format(this.dueDate, 'yyyy-MM-dd') : null
    },
    taskPriorityOptions () {
      return PRIORITY_OPTIONS.map((e) => {
        return {
          name: e.name,
          value: e.value
        }
      })
    }
  },
  watch: {
    updateData (newData) {
      const priorityOptions = PRIORITY_OPTIONS.find((e) => e.value === newData.task_priority)
      this.$store.commit('todos/setTodoCreateError', '')
      if (this.isUpdate) {
        this.title = newData.title
        this.description = newData.description
        this.due_at = newData.due_at
        this.selectedTaskPriority = {
          name: priorityOptions.name,
          value: priorityOptions.value
        }
      } else {
        this.resetForm()
      }
    }
  },
  methods: {
    checkEmptyObject (data) {
      return isEmpty(data)
    },
    hasError (key) {
      return has(this.todoCreate.error, key)
    },
    submit () {
      if (!this.isReject) {
        this.$emit('submit', {
          title: this.title,
          description: this.description,
          task_priority: this.selectedTaskPriority?.value,
          due_at: this.formattedDueDate,
          ...(this.isUpdate && { id: this.updateData.id }),
          isUpdate: this.isUpdate
        })
      } else {
        this.$emit('update-task', {
          id: this.updateData.id,
          type: 'delete-task'
        })
      }
    },
    resetForm () {
      this.title = null
      this.description = null
      this.task_priority = null
      this.due_at = new Date()
    },
    closeModal () {
      this.$emit('close-user-modal')
    }
  }
}
</script>
