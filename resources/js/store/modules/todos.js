import axios from 'axios'
import { formatDistance } from 'date-fns'

// state
export const state = {
  todos: {
    fetch: false,
    paginationLength: 1,
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
    console.log(payload)
    state.todos.list = state.todos.list.map((data) => {
      if (data.id === payload.id) {
        return {
          created_at: payload.created_at,
          date_created: formatDistance(new Date(payload.created_at), new Date(), { addSuffix: true }),
          description: payload.description,
          due_at: payload.due_at,
          id: payload.id,
          task_priority: payload.task_priority,
          title: payload.title,
          updated_at: payload.updated_at,
          user_id: payload.user_id
        }
      }
      return {
        ...data
      }
    })
  },
  updateTodoStatus (state, payload) {
    state.todos.list = state.todos.list.map((data) => {
      if (data.id === payload.id) {
        return {
          created_at: payload.created_at,
          date_completed: payload.date_completed,
          date_created: formatDistance(new Date(payload.created_at), new Date(), { addSuffix: true }),
          description: payload.description,
          due_at: payload.due_at,
          id: payload.id,
          task_priority: payload.task_priority,
          title: payload.title,
          updated_at: payload.updated_at,
          user_id: payload.user_id
        }
      }
      return {
        ...data
      }
    })
  },
  removeTodo (state, payload) {
    const id = state.todos.list.findIndex(e => e.id === payload)
    state.todos.list.splice(id, 1)
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
        commit('setTodoPaginationLength', state.todos.paginationLength + 1)
      })
      .catch(({ response }) => {
        commit('setTodoCreateState', false)
        commit('setTodoCreateError', response.data.errors)
      })
  },
  async updateTodo ({ commit, state }, payload) {
    commit('setTodoCreateState', true)
    commit('setTodoCreateError', '')
    await axios
      .put(`/api/todos/${payload.id}`, {
        title: payload.title,
        description: payload.description,
        task_priority: payload.task_priority,
        due_at: payload.due_at
      })
      .then(({ data }) => {
        commit('setTodoCreateState', false)
        commit('updateTodoList', data.data)
      })
      .catch(({ response }) => {
        commit('setTodoCreateState', false)
        commit('setTodoCreateError', response.data.errors)
      })
  },
  async completeTask ({ commit, state }, payload) {
    commit('setTodoCreateState', true)
    commit('setTodoCreateError', '')
    await axios
      .post(`/api/todos/complete-task/${payload.id}`)
      .then(({ data }) => {
        commit('setTodoCreateState', false)
        commit('updateTodoStatus', data.data)
      })
      .catch(({ response }) => {
        commit('setTodoCreateState', false)
        commit('setTodoCreateError', response.data.errors)
      })
  },
  async deleteTask ({ commit, state }, payload) {
    commit('setTodoCreateState', true)
    commit('setTodoCreateError', '')
    await axios
      .delete(`/api/todos/${payload.id}`)
      .then(({ data }) => {
        commit('setTodoCreateState', false)
        commit('removeTodo', payload.id)
      })
      .catch(({ response }) => {
        commit('setTodoCreateState', false)
        commit('setTodoCreateError', response.data.errors)
      })
  }
}
