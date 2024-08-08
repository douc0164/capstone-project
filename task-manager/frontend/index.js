const baseUrl = 'http://127.0.0.1:8000'
const app = Vue.createApp({
  data: function () {
    return {
      title: 'Task Manager',
      token: '',
      lists: [],
      showNewList: false,
      showEditList: false,
      sortOption: '', 
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
      await this.getLists()
      this.sortLists() // Sort lists after fetching
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

        await this.getLists()
        this.sortLists() // Sort lists after login

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
        this.sortLists() // Sort lists after fetching
          
      } catch (error) {
        console.log(error)
      }
    },

    // ADD NEW LIST
    addList: async function () {
        try {
        const response = await fetch(`${baseUrl}/api/lists`, {
          method: 'post',
          headers: {
            'Authorization': `Bearer ${this.token}`,
            'Accept': 'application/json',
            'Content-Type':'application/json'
          },
          body: JSON.stringify(this.newList)
        })

        const json = await response.json()
        this.lists.push(json)
        this.showNewList = false

        this.sortLists() 
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
        this.sortLists() // Sort lists after editing a list

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
        this.sortLists() // Sort lists after deleting a list

      } catch (error) {
        console.log(error)
      }
    },

    // SORT LISTS
    sortLists: function () {
      if (this.sortOption === 'name-asc') {
        this.lists.sort((a, b) => a.list_name.localeCompare(b.list_name))
      } else if (this.sortOption === 'name-desc') {
        this.lists.sort((a, b) => b.list_name.localeCompare(a.list_name))
      } else if (this.sortOption === 'newest') {
        this.lists.sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
      } else if (this.sortOption === 'oldest') {
        this.lists.sort((a, b) => new Date(a.created_at) - new Date(b.created_at))
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
