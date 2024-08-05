const baseUrl = 'http://127.0.0.1:8000'
const urlParams = new URLSearchParams(window.location.search)
const listId = urlParams.get('list_id')

const app = Vue.createApp({
  data: function () {
    return {
      listName: '',
      tasks: [],
      priorities: [],
      showNewTask: false,
      showEditTask: false,
      sortOption: '',

      newTask: {
        task_name: '',
        due_date: '',
      },
      editTask: {
        task_name: '',
        due_date: '',
      },
      token: sessionStorage.getItem('token') || ''
    }
  },

  created: async function () {
    if (this.token) {
      this.getListName()
      this.getTasks()
      this.getPriorities()
    }
  },

  methods: {
    // GET LIST NAME
    getListName: async function () {
      try {
        const response = await fetch(`${baseUrl}/api/lists/${listId}`, {
          method: 'get',
          headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${this.token}`
          }
        })
        const list = await response.json()
        this.listName = list.list_name

      } catch (error) {
        console.log(error)
      }
    },

    // GET TASKS
    getTasks: async function () {
      try {
        const response = await fetch(`${baseUrl}/api/lists/${listId}/tasks`, {
          method: 'get',
          headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${this.token}`
          }
        })
        this.tasks = await response.json()

      } catch (error) {
        console.log(error)
      }
    },

    // GET PRIORITIES
    getPriorities: async function () {
      try {
        const response = await fetch(`${baseUrl}/api/priorities`, {
          method: 'get',
          headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${this.token}`
          }
        })
        this.priorities = await response.json()

      } catch (error) {
        console.log(error)
      }
    },

    // ADD TASK
    addTask: async function () {
      try {
        const response = await fetch(`${baseUrl}/api/lists/${listId}/tasks`, {
          method: 'post',
          headers: {
            'Authorization': `Bearer ${this.token}`,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(this.newTask)
        })

        const json = await response.json()
        this.tasks.push(json)
        this.showNewTask = false

      } catch (error) {
        console.log(error)
      }
    },

    // EDIT TASK
    showEditTaskModal(task) {
      this.editTask = { ...task }
      this.showEditTask = true
    },

    // UPDATE TASK
    updateTask: async function () {
      try {
        const response = await fetch(`${baseUrl}/api/lists/${listId}/tasks/${this.editTask.id}`, {
          method: 'put',
          headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${this.token}`,
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(this.editTask)
        })

        const json = await response.json()
        const index = this.tasks.findIndex(task => task.id === this.editTask.id)
        this.tasks.splice(index, 1, json)
        this.showEditTask = false

      } catch (error) {
        console.log(error)
      }
    },

    // DELETE TASK
    deleteTask: async function (task) {
      try {
        await fetch(`${baseUrl}/api/lists/${listId}/tasks/${task.id}`, {
          method: 'delete',
          headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${this.token}`
          }
        })

        const index = this.tasks.findIndex(t => t.id === task.id)
        this.tasks.splice(index, 1)

      } catch (error) {
        console.log(error)
      }
    },

    // SORT TASKS
    sortTasks() {
      if (this.sortOption === 'name-asc') {
        this.tasks.sort((a, b) => a.task_name.localeCompare(b.task_name))
      } else if (this.sortOption === 'name-desc') {
        this.tasks.sort((a, b) => b.task_name.localeCompare(a.task_name))
      } else if (this.sortOption === 'date-asc') {
        this.tasks.sort((a, b) => new Date(a.due_date) - new Date(b.due_date))
      } else if (this.sortOption === 'date-desc') {
        this.tasks.sort((a, b) => new Date(b.due_date) - new Date(a.due_date))
      }
    }
  },

  computed: {
    sortedTasks() {
      return this.tasks
    }
  }
})

app.mount('#app')
