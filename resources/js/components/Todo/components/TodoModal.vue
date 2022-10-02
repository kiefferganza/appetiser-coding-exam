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
      class="overflow-y-auto overflow-x-hidden sm:px-4"
      style="max-height: 30em"
    >
      <alert-component v-show="todoCreate.error" class="pb-3" variant="danger">
        <error-message :message="todoCreate.error" />
      </alert-component>
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
          </form-group>
          <form-group
            class="w-full"
            label="Due Date"
            label-for="priority"
          >
            <form-date-picker v-model="dueDate" />
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

import CommonModal from '~/components/Common/Modal'
import FormGroup from '~/components/Common/FormGroup'
import FormInput from '~/components/Common/Inputs/FormInput'
import ErrorMessage from '~/components/Common/ErrorMessage'
import AlertComponent from '~/components/Common/AlertComponent'
import FormSelect from '~/components/Common/Inputs/FormSelect'
import FormTextArea from '~/components/Common/Inputs/FormTextArea'
import FormDatePicker from '~/components/Common/Inputs/FormDatePicker'

import { PRIORITY_OPTIONS } from '~/constants/priority-options'

export default {
  components: {
    AlertComponent,
    FormInput,
    FormSelect,
    FormGroup,
    ErrorMessage,
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
      dueDate: null,
      selectedTaskPriority: null
    }
  },
  computed: {
    ...mapState('todos', ['todoCreate']),
    isUpdate () {
      return !this.checkEmptyObject(this.updateData)
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
      this.$store.commit('todos/setTodoCreateError', '')
      const value = newData.posDeviceID

      if (this.isUpdate) {
        this.serialNumber = newData.serialNumber
        this.deviceCode = newData.posDeviceCode
        this.selectedStatus = {
          value: newData.isActive,
          name: newData.isActive === 1 ? 'Active' : 'Inactive'
        }
        this.selectedDeviceType = {
          value,
          name: value === 1 ? 'Android' : 'POS Hardware'
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
    submit () {
      this.$emit('submit', {
        title: this.title,
        description: this.description,
        task_priority: this.selectedTaskPriority.value,
        due_at: this.dueDate ? format(new Date(this.dueDate), 'yyyy-MM-dd') : null,
        isUpdate: this.isUpdate
      })
    },
    resetForm () {
      this.title = null
      this.description = null
      this.task_priority = null
      this.due_at = null
      this.isUpdate = null
    },
    closeModal () {
      this.$emit('close-user-modal')
    }
  }
}
</script>
