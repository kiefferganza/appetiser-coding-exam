<template>
  <div class="animate__animated animate__fadeIn">
    <div
      class="flex-col-reverse md:flex-row flex sm:justify-between mb-4 md:mb-0"
    >
      <search-input v-if="searchable" :placeholder="searchPlaceholder" />
      <div class="flex items-center pt-4 md:pt-0">
        <slot name="widget" />
      </div>
    </div>
    <div class="shadow-md rounded-md">
      <div class="relative overflow-x-auto rounded-t-md">
        <table
          class="static min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-700"
        >
          <thead>
            <tr class="bg-white">
              <th
                v-for="(column, index) in data.columns"
                :key="index"
                scope="col"
                class="p-6 text-xs font-bold tracking-wider text-left text-ventamo-navy-blue uppercase dark:text-gray-400"
              >
                {{ column }}
              </th>
              <th v-if="showActionHeader" scope="col" class="p-4 font-bold">
                <span class="sr-only">Actions</span>
              </th>
            </tr>
          </thead>
          <tbody
            class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700"
          >
            <template v-if="data.rows.length > 0 && !fetching">
              <tr
                v-for="({ rowValues, id }, index) in data.rows"
                :key="index"
                class="hover:bg-gray-100 dark:hover:bg-gray-700"
              >
                <td
                  v-for="(values, i) in rowValues"
                  :key="i"
                  class="p-5 text-sm font-medium text-ventamo-navy-blue whitespace-nowrap dark:text-white"
                >
                  <slot :name="`table-data-${i}`" :data="values">
                    {{ values }}
                  </slot>
                </td>
                <td
                  class="py-4 px-6 text-sm font-medium text-right whitespace-nowrap relative"
                >
                  <button
                    v-click-outside="() => hideDropdown(id)"
                    class="text-blue-600 dark:text-blue-500"
                    @click="openActionDropdown(id)"
                  >
                    <common-fa-icon
                      icon="ellipsis-v"
                      class="flex-shrink-0 h-5 w-5 text-gray-400"
                    />
                  </button>
                  <action-dropdown
                    :id="id"
                    :ref="`action-${id}`"
                    class="z-50"
                    :items="data.callbacks"
                  >
                    <template
                      v-for="(callback, callbackIndex) in data.callbacks"
                      :slot="`action-button-${callbackIndex}`"
                    >
                      <slot
                        :id="id"
                        :name="`action-buttons-${callbackIndex}`"
                        :data="rowValues"
                        :title="callback.title"
                      />
                    </template>
                  </action-dropdown>
                </td>
              </tr>
            </template>
            <tr v-if="data.rows.length === 0 && !fetching">
              <td :colspan="data.columns.length + 1">
                <empty-state
                  :title="emptyStateTitle"
                  :description="emptyStateDescription"
                />
              </td>
            </tr>
            <tr v-if="fetching">
              <td :colspan="data.columns.length + 1" class="text-center py-4">
                <common-fa-icon
                  icon="spinner"
                  class="h-12 w-12 text-gray-400 mx-auto"
                  spin
                />
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <pagination
        v-if="pagination && data.rows.length > 0"
        class="rounded-b-md"
        :length="paginationLength"
        :page="paginationPage"
        :limit="paginationLimit"
        @paginate="(e) => $emit('table-paginate', e)"
      />
    </div>
  </div>
</template>

<script>
import clickOutside from '../../../directives/clickoutside'
import ActionDropdown from '~/components/Common/Table/components/ActionDropdown'
import SearchInput from '~/components/Common/Table/components/SearchInput'
import EmptyState from '~/components/Common/EmptyState'
import Pagination from '~/components/Common/Table/components/Pagination'
import CommonFaIcon from '~/components/Common/FaIcon'

export default {
  components: { Pagination, EmptyState, SearchInput, ActionDropdown, CommonFaIcon },
  props: {
    data: {
      type: Object,
      required: true
    },
    searchable: {
      type: Boolean,
      default: false
    },
    paginationLength: {
      type: Number,
      default: 0
    },
    paginationLimit: {
      type: Number,
      default: 10
    },
    paginationPage: {
      type: Number,
      default: 1
    },
    pagination: {
      type: Boolean,
      default: false
    },
    fetching: {
      type: Boolean,
      default: false
    },
    searchPlaceholder: {
      type: String,
      default: ''
    },
    emptyStateTitle: {
      type: String,
      default: ''
    },
    emptyStateDescription: {
      type: String,
      default: ''
    }
  },
  data () {
    return {
      showAction: false,
      showActionHeader: true
    }
  },

  methods: {
    hideDropdown (id) {
      if (this.$refs[`action-${id}`]) {
        this.$refs[`action-${id}`][0].close()
      }
    },
    openActionDropdown (id) {
      this.$refs[`action-${id}`][0].open()
    }
  }
}
</script>
