const baseUrl = 'http://127.0.0.1:8000'
const urlParams = new URLSearchParams(window.location.search)
const listId = urlParams.get('list_id')

const app = Vue.createApp({
  data: function () {
    return {
      listName: '',
      tasks: [],
      showNewTask: false,
      newTask: {
        task_name: '',
        due_date: ''
      },
      token: sessionStorage.getItem('token') || ''
    }
  },

  created: async function () {
    if (this.token) {
      this.getListName()
      this.getTasks()
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

    // ADD TASK
    addTask: async function () {
      try {
        const response = await fetch(`${baseUrl}/api/lists/${listId}/tasks`, {
          method: 'post',
          headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${this.token}`,
            'Content-Type': "application/json"
          },
          body: JSON.stringify(this.newTask)
        })

        const json = await response.json()
        this.tasks.push(json)
        this.showNewTask = false

      } catch (error) {
        console.log(error)
      }
    }
  }
})

app.mount('#app')
