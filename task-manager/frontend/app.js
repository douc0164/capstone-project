const baseUrl = 'http://127.0.0.1:8000'
const app = Vue.createApp({
  data: function () {
    return {
      title: 'Task Manager',
      token: '',
      lists: [],
      showNewList: false,
      showEditList: false,

      // login
      loginForm: {
        email: '',
        password: ''
      },

      // add list
      newList: {
        list_name: ''
      },

      // edit list
      editForm: {
        id: '',
        list_name: '',
        created_at: '',
        updated_at: ''
      }
    }
  },

  // KEEP USER LOGGED IN
  created: async function () {
    this.token = sessionStorage.getItem('token') || ''
    if (this.token) {
      this.getLists()
    }
  },

  methods: {
    // LOGIN
    login: async function () {
      try {
        const response = await fetch(`${baseUrl}/login`, {
          method: 'post',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify(this.loginForm)
        })

        const json = await response.json()

        // Save token
        this.token = json.token
        sessionStorage.setItem('token', this.token)

        this.getLists()

      } catch (error) {
        console.log(error)
      }
    },

    // DISPLAY, GET LISTS
    getLists: async function () {
      try {
        const response = await fetch(`${baseUrl}/api/lists`, {
          method: 'get',
          headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${this.token}`
          }
        })
        this.lists = await response.json()

      } catch (error) {
        console.log(error)
      }
    },

    // ADD LIST
    addList: async function () {
      try {
        const response = await fetch(`${baseUrl}/api/lists`, {
          method: 'post',
          headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${this.token}`,
            'Content-Type': "application/json"
          },
          body: JSON.stringify(this.newList)
        })

        const json = await response.json()
        this.lists.push(json)
        this.showNewList = false

      } catch (error) {
        console.log(error)
      }
    },

    // EDIT - DISPLAY NOTE INFO THAT WILL BE EDITED
    editList: function (list) {
      this.editForm = {
        id: list.id,
        list_name: list.list_name,
        created_at: list.created_at,
        updated_at: list.updated_at
      }
      this.showEditList = true
    },

    // EDIT - SAVE CHANGES
    updateList: async function () {
      try {
        const response = await fetch(`${baseUrl}/api/lists/${this.editForm.id}`, {
          method: 'put',
          headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${this.token}`,
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(this.editForm)
        })

        const json = await response.json()
        const index = this.lists.findIndex(list => list.id === json.id)
        this.lists.splice(index, 1, json)
        this.showEditList = false

      } catch (error) {
        console.log(error)
      }
    },

    // DELETE LIST
    deleteList: async function (list) {
      try {
        await fetch(`${baseUrl}/api/lists/${list.id}`, {
          method: 'delete',
          headers: {
            'Authorization': `Bearer ${this.token}`
          }
        })
        this.lists = this.lists.filter(l => l.id !== list.id)
      } catch (error) {
        console.log(error)
      }
    },

    // LOGOUT
    logout: function () {
      this.token = ''
      sessionStorage.removeItem('token')
    }
  }
})

app.mount('#app')
