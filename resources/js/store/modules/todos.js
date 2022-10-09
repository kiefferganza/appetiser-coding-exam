import axios from 'axios'
import { formatDistance } from 'date-fns'

// state
export const state = {
  todos: {
    fetch: false,
    paginationLength: 1,
    page: 1,
    list: [],
    sortType: null,
    searchKey: null,
    lastInsertedID: null
  },
  tags: {
    fetch: false,
    paginationLength: 1,
    page: 1,
    list: [],
    sortType: null,
    searchKey: null,
  },
  todoCreate: {
    fetch: false,
    error: ''
  },
  todoFile: []
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
  setTagList (state, payload) {
    state.tags.list = payload
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
  setTodoSortType (state, payload) {
    state.todos.sortType = payload
  },
  setTodoSearch (state, payload) {
    state.todos.searchKey = payload
  },
  setTodoFile (state, payload) {
    state.todoFile = payload
  },
  setLastInsertedID (state, payload) {
    state.todos.lastInsertedID = payload
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
      .get(`api/todos?page=${state.todos.page}`, {
        params: {
          sortType: state.todos.sortType,
          search: state.todos.searchKey
        }
      })
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
  async fetchTags ({ commit, state }, payload) {
    commit('setTodoState', true)
    await axios
      .get('api/tags')
      .then(({ data }) => {
        commit('setTodoState', false)
        commit('setTagList', data.tags)
      })
      .catch(() => {
        commit('setTodoState', false)
        commit('setTagList', [])
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
        due_at: payload.due_at,
        tag: payload.tag
      })
      .then(({ data }) => {
        commit('setTodoCreateState', false)
        commit('addTodoList', data.data)
        commit('setLastInsertedID', data.data.id)
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
        due_at: payload.due_at,
        tag: payload.tag
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
  },
  async uploadFile ({ commit, state }, payload) {
    const config = {
      headers: { 'content-type': 'multipart/form-data' }
    }
    payload.file.append('todoID', state.todos.lastInsertedID)
    await axios
      .post('/api/upload', payload.file, config)
      .then(({ data }) => {
        commit('setTodoCreateState', false)
        commit('setTodoFile', data.data)
      })
      .catch(({ response }) => {
        commit('setTodoCreateState', false)
        commit('setTodoCreateError', response.data.errors)
      })
  }
}
