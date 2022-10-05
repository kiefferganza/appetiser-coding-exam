<template>
  <common-modal
    :show="show"
    size="xl"
    is-form
    :title="!isUpdate ? 'Create Todo' : 'Update Todo'"
    :busy="todoCreate.fetch"
    @save="submit"
    @close="closeModal"
  >
    <div
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
    }
  },
  data () {
    return {
      title: null,
      description: null,
      dueDate: new Date(),
      selectedTaskPriority: null,
      selectedStatus: null,
    }
  },
  computed: {
    ...mapState('todos', ['todoCreate']),
    isUpdate () {
      return !this.checkEmptyObject(this.updateData)
    },
    formattedDueDate () {
      return format(new Date(this.dueDate), 'yyyy-MM-dd')
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
      this.$emit('submit', {
        title: this.title,
        description: this.description,
        task_priority: this.selectedTaskPriority?.value,
        due_at: this.formattedDueDate,
        ...(this.isUpdate && { id: this.updateData.id }),
        isUpdate: this.isUpdate
      })
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
