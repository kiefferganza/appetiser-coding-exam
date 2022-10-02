<template>
  <common-modal
    :show="show"
    size="lg"
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
      <div class="py-3 pb-5">
        <form-group
          v-if="!isUpdate"
          class="w-full"
          label="Store Code"
          label-for="contact-option"
          required
        >
          <form-select
            v-model="selectedStoreCode"
            track-by="name"
            label="name"
            :allow-empty="false"
            :options="[]"
            name="contact-option"
          />
        </form-group>
      </div>
      <div class="pb-5">
        <div class="flex flex-col md:flex-row items-center gap-4 pb-1">
          <form-group
            class="w-full"
            label="Serial Number"
            label-for="name"
            required
          >
            <form-input
              v-model="serialNumber"
              required
              type="text"
              name="name"
              block
            />
          </form-group>
          <form-group
            class="w-full"
            label="Device Code"
            label-for="name"
            required
          >
            <form-input
              v-model="deviceCode"
              required
              type="text"
              name="name"
              block
            />
          </form-group>
        </div>
      </div>
      <div class="py-3 border-t pb-5">
        <form-group
          class="w-full"
          label="Device Type"
          label-for="contact-option"
          required
        >
          <form-select
            v-model="selectedDeviceType"
            track-by="name"
            label="name"
            :allow-empty="false"
            :options="deviceTypeOptions"
            name="contact-option"
          />
        </form-group>
      </div>
    </div>
  </common-modal>
</template>

<script>
import { mapState } from 'vuex'
import isEmpty from 'lodash/isEmpty'

import CommonModal from '~/components/Common/Modal'
import FormGroup from '~/components/Common/FormGroup'
import FormInput from '~/components/Common/Inputs/FormInput'
import ErrorMessage from '~/components/Common/ErrorMessage'
import AlertComponent from '~/components/Common/AlertComponent'
import FormSelect from '~/components/Common/Inputs/FormSelect'

export default {
  components: {
    AlertComponent,
    FormInput,
    FormSelect,
    FormGroup,
    ErrorMessage,
    CommonModal
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
      deviceCode: null,
      serialNumber: null,
      selectedStatus: null,
      selectedDeviceType: {
        value: 1,
        name: 'Android'
      },
      selectedStoreCode: null,
      deviceTypeOptions: [
        {
          value: 1,
          name: 'Android'
        },
        {
          value: 2,
          name: 'POS Hardware'
        }
      ],
      statusOptions: [
        {
          value: 1,
          name: 'Active'
        },
        {
          value: 0,
          name: 'Inactive'
        }
      ]
    }
  },
  computed: {
    ...mapState('todos', ['todoCreate']),
    isUpdate () {
      return !this.checkEmptyObject(this.updateData)
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
        storeCode: this.isUpdate ? '' : this.selectedStoreCode.value,
        posDeviceCode: this.deviceCode,
        serialNumber: this.serialNumber,
        deviceTypeID: this.selectedDeviceType.value,
        posDeviceID: this.updateData.posDeviceID,
        isUpdate: this.isUpdate,
        resetCount: this.updateData.resetCount,
        isActive: this.selectedStatus.value
      })
    },
    resetForm () {
      this.deviceCode = null
      this.serialNumber = null
    },
    closeModal () {
      this.$emit('close-user-modal')
    }
  }
}
</script>
