import axios from 'axios'
import { formatDistance } from 'date-fns'

// state
export const state = {
  todos: {
    fetch: false,
    paginationLength: 0,
    page: 1,
    list: []
  },
  todoCreate: {
    fetch: false,
    error: ''
  }
}

// getters
export const getters = {
  formattedTodoList: state => {
    return state.todos.list.map((data) => {
      return {
        ...data,
        date_created: formatDistance(new Date(data.created_at), new Date(), { addSuffix: true })
      }
    })
  }
}

// mutations
export const mutations = {
  setTodoList (state, payload) {
    state.todos.list = payload
  },
  setTodoState (state, payload) {
    state.todos.fetch = payload
  },
  setTodoPaginationLength (state, payload) {
    state.todos.paginationLength = payload
  },

  setTodoCurrentPage (state, payload) {
    state.todos.page = payload
  },
  addTodoList (state, payload) {
    if (state.todos.paginationLength >= 10) {
      state.todos.list.pop()
    }
    state.todos.list.unshift({
      created_at: payload.created_at,
      date_created: formatDistance(new Date(payload.created_at), new Date(), { addSuffix: true }),
      description: payload.description,
      due_at: payload.due_at,
      id: payload.id,
      task_priority: payload.task_priority,
      title: payload.title,
      updated_at: payload.updated_at,
      user_id: payload.user_id
    })
  },
  updateTodoList (state, payload) {
    state.todos.list = state.todos.list.map((data) => {
      if (data.id === payload.id) {
        return {
          ...data
        }
      }
      return {
        ...data
      }
    })
  },
  setTodoCreateState (state, payload) {
    state.todoCreate.fetch = payload
  },
  setTodoCreateError (state, payload) {
    state.todoCreate.error = payload
  }
}

// actions
export const actions = {
  async fetchTodos ({ commit, state }, payload) {
    commit('setTodoState', true)
    await axios
      .get(`api/todos?page=${state.todos.page}`, payload)
      .then(({ data }) => {
        commit('setTodoState', false)
        commit('setTodoList', data.data)
        commit('setTodoPaginationLength', data.total)
      })
      .catch(() => {
        commit('setTodoState', false)
        commit('setTodoList', [])
        commit('setTodoPaginationLength', 0)
      })
  },
  async createTodo ({ commit, state }, payload) {
    commit('setTodoCreateState', true)
    commit('setTodoCreateError', '')
    await axios
      .post('/api/todos', {
        title: payload.title,
        description: payload.description,
        task_priority: payload.task_priority,
        due_at: payload.due_at
      })
      .then(({ data }) => {
        commit('setTodoCreateState', false)
        commit('addTodoList', data.data)
      })
      .catch(({ response }) => {
        commit('setTodoCreateState', false)
        commit('setTodoCreateError', response.data.message)
      })
  },
  async updateTodos ({ commit, state }, payload) {
    commit('setPosCreateState', true)
    commit('setPosCreateError', '')
    await this.$api
      .post('/pos/update-pos-device/', {
        posDeviceID: payload.posDeviceID,
        posDeviceCode: payload.posDeviceCode,
        serialNumber: payload.serialNumber,
        deviceTypeID: payload.deviceTypeID,
        resetCount: payload.resetCount,
        isActive: payload.isActive
      })
      .then(() => {
        commit('setPosCreateState', false)
        this.app.$toast.success('Successfully updated todo.')
      })
      .catch(({ response }) => {
        commit('setPosCreateState', false)
        commit('setPosCreateError', response.data.message)
      })
  }
}
