const baseUrl = 'http://127.0.0.1:8000'
const app = Vue.createApp({
    data() {
        return {
            title: 'Task Manager',
            token: sessionStorage.getItem('token'),
            lists: [],
            newList: {
                list_name: '',
                user_id: sessionStorage.getItem('user_id'),
            },
            editForm: { list_name: '' },
            showNewList: false,
            showEditList: false,
            updateListId: null,
            showRegistrationForm: false,
            loginForm: { email: '', password: '' },
            registrationForm: {
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
            },
            sortOption: '',
            loading: false,
            loginError: false,
            loginErrorMessage: '',
            registerError: false,
            registerErrorMessage: '',
        }
    },

    //LOGIN
    methods: {
        async login() {
            this.loading = true
            const response = await fetch(`${baseUrl}/api/login`, {
                method: 'post',
                headers: { 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(this.loginForm),
            })

            const data = await response.json()//converts response to json

            if (response.ok) {
                //save token
                this.token = data.token
                this.user_id = data.user_id
                sessionStorage.setItem('token', this.token)
                sessionStorage.setItem('user_id', this.user_id)
                this.loginForm.email = ''
                this.loginForm.password = ''
                this.fetchLists()
                this.sortLists() // Sort lists after fetching
            } else {
                console.error(data.message)
                this.loginError = true
                this.loginErrorMessage = data.message
            }
            this.loading = false
        },

        //REGISTRATION
        async register() {
            if (this.registrationForm.password !== this.registrationForm.password_confirmation) {
                this.registerError = true
                this.registerErrorMessage = 'Entered passwords do not match. Try again.'
                return
            }

            this.loading = true
            const response = await fetch(`${baseUrl}/api/register`, {
                method: 'post',
                headers: { 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(this.registrationForm),
            })
            const data = await response.json() //converts response to json

            if (response.ok) {
                this.registrationForm.name = ''
                this.registrationForm.email = ''
                this.registrationForm.password = ''
                this.registrationForm.password_confirmation = ''
                this.showRegistrationForm = false
                //redirect to login page after registration
                this.loginForm.email = this.registrationForm.email
                this.loginForm.password = this.registrationForm.password
                this.login()
            } else {
                console.log(data.errors)
                this.registerError = true
                this.registerErrorMessage = data.errors.email?.[0] || data.errors.name?.[0] || data.errors.password?.[0]
            }
            this.loading = false
        },

        //DISPLAY, GET LISTS
        async fetchLists() {
            this.loading = true
            const response = await fetch(`${baseUrl}/api/lists/${sessionStorage.getItem('user_id')}`, {
                headers: { 
                    'Authorization': `Bearer ${this.token}` },
            })
            this.lists = await response.json()
            this.sortLists() // Sort lists after fetching
            this.loading = false
        },
            
        //ADD NEW LIST
        async addList() {
            this.loading = true
            this.newList.user_id = sessionStorage.getItem('user_id')
            const response = await fetch(`${baseUrl}/api/lists/add`, {
                method: 'post',
                headers: {
                    'Authorization': `Bearer ${this.token}`,
                    'Accept': 'application/json',
                    'Content-Type':'application/json'
                },
                body: JSON.stringify(this.newList),
            })
            if (response.ok) {
                this.newList.list_name = ''
                this.showNewList = false
                this.fetchLists()
            } else {
                console.error(await response.json())
            }
            this.loading = false
        },

        // EDIT - DISPLAY NOTE INFO THAT WILL BE EDITED
        async editButtOnClick(list) {
            this.showEditList = true
            this.updateListId = list.id
            this.editForm.list_name = list.list_name
        },

        // EDIT - SAVE CHANGES
        async updateList() {
            this.loading = true
            const response = await fetch(`${baseUrl}/api/lists/${this.updateListId}`, {
                method: 'put',
                headers: {
                    'Content-Type': 'application/json',
                    Authorization: `Bearer ${this.token}`,
                },
                body: JSON.stringify(this.editForm),
            })
            if (response.ok) {
                this.editForm.list_name = ''
                this.showEditList = false
                this.fetchLists()
                this.sortLists() // Sort lists after editing a list
            } else {
                console.error(await response.json())
            }
            this.loading = false
        },

        //DELETE
        async deleteList(list) {
            this.loading = true
            const response = await fetch(`${baseUrl}/api/lists/${list.id}`, {
                method: 'DELETE',
                headers: { Authorization: `Bearer ${this.token}` },
            })
            if (response.ok) {
                this.fetchLists()
            } else {
                console.error(await response.json())
            }
            this.loading = false
        },

        //SORT LISTS
        sortLists: function () {
            if (this.sortOption === 'name-asc') {
                this.lists.sort((a, b) => a.list_name.localeCompare(b.list_name));
            } else if (this.sortOption === 'name-desc') {
                this.lists.sort((a, b) => b.list_name.localeCompare(a.list_name));
            } else if (this.sortOption === 'newest') {
                this.lists.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
            } else if (this.sortOption === 'oldest') {
                this.lists.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
            }
        },

        //LOGOUT
        async logout() {
            sessionStorage.removeItem('token')
            sessionStorage.removeItem('user_id')
            this.token = null
        },
    },

    //automatically loads tasks when logged in
    mounted() {
        if (this.token) {
            this.fetchLists()
        }
    },
})

app.mount('#app')
